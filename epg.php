<?php
/**
 * // epg.php 按需返回不同格式的节目数据
 * // 2024年1月10日 16:53:24
 * // author: aming.ou QQ3364776
 * // http://127.0.0.1/epg.php?channel=cctv2&date=20231218          #for sptv json
 * // http://127.0.0.1/epg.php?ch=CCTV1&date=2023-12-18             #for diyp json
 * // http://127.0.0.1/epg.php?ch=xml // Provided by: QQ 791019898  #for iptv xml
 */ 
// 运行参数
error_reporting(0);
$utf8 = false; // true输出utf8编码, false输出unicode编码
$iscache = false; //如果开启了memcached服务, 建议设置为true,否则设置false
$cachehour = 2; //当天后的epg数据缓存小时数,建议根据xml同步周期进行设置, 推荐2小时
$debug = !empty($_GET["debug"]) ? true : false;
// 传入参数
$riqi = !empty($_GET["date"]) ? $_GET["date"] : date("Y-m-d");
$ch = !empty($_GET["ch"]) ? $_GET["ch"] : $_GET["channel"];
$ch = empty($ch) ? 'CCTV1' : $ch;
// 'diyp' ,返回DIYP final版的epg数据, sptv 返回超级直播的epg数据, 默认返回DIYP epg格式
$tvapp = !empty($_GET["channel"]) ? 'sptv' : 'diyp';

if ($debug){
	echo var_dump($utf8) . "<br>" . $riqi . "<br>" . $ch . "<br>" . $tvapp . "<br>"  ;
	$iscache = false; // 测试模式,关闭缓存
}

// 数据库连接串
class ChannelDB extends SQLite3
{
	static $isw = false; // $isw = true 时写入访问日志, $isw = false时不写入日志,建议调测时设置为true,正式使用时设置为false,加快epg显示速度
	function __construct()
	{ 
		// 根据项目修改sqlite数据库名
		$sdb = "channel_epg.db"; 
		$this->open($sdb, self::$isw ? SQLITE3_OPEN_READWRITE : SQLITE3_OPEN_READONLY);
	}
    public static function isw()
    {
        return self::$isw;
    }
}
// 自适应日期格式 YYYYMMDD 转换为 YYYY-MM-DD
if (strlen($riqi) == 8)
{
	$string = $riqi;
	$year = substr($string, 0, 4);
	$month = substr($string, 4, 2);
	$day = substr($string, 6, 2);
	$riqi = $year . "-" . $month . "-" . $day;
}
$isnewDate = strtotime($riqi) >= strtotime(date("Y-m-d")) ? true : false;

if ($iscache)
{
	if (class_exists('Memcache'))
	{
		$memcache = new Memcache;
		if (!@$memcache->connect('localhost', 11211))
		{
			$iscache = false;
		}
		if ($iscache)
		{
			$key = md5($ch . '|' . $riqi . '|' . $tvapp);
			$cache_result = $memcache->get($key);
			if ($cache_result)
			{	
				if (!$debug){
					if ($ch === 'xml')
					{
						header_remove();
						header('Content-Type: text/xml; charset=utf-8');
						header('Content-Disposition: attachment; filename=epg.xml');
					}else{
						header_remove();
						header('Content-type: application/json');
						header('Content-Disposition: attachment; filename=epg.json');
					}
				}
				echo $cache_result;
				return;
			}
		}
	}
	else
	{
		$iscache = false;
	}
}
$config = array();
$channel = new ChannelDB();
$isw = $channel->isw();

$group = 'xxxxx';
// --写入日志-开始--------------------------------------------------
// 当前IP
$ip = $_SERVER['REMOTE_ADDR'];
$time = date("Y-m-d H:i:s"); 
// 当前url
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
// 获取最后来源地址
if (empty($_SERVER['HTTP_REFERER']))
{
	$source_link = $url;
}
else
{
	$source_link = $_SERVER['HTTP_REFERER'];
}
$source_link = urldecode($source_link);
// 将IP地址记录到日志文件或数据库中
if ($isw) $channel->exec("INSERT or ignore INTO access_log (ip_address,access_time,url) VALUES ('N{$ip}','{$time}','{$source_link}');");
// --写入日志-结束--------------------------------------------------

