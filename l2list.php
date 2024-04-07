<!DOCTYPE html>
<html>
<head>
<head>
<title>直播源数据转化240123v7</title>
<script>
	function disableRadios(radio) {
		var checkboxes = document.getElementsByTagName('input');
		if (radio.value == 'deb'){
		   for (var i = 0; i < checkboxes.length; i++) {
			  if (checkboxes[i].name == 'gender2') {
				 checkboxes[i].disabled = true;
			  }
		   }
	   }else{
		   for (var i = 0; i < checkboxes.length; i++) {
			  if (checkboxes[i].name == 'gender2') {
				 checkboxes[i].disabled = false;
			  }
		   }
	   }
	}
　　 function trim(str){ //删除左右两端的空格
　　     return str.replace(/(^\s*)|(\s*$)/g, "");
　　 }
	function disablebt(textArea) {
		var t = trim(textArea.value);
		if (t.length > 12)
		{
			t = t.substring(0,4).toUpperCase();
			if (t == 'HTTP'){
				document.getElementById("button1").disabled=true;
				document.getElementById("button2").disabled=false;
		    }else{
				document.getElementById("button1").disabled=false;
				document.getElementById("button2").disabled=true;
		    }
		}else{
				document.getElementById("button1").disabled=true;
				document.getElementById("button2").disabled=true;
		}
	}
	function bt1() {
		document.getElementById("button1").disabled=false;
		document.getElementById("button2").disabled=true;
	}
	function bt2() {
		document.getElementById("button1").disabled=true;
		document.getElementById("button2").disabled=false;
	}
	function saveFile() {
		var inValue = document.getElementById("outContent").value;
		var st = document.getElementById("savetype").value;
		const now = new Date().getTime();
		const fileName = "live_" + now + "." + st;
		var tempLink = document.createElement("a");
		var taBlob = new Blob([inValue], { type: "text/plain;charset=utf-8" });
		tempLink.setAttribute('href', URL.createObjectURL(taBlob));
		tempLink.setAttribute('download', fileName);
		tempLink.click();
		URL.revokeObjectURL(tempLink.href);
	}
	function importFile() {
  		var fileInput = document.getElementById("_ef");
        fileInput.click();
	}
    function beforeUpload(event){
		var fileInput = document.getElementById("_ef");
		if (fileInput.files && fileInput.files[0])
		{
			var file = fileInput.files[0];
			const reader = new FileReader();
			reader.onload = (event) => {
				const content = event.target.result;
				var varId = document.getElementById("m3uContent");
				varId.value = content;
				disablebt(varId);
  			}
			reader.readAsText(file);
		}
	}
</script>
<style type="text/css">
.tex{
	background:#efefef;
	resize:none;
}
</style>
</head>
<body>
<?php
error_reporting(0);
// http://127.0.0.1/diyp/l2list.php
// aming.ou QQ:3364776 

// 判断是否有表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['gender']))
	{
		$tosql = $_POST['gender'];
	}else{
		$tosql = 'deb';
		$_POST['gender'] = $tosql;
	}
	if(isset($_POST['gender2']))
	{
		$optmerge = $_POST['gender2'];
		$merge = $optmerge !='3' ? true : false ;
	}else{
		$optmerge = '3';
		$_POST['gender2'] = '3';
		$merge = false;
	}
	if ($tosql == 'deb' or $tosql == '') 
	{
		$debug = true;
		$merge = false;
	}else{
		$debug = false;
	}
	if ($optmerge == '3') $merge = false;

	$isdelCounter = 1;
	
	if (class_exists("SQLite3"))
	{
		$db = new SQLite3(':memory:'); // 这将创建一个临时的内存数据库
		$db->exec("CREATE TABLE if not exists 'tmp_list' (item text, title text, epg text, url text, isdel integer null default 120);");
		if ($debug) $db = null;
	}else{
		$db = null;
	}
}else{
	$_POST['gender'] = 'deb';
	$_POST['gender2'] = '2';
}
?> 
<h2>请输入M3U/TXT文本或者直播源网址URL列表：</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];
?>">
	<textarea id="m3uContent" name="m3uContent" rows="10" cols="100%" placeholder="这里输入M3U/TXT文本内容，或者每行一条直播源网址URL" style="width: 1024px;" onInput="disablebt(this)"><?php if (isset($_POST['m3uContent'])) echo $_POST['m3uContent']; ?></textarea>
	<div style="width:1024px"><input type="file" id="_ef" name="_ef" style="visibility:hidden;" onchange="beforeUpload()"><button id="openf" name="openf" type="button" style="float:right" onClick="importFile()">打开文件</button></div>
    <font size="" color="#000000"><b>输出：</b></font>
	<input type="radio" name="gender" value="deb" <?php if(isset($_POST['gender']) && $_POST['gender'] == 'deb') echo "checked"; ?> onchange="disableRadios(this)"> 调试模式
	<input type="radio" name="gender" value="sql" <?php if(isset($_POST['gender']) && $_POST['gender'] == 'sql') echo "checked"; ?> onchange="disableRadios(this)"> 生成SQL
    <input type="radio" name="gender" value="txt" <?php if(isset($_POST['gender']) && $_POST['gender'] == 'txt') echo "checked"; ?> onchange="disableRadios(this)"> 生成TXT
	<input type="radio" name="gender" value="m3u" <?php if(isset($_POST['gender']) && $_POST['gender'] == 'm3u') echo "checked"; ?> onchange="disableRadios(this)"> 生成M3U
	<br>
	<font size="" color="#000000" style="line-height: 3;"><b>清洗：</b></font>
    <input type="radio" name="gender2" value="1" <?php if(isset($_POST['gender2']) && $_POST['gender2'] == '1') echo "checked"; ?> > 去重
	<input type="radio" name="gender2" value="2" <?php if(isset($_POST['gender2']) && $_POST['gender2'] == '2') echo "checked"; ?> > 合并
	<input type="radio" name="gender2" value="3" <?php if(isset($_POST['gender2']) && $_POST['gender2'] == '3') echo "checked"; ?> > 不处理
	<br>
	<input type="submit" id="button1" name="button1" value="转换M3U/TXT文本" disabled="true"> - 
	<input type="submit" id="button2" name="button2" value="解析M3U/TXT网址" disabled="true">
</form>
<?php

$start = microtime(true); 
// m3u 测试数据
$stringm3u = <<<CUT
#EXTM3U
#EXTINF:-1 tvg-id="" tvg-name="A&E" tvg-logo="" group-title="ENTRETENIMIENTO",A&E`http://nxtv.tk:8080/live/jarenas/iDKZrC56xZ/76.ts
http://nxtv.tk:8080/live/jarenas/iDKZrC56xZ/76.ts
#EXTINF:-1 tvg-id="" tvg-name="ABC Puerto Rico" tvg-logo="" group-title="NACIONALES",ABC Puerto Rico
http://nxtv.tk:8080/live/jarenas/iDKZrC56xZ/96.ts
CUT;

// txt 测试数据
$stringtxt = <<<CUT
广东频道,#genre#
广州综合,http://nas.jdshipin.com:8801/gztv.php?id=zhonghe
广州新闻,http://nas.jdshipin.com:8801/gztv.php?id=xinwen#http://113.100.193.10:9901/tsfile/live/1000_1.m3u8
直播频道,#genre#
CCTV2,http://dbiptv.sn.chinamobile.com/PLTV/88888893/224/3221226195/index.m3u8?0.smil
CUT;

// 网站m3u数据源URL数组 测试数据
$dataSources = [
'http://127.0.0.1/diyp/live.php',
'https://iptv.tgsd.com/diyp/ycl_iptv.txt',
'http://127.0.0.1/diyp/m3u.php'
]; 