// 开始处理epg数据
if ($ch === 'xml')
{ 
	// iptv xml format
	// Provided by: QQ 791019898
	// 初始化 XMLTV 数据
	$channel_data = array();
	$programme_data = array(); 
	$channels = array();
	// 获取今天开始的所有频道节目数据,并去重
	$sql = "SELECT DISTINCT e.channel, e.title, e.sdate, e.sstart, e.sstop
			FROM epg_programme e where e.sdate >= '" . $riqi . "' and e.channel in (select DISTINCT channel_id from epg_channel)" ;
	$retval = $channel->query($sql);
	while ($row = $retval->fetchArray())
	{ 
		// 整理节目信息
		$channel_id = $row['channel'];
		if (!isset($channels[$channel_id]))
		{
			$channels[$channel_id] = 0;
		}
		$channels[$channel_id]++ ;
		$start_time = date('YmdHis O', strtotime($row['sdate'] . ' ' . $row['sstart']));
		$end_time = date('YmdHis O', strtotime($row['sdate'] . ' ' . $row['sstop']));
		$programme_data[] = "\t<programme channel=\"$channel_id\" start=\"$start_time\" stop=\"$end_time\">\n\t\t<title lang=\"zh\">" . htmlspecialchars($row['title']) . "</title>\n\t</programme>";
	} 
	// 获取所有频道数据
	$sql = "SELECT c.* FROM epg_channel c;" ;
	$retval = $channel->query($sql);
	while ($row = $retval->fetchArray())
	{ 
		// 添加频道信息（如果尚未添加）
		$channel_name = $row['name'];
		$channel_id = $row['channel_id'];
		if (isset($channels[$channel_id]) && !isset($channel_data[$channel_name]))
		{
			$channel_data[$channel_name] = "\t<channel id=\"$channel_id\">\n\t\t<display-name lang=\"zh\">$channel_name</display-name>\n\t</channel>";
		}
	} 
	// 输出 XMLTV 数据
	$xmltv_data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<tv generator-info-name=\"epg\" generator-info-url=\"epg.php?ch=xml\">\n" . implode("\n", $channel_data) . "\n" . implode("\n", $programme_data) . "\n</tv>"; 
	// 如果开启了缓存
	if ($iscache)
	{
		$memcache->set($key, $xmltv_data, MEMCACHE_COMPRESSED, 3600 * $cachehour);
	} 
	// 输出 XMLTV 数据
	if (!$debug){
	header_remove();
	header('Content-Type: text/xml; charset=utf-8');
	header('Content-Disposition: attachment; filename=epg.xml');}
	echo $xmltv_data;
}
else
{ 
	// diyp $ sptv format
	// 查询频道名对应的节目单是否存在
	$sql = "SELECT DISTINCT * FROM epg_programme WHERE channel = (SELECT channel_id  FROM epg_channel where upper(name)='" . strtoupper($ch) . "' limit 1) AND sdate = '" . $riqi . "'";
	$is_found = 0;
	$retval = $channel->query($sql);
	while ($row = $retval->fetchArray())
	{
		$is_found = 1;
		if ($tvapp == 'sptv')
		{
			$epg_data2s[] = array("eventId" => "",
				"showTime" => gmdate('H:i', (strtotime($riqi . ' ' . $row['sstop']) - strtotime($riqi . ' ' . $row['sstart']))),
				"eventType" => "",
				"st" => strtotime($riqi . ' ' . $row['sstart']),
				"t" => $row['title'],
				"duration" => (strtotime($riqi . ' ' . $row['sstop']) - strtotime($riqi . ' ' . $row['sstart'])),
				"et" => strtotime($riqi . ' ' . $row['sstop'])
				);
		}
		else
		{
			$epg_datas[] = array("start" => $row['sstart'],
				"end" => $row['sstop'],
				"title" => $row['title'],
				"desc" => ""
				);
		}
	}
	if ($is_found == 1)
	{
		if ($tvapp == 'sptv')
		{
			$age = ["$ch" => array("isLive" => "",
				"liveSt" => "",
				"program" => $epg_data2s,
				"channelName" => "$ch",
				"lvUrl" => ""
				)];
		}
		else
		{
			$age = array("channel_name" => "$ch",
				"date" => "$riqi",
				"epg_data" => $epg_datas
				);
		}
		$jsonage = $utf8 ? json_encode($age) : json_encode($age, JSON_UNESCAPED_UNICODE);
	}
	else
	{ 
		// 空节目表，可用以回看定位
		for($i = 0;
			$i <= 23;
			$i++)
		{
			$epg_datas[] = array("start" => sprintf("%02d", $i) . ":00",
				"end" => sprintf("%02d", $i) . ":59",
				"title" => "精彩节目",
				"desc" => ""
				);
			$epg_data2s[] = array("eventId" => "",
				"showTime" => gmdate('H:i', (strtotime($riqi . ' ' . sprintf("%02d:59", $i)) - strtotime($riqi . ' ' . sprintf("%02d:00", $i)) + 60)),
				"eventType" => "",
				"st" => strtotime($riqi . ' ' . sprintf("%02d:00", $i)),
				"t" => "精彩节目",
				"duration" => (strtotime($riqi . ' ' . sprintf("%02d:59", $i)) - strtotime($riqi . ' ' . sprintf("%02d:00", $i)) + 60),
				"et" => strtotime($riqi . ' ' . sprintf("%02d:59", $i))
				);
		}
		if ($tvapp == 'sptv')
		{
			$age = ["$ch" => array("isLive" => "",
				"liveSt" => "",
				"program" => $epg_data2s,
				"channelName" => "$ch",
				"lvUrl" => ""
				)];
		}
		else
		{
			$age = array("channel_name" => "$ch",
				"date" => "$riqi",
				"epg_data" => $epg_datas
				);
		}
		$jsonage = $utf8 ? json_encode($age) : json_encode($age, JSON_UNESCAPED_UNICODE);
	}
	if ($iscache)
	{
		if ($isnewDate)
		{
			$memcache->set($key, $jsonage, MEMCACHE_COMPRESSED, 3600 * $cachehour);
		}
		else
		{
			$memcache->set($key, $jsonage, MEMCACHE_COMPRESSED, 3600 * 24 * 8);
		}
	}
	if (!$debug){
	header_remove();
	header('Content-type: application/json');
	header('Content-Disposition: attachment; filename=epg.json');}
	echo $jsonage;
}
?>