// 拆分字符串后取数组中出现次数最多的元素
function maxstr($string)
{
	$string = trim($string);
	if (strlen($string) === 0)
	{
		$more_value = '';
	}else{
		$array = array_filter(explode("#", $string)); 
		$frequency = array_count_values($array); 
		arsort($frequency); 
		$max_number = reset($frequency);
		$more_value = key($frequency);
	}
	return $more_value;
}
// 分许m3u格式的字符串
function formatm3u($string)
{
	preg_match_all('/(?P<tag>#EXTINF:-1)|(?:(?P<prop_key>[-a-z]+)=\"(?P<prop_val>[^"]+)")|(?<something>,[^
	]+)|(?<url>http[^\s]+)/', $string, $match);
	$count = count($match[0]);
	$result = [];
	$index = -1;
	for($i = 0; $i < $count; $i++)
	{
		$item = $match[0][$i];
		if (!empty($match['tag'][$i]))
		{ 
			// is a tag increment the result index
			++$index;
		}elseif (!empty($match['prop_key'][$i]))
		{ 
			// is a prop - split item
			$result[$index][$match['prop_key'][$i]] = $match['prop_val'][$i];
		}elseif (!empty($match['something'][$i]))
		{ 
			// is a prop - split item
			$result[$index]['something'] = $item;
		}elseif (!empty($match['url'][$i]))
		{
			$result[$index]['url'] = $item ;
		}
	}
	return $result;
} 
// 对title进行格式化的函数
function formatTitle($title,$t=1)
{
	$text = strtoupper($title);
	if ($t==1)
		{if (preg_match("/CCTV/", $text))
		{
			preg_match_all("/^[A-Za-z0-9-+]+/", $text, $matches);
			$text = $matches[0][0];
		}
		if (strpos($text, "[") != false)
		{
			$text = substr($text, 0, strpos($text, "["));
		}
		$text = str_replace('-', '', $text);
		if (strlen($text) == 0) $text = strtoupper($title);
	}else{
		if (strpos($text, "[") != false)
		{
			$text = substr($text, 0, strpos($text, "["));
		}
	}
	return $text;
}
// 去重
function formatUrls($urlstr)
{
	$urls = array_filter(explode("#", $urlstr)); 
	$result = array_unique($urls);
	return implode("#", $result);
}
// 分析txt格式的字符串
function formattxt($string)
{ 
	// 将数据分割成行
	$lines = explode("\n", $string); 
	// 当前分类项目
	$currentItem = '';
	$result = [];
	foreach ($lines as $line)
	{
		$line = trim($line);
		if (empty($line)) continue; 
		if (strpos($line, '#genre#') !== false)
		{ 
			// 提取item
			list($currentItem,) = explode(',', $line);
			$currentItem = trim(str_replace('#genre#', '', $currentItem));
		}
		else
		{ 
			// 分割标题和URL
			if ($currentItem === '') $currentItem = '直播频道'; #如果没有分组
			list($title, $url) = explode(',', $line, 2);
			if (empty($url) or strtoupper(substr($url,0,4)) !='HTTP') continue; 
			$title = trim($title);
			$url = trim($url);
			$result[] = array('group-title' => $currentItem,
				'tvg-name' => '',
				'something' => ',' . trim($title),
				'url' => $url
				);
		}
	}
	return $result;
}

/**
* 获取随机useragent
*/
function get_rand_useragent() {
  $arr = array(
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 LBBROWSER',
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729;Media Center PC 6.0; .NET4.0C; .NET4.0E)',
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 SE 2.X MetaSr 1.0',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:16.0) Gecko/20121026 Firefox/16.0',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:2.0b13pre) Gecko/20110307 Firefox/4.0b13pre',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16',
            'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'
        );
    return $arr[array_rand($arr)];
}

//添加请求头
function FormatHeader($url,$useragent) {
	// 解析url
	$temp = parse_url($url);
	$query = isset($temp['query']) ? $temp['query'] : '';
	$path = isset($temp['path']) ? $temp['path'] : '/';
	$header = array (
		 "POST {$path}?{$query} HTTP/1.1",
		 "Host: {$temp['host']}",
		 "Referer: http://{$temp['host']}/",
		 "Content-Type: text/xml; charset=utf-8",
		 'Accept: application/json, text/javascript, */*; q=0.01',
		 'Accept-Encoding:gzip, deflate, br',
		 'Accept-Language:zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
		 'Connection:keep-alive',
		 'X-Requested-With: XMLHttpRequest',
		 'User-Agent: '.$useragent,
		 );
	return $header;
}
// 获取网页数据
function getContent($url)
{
	$useragent = get_rand_useragent();
	$header = FormatHeader($url,$useragent);
	$timeout= 120;
	$process = curl_init($url);
	curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($process, CURLOPT_HTTPHEADER, $header);
	curl_setopt($process, CURLOPT_HEADER, 0);
	curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 5); 
	curl_setopt($process, CURLOPT_TIMEOUT, 45);
	curl_setopt($process, CURLOPT_MAXREDIRS, 10 );
	// 设置启用SSL协议
	if (strtoupper(substr($url, 0, 5)) == 'HTTPS')
	{
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
	} 
	// 设置GZIP压缩
	$isgz = false;
	if (strtoupper(substr($url, -3)) == '.GZ')
	{
		curl_setopt($process, CURLOPT_ENCODING, 'gzip');
		$isgz = true;
	}
	curl_setopt($process, CURLOPT_USERAGENT, $useragent);
	$data = curl_exec($process);
	if (curl_errno($process))
	{
		echo curl_errno($process) . curl_error($process) . "<br>";
	}
	curl_close($process);
	if ($isgz)
	{
		$data = gzdecode($data);
	}
	unset($process);
	return $data;
}

// 处理按钮动作
if (isset($_POST['button1']))
{ 
	echo "<script>bt1();</script>";
	// Button 1被点击时执行的操作
	echo "<h3>转换文本内容如下：</h3>";
	if ($debug) echo "频道分组|频道名称|EPG名称|直播源网址<br>";
	$data = trim($_POST['m3uContent']); 
	// 先按m3u格式进行解析
	$result = formatm3u($data); 
	// 如果解析结果异常,再按txt格式解析
	if (count($result) <= 1)
	{
		$result = formattxt($data);
	} 
	if ($debug) echo "-- fetch data from Content" . "<br>";
	$currentItem = '';
	foreach ($result as $tv)
	{ 
		if (count($tv) === 1)
		{
			if ($debug) print($tv['x-tvg-url']) . "<br>";
		}elseif (count($tv) >= 3)
		{
			$urls = explode('#', $tv['url']);
			foreach ($urls as $url)
			{
				if (isset($db) && $merge) 
				{
					$db->exec("INSERT or replace INTO `tmp_list` VALUES ('". $tv['group-title'] ."','". trim(substr($tv['something'] , 1)) ."','". $tv['tvg-name'] ."','". $url ."','". $isdelCounter ."');");
				}else{
					// 不支持SQLite数据库时,直接打印结果
					if ($debug)
					{
						print($tv['group-title'] . "|" . substr($tv['something'] , 1) . "|" . $tv['tvg-name'] . "|" . $url . "|" . $isdelCounter . "<br>");
					}else if ($tosql=='sql')
					{
						print("INSERT or replace INTO `list` VALUES ('". $tv['group-title'] ."','". trim(substr($tv['something'] , 1)) ."','". $tv['tvg-name'] ."','". $url ."','". $isdelCounter ."');<br>");
					}else if ($tosql=='txt'){
						if ($currentItem != $tv['group-title'])
						{
							$currentItem = $tv['group-title'];
							print($tv['group-title'] . ",#genre#" . "<br>");
						}
						print(trim(substr($tv['something'] , 1)) . "," . $url . "<br>");
					}else if ($tosql=='m3u'){
						if ($isdelCounter ===1)
						{
							print("#EXTM3U x-tvg-url=" . '"http://127.0.0.1/diyp/epg.php"' . "<br>");
						}
						if (empty($tv['tvg-name']))
						{
							$tv['tvg-name'] = formatTitle(trim(substr($tv['something'] , 1)));
						}
						print('#EXTINF:-1 tvg-id="" tvg-logo="" tvg-name="' . $tv['tvg-name'] . '" group-title="' . $tv['group-title'] . '",' . trim(substr($tv['something'] , 1)) . "<br>");
						print($url . "<br>");
					}
				}
				$isdelCounter++;
			}
		}else{
			print_r($tv);
		}
	}

}
else if (isset($_POST['button2']))
{
	echo "<script>bt2();</script>";
	// Button 2被点击时执行的操作
	echo "<h3>解析网址内容如下：</h3>";
	if ($debug) echo "频道分组|频道名称|EPG名称|直播源网址<br>";
	$m3uContent = $_POST['m3uContent']; 
	$dataSources = explode("\n", $m3uContent);
	foreach ($dataSources as $dataSource)
	{ 
		// 获取数据
		$dataSource = str_replace("\r", "", $dataSource);
		$dataSource =trim($dataSource);
		if (empty($dataSource)) continue; 
		//$data = file_get_contents($dataSource);
		$data = getContent($dataSource);
		if ($data === false)
		{
			echo "Error: Unable to fetch m3u data from $dataSource.\n" . "<br>";
			continue;
		}
		else
		{ 
			// 先按m3u格式进行解析 echo htmlspecialchars($data);
			$result = formatm3u($data); 
			// 如果解析结果异常,再按txt格式解析
			if (count($result) <= 1)
			{
				$result = formattxt($data);
			} 
			if ($debug) echo "-- fetch data from $dataSource" . "<br>";
			$currentItem = '';
			foreach ($result as $tv)
			{ 
				if (count($tv) === 1)
				{
					if ($debug && !isset($db)) print($tv['x-tvg-url']) . "<br>";
				}elseif (count($tv) >= 3)
				{
					$urls = explode('#', $tv['url']);
					foreach ($urls as $url)
					{
						if (isset($db) && $merge) 
						{
							$db->exec("INSERT or replace INTO `tmp_list` VALUES ('". $tv['group-title'] ."','". trim(substr($tv['something'] , 1)) ."','". $tv['tvg-name'] ."','". $url ."','". $isdelCounter ."');");
						}else{
							// 不支持SQLite数据库时,直接打印结果
							if ($debug)
							{
								print($tv['group-title'] . "|" . substr($tv['something'] , 1) . "|" . $tv['tvg-name'] . "|" . $url . "|" . $isdelCounter . "<br>");
							}else if ($tosql=='sql')
							{
								print("INSERT or replace INTO `list` VALUES ('". $tv['group-title'] ."','". trim(substr($tv['something'] , 1)) ."','". $tv['tvg-name'] ."','". $url ."','". $isdelCounter ."');<br>");
							}else if ($tosql=='txt'){
								if ($currentItem != $tv['group-title'])
								{
									$currentItem = $tv['group-title'];
									print($tv['group-title'] . ",#genre#" . "<br>");
								}
								print(trim(substr($tv['something'] , 1)) . "," . $url . "<br>");
							}else if ($tosql=='m3u'){
								if ($isdelCounter ===1)
								{
									print("#EXTM3U x-tvg-url=" . '"http://127.0.0.1/diyp/epg.php"' . "<br>");
								}
								print('#EXTINF:-1 tvg-id="" tvg-logo="" tvg-name="' . $tv['tvg-name'] . '" group-title="' . $tv['group-title'] . '",' . trim(substr($tv['something'] , 1)) . "<br>");
								print($url . "<br>");
							}
						}
						$isdelCounter++;
					}
				}else{
					print_r($tv);
				}
			}

		}
	}
}
try {
	if (isset($db))
	{
		$count = $db->querySingle("SELECT count(*) FROM 'tmp_list'");
		if ($debug) 
		{
			echo " -- " . $count ;
		}elseif ($merge){
			$config = array();
			$result = $db->query("SELECT * from `tmp_list` where isdel > 0 order by isdel;");
			while ($row = $result->fetchArray())
			{
				$config[] = ['item' => $row[0], 'title' => sprintf("%s", trim($row[1])), 'epg' => sprintf("%s", maxstr($row[2])), 'url' => trim(sprintf("%s", $row[3])), 'isdel' => $row[4]];
			}
			// 合并分组
			$groupconfig = array_reduce($config, function($result, $item)
				{
					$gender = $item['item'];
					if (!isset($result[$gender]))
					{
						$result[$gender] = [];
					}
					$result[$gender][] = $item;
					return $result;
				}, []);
				
			// 合并频道url
			$config = array();
			foreach ($groupconfig as $item => $titles)
			{
				$channels = array_reduce($titles, function($result, $item)
					{
						$gender = $item['title'];
						if (!isset($result[$gender]))
						{
							$result[$gender] = array('title' => $gender,'url' => array(),'epg' => array(),'isdel' => array());
						}
						$result[$gender]['url'][] = $item['url'];
						$result[$gender]['epg'][] = $item['epg'];
						$result[$gender]['isdel'][] = $item['isdel'];
						return $result;
					}, []);
				$config[$item] = $channels;
			}
			$groupconfig = $config;
			unset($config);

			function gentxt($groupconfig,$optmerge)
			{
				$config = array();
				foreach ($groupconfig as $item => $titles)
				{
					$config[] = sprintf("%s,#genre#", $item);
					foreach ($titles as $k => $v)
					{					
						$epg = maxstr(implode('#',$v['epg']));
						$epg = $epg === '' ? formatTitle($v['title']) : $epg;
						$title =  formatTitle($v['title'],2);
						$url = formatUrls(implode('#',$v['url']));
						if ($optmerge === '2'){
							$config[] = $title . ',' . $url;
						}else{
							$urls = explode("#", $url);
							foreach($urls as $url)
							{
								$config[] = $title . ',' . $url;
							}
						}
					}
				}
				return $config;
			}
			function genm3u($groupconfig,$optmerge)
			{
				$config = array();
				$config[] = '#EXTM3U x-tvg-url="http://127.0.0.1/diyp/epg.php"';
				foreach ($groupconfig as $item => $titles)
				{
					foreach ($titles as $k => $v)
					{
						$epg = maxstr(implode('#',$v['epg']));
						$epg = $epg === '' ? formatTitle($v['title']) : $epg;
						$title =  formatTitle($v['title'],2);
						$url = formatUrls(implode('#',$v['url']));
						if ($optmerge === '2'){
							$config[] = sprintf('#EXTINF:-1 tvg-id="" tvg-name="%s" tvg-logo="" group-title="%s",%s', $epg, $item, $title);
							$config[] = $url;
						}else{
							$urls = explode("#", $url);
							foreach($urls as $url)
							{
								$config[] = sprintf('#EXTINF:-1 tvg-id="" tvg-name="%s" tvg-logo="" group-title="%s",%s', $epg, $item, $title);
								$config[] = $url;
							}
						}
					}
				}
				return $config;
			}

			function gensql($groupconfig,$optmerge)
			{
				$config = array();
				$isdelCounter = 120;
				foreach ($groupconfig as $item => $titles)
				{
					foreach ($titles as $k => $v)
					{
						$isdel = false ? min($v['isdel']) : $isdelCounter;
						$epg = maxstr(implode('#',$v['epg']));
						$epg = $epg === '' ? formatTitle($v['title']) : $epg;
						$title =  formatTitle($v['title'],2);
						$url = formatUrls(implode('#',$v['url']));
						if ($optmerge === '2'){
							$config[] = "INSERT or replace INTO `list` VALUES ('". $item ."','". $title ."','". $epg ."','". $url ."','". $isdel ."');";
						}else{
							$urls = explode("#", $url);
							foreach($urls as $url)
							{
								$config[] = "INSERT or replace INTO `list` VALUES ('". $item ."','". $title ."','". $epg ."','". $url ."','". $isdel ."');";
							}
						}
						$isdelCounter = $isdelCounter + 2;
					}
				}
				return $config;
			}
			// 按需输出
			if ($tosql=='sql'){
				$output = gensql($groupconfig,$optmerge);
			}else if ($tosql=='txt'){
				$output = gentxt($groupconfig,$optmerge);
			}else if ($tosql=='m3u'){
				$output = genm3u($groupconfig,$optmerge);
			}else{
				$output = [];
			}
			if (count($output)>0)
			{
				echo '' .
				'<div style="width:1024px">' .
				'<textarea id="outContent" name="outContent" rows="10" cols="100%" style="width:1024px">'. implode(PHP_EOL, $output) . '</textarea>' .
				'<button id="saveContent" name="saveContent" type="button" style="float:right" onClick="saveFile()">保存文件</button>' .
				'<input type="hidden" id="savetype" name="savetype" value="' . $tosql . '">' .
				'</div>';
			}
		}
	}
	$db = null;
	unset($db);
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
} 
if ($debug) echo ' * total cost ' . number_format(microtime(true) - $start, 4) . ' s.';
?>
<br><br>m3u输入样例<br>
<textarea name="insample" rows="5" cols="100%" style="width:1024px"  class="tex">
#EXTM3U x-tvg-url="http://127.0.0.1/diyp/epg.php"
#EXTINF:-1 tvg-id="" tvg-name="" tvg-logo="" group-title="广东频道",广州综合
http://nas.jdshipin.com:8801/gztv.php?id=zhonghe
#EXTINF:-1 tvg-id="" tvg-name="" tvg-logo="" group-title="广东频道",广州新闻
http://nas.jdshipin.com:8801/gztv.php?id=xinwen#http://113.100.193.10:9901/tsfile/live/1000_1.m3u8
#EXTINF:-1 tvg-id="" tvg-name="" tvg-logo="" group-title="直播频道",CCTV2
http://dbiptv.sn.chinamobile.com/3221226195/index.m3u8?0.smil#http://dbiptv2.sn.chinamobile.com/3221226195/index.m3u8?0.smil</textarea>
<br><br>txt输入样例<br>
<textarea name="insample" rows="5" cols="100%" style="width:1024px"  class="tex">
广东频道,#genre#
广州综合,http://nas.jdshipin.com:8801/gztv.php?id=zhonghe
广州新闻,http://nas.jdshipin.com:8801/gztv.php?id=xinwen#http://113.100.193.10:9901/tsfile/live/1000_1.m3u8
直播频道,#genre#
CCTV2,http://dbiptv.sn.chinamobile.com/3221226195/index.m3u8?0.smil#http://dbiptv2.sn.chinamobile.com/3221226195/index.m3u8?0.smil</textarea>
<br><br>URL输入样例<br>
<textarea name="insample" rows="3" cols="100%" style="width:1024px" class="tex">
http://127.0.0.1/diyp/live.php?t=m3u
http://127.0.0.1/diyp/live.php?t=txt</textarea>
</body>
</html>
