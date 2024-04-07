<?php
/**
 * http://PHPnow.org
 * YinzCN_at_Gmail.com
 */

error_reporting(E_ALL);

define('TimeZone', + 8.0);

function _GET($n)
{
	return isset($_GET[$n]) ? $_GET[$n] : null;
}
function _SERVER($n)
{
	return isset($_SERVER[$n]) ? $_SERVER[$n] : '[undefine]';
}

if (_GET('act') == 'phpinfo')
{
	if (function_exists('phpinfo')) phpinfo();
	else echo 'phpinfo() has been disabled.';
	exit;
}

$Info = array();
$Info['php_ini_file'] = function_exists('php_ini_loaded_file') ? php_ini_loaded_file() : '[undefine]';

if (_GET('act') == 'getip')
{
	$i = _SERVER('SERVER_NAME') . '|' . _SERVER('REMOTE_ADDR') . '|' . _SERVER('SERVER_SOFTWARE') . '|' . (function_exists('mysql_close')?mysql_get_client_info():'') . '|' . _SERVER('DOCUMENT_ROOT');
	$c = @file_get_contents('http://phpnow.org/myip.php?' . base64_encode($i));
	if (preg_match('/^\d+\.\d+\.\d+\.\d+$/', $c) == 1) echo $c;
	else echo 'false';
	exit;
}

function colorhost()
{
	$c = array('#87cefa', '#ffa500', '#ff6347', '#9acd32', '#32cd32', '#ee82ee');
	$a = str_split(_SERVER('SERVER_NAME'));
	$k = $l = 0;
	foreach ($a as &$d)
	{
		while ($k == $l) $k = array_rand($c);
		$d = '<b style="color: ' . $c[$k] . ';">' . $d . '</b>';
		$l = $k;
	}
	return implode('', $a);
}

function get_ea_info($name)
{
	$ea_info = eaccelerator_info();
	return $ea_info[$name];
}
function get_gd_info($name)
{
	$gd_info = gd_info();
	return $gd_info[$name];
}

define('YES', '<span style="color: #008000; font-weight : bold;">Yes</span>');
define('NO', '<span style="color: #ff0000; font-weight : bold;">No</span>');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="YinzCN" />
<meta name="reply-to" content="YinzCN@Gmail.com" />
<meta name="copyright" content="YinzCN" />
<title>PHPnow Works!</title>
<style type="text/css">
<!--
body {
  font-family: verdana, tahoma;
  font-size: 12px;
  margin-top: 10px;
}

form {
  margin: 0;
}

table {
  border-collapse: collapse;
}

.info tr td {
  border: 1px solid #000000;
  padding: 3px 10px 3px 10px;
}

.info th {
  border: 1px solid #000000;
  font-weight: bold;
  height: 16px;
  padding: 3px 10px 3px 10px;
  background-color: #9acd32;
}

input {
  border: 1px solid #000000;
  background-color: #fafafa;
}

a {
  text-decoration: none;
  color: #000000;
}

a:hover {
  text-decoration: underline;
}

a.arrow {
  font-family: webdings, sans-serif;
  font-size: 10px;
}

a.arrow:hover {
  color: #ff0000;
  text-decoration: none;
}

.item {
  white-space: nowrap;
  text-align: right;
}

hr {
  margin: 10px auto;
}
-->
</style>
<script type="text/JavaScript">
function $(id) { return document.getElementById(id); }

function get_ip() {
  var xhr, r;
  xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      r = xhr.responseText;
      if (r == 'false') $('ip_r').innerHTML = '获取外网 IP 失败!';
      else $('ip_r').innerHTML = '此服务器互联网 IP<br /><a href="http://' + r + '" style="color: #999999;">' + r + '</a>';
    }
  }
  xhr.open("GET", "?act=getip", true);
  xhr.send();
}
</script>
</head>
<body onload="get_ip();">
<div style="margin: 0 auto; width: 600px;">

<div style="height: 60px;">
  <div style="float: right; margin: 5px; text-align: center;">
   <div><a style="color: #ffa500;" href="http://phpnow.org/go.php?id=1005">为何只能本地访问?</a></div>
   <div id="ip_r" style="color: #999999;">正在获取 IP 地址</div>
  </div>

  <div style="float: left;">
   <div style="font-weight: bold; font-size: 2.2em;"><a href="<?php echo _SERVER('PHP_SELF');

?>?" style="text-decoration: none;"><?php echo colorhost();

?></a></div>
   <div style="margin: 5px auto;"># Let's <b style="color: #777BB4;">PHP</b> <b style="color: #FF4500;">now</b> <b>!</b></div>
  </div>
</div>

<br />

<table width="100%" class="info">
  <tr>
    <th colspan="2">Server Information</th>
  </tr>

  <tr>
    <td class="item">SERVER_NAME</td>
    <td><?php echo _SERVER('SERVER_NAME');

?></td>
  </tr>

  <tr>
    <td class="item">SERVER_ADDR:PORT</td>
    <td><?php echo _SERVER('SERVER_ADDR') . ':' . _SERVER('SERVER_PORT');

?></td>
  </tr>

  <tr>
    <td class="item">SERVER_SOFTWARE</td>
    <td><?php echo stripos(_SERVER('SERVER_SOFTWARE'), 'PHP')?_SERVER('SERVER_SOFTWARE'):_SERVER('SERVER_SOFTWARE') . ' PHP/' . PHP_VERSION;

?></td>
  </tr>

  <tr>
    <td class="item">PHP_SAPI</td>
    <td><?php echo PHP_SAPI;

?></td>
  </tr>

  <tr>
    <td class="item" style="color: #ff0000;">php.ini</td>
    <td><?php echo $Info['php_ini_file'];

?></td>
  </tr>

  <tr>
    <td class="item">网站主目录</td>
    <td><?php echo _SERVER('DOCUMENT_ROOT');

?></td>
  </tr>

  <tr>
    <td class="item">Server Date / Time</td>
    <td><?php echo gmdate('Y-m-d', time() + TimeZone * 3600);

?> <?php echo gmdate('H:i:s', time() + TimeZone * 3600);

?> <span style="color: #999999;">(<?php echo (TimeZone < 0?'-':'+') . gmdate('H:i', abs(TimeZone) * 3600);

?>)</span></td>
  </tr>

  <tr>
    <td class="item">Other Links</td>
    <td>
    <a href='<?php echo _SERVER('PHP_SELF');

?>?act=phpinfo'>phpinfo()</a>
    | <?php echo file_exists('phpMyAdmin') ? '<a href="/phpMyAdmin">phpMyAdmin</a>' : '';

?>
    </td>
  </tr>
</table>

<hr />

<table width="100%" class="info">
  <tr>
    <th colspan="2">PHP 组件支持必须全部为YES</th>
  </tr>
<?php
$classPDO = class_exists("PDO");
$classSQLite3 = class_exists("SQLite3"); 
// PDO is there, check if the SQLite driver for PDO is missing
if ($classPDO)
{
	$PDOSqliteDriver = (in_array("sqlite", PDO::getAvailableDrivers()));
}
else
{
	$PDOSqliteDriver = false;
}

?>

  <tr>
    <td class="item">Curl 支持</td>
    <td><?php echo function_exists('curl_init') ? YES . ' / ' : NO;

?></td>
  </tr>

  <tr>
    <td class="item">soap 支持</td>
    <td><?php echo function_exists('is_soap_fault') ? YES . ' / '  : NO;

?></td>
  </tr>

  <tr>
    <td class="item">gettext 支持</td>
    <td><?php echo function_exists('gettext') ? YES . ' / ' : NO;

?></td>
  </tr>

  <tr>
    <td class="item">SQLite3 支持</td>
    <td><?php echo $classSQLite3 ? YES . ' / ' . SQLite3::version()['versionString'] : NO;

?></td>
  </tr>

  <tr>
    <td class="item">PDO SQLite 支持</td>
    <td><?php echo $PDOSqliteDriver ? YES . ' / ' . 'PDO' : NO;

?></td>
  </tr>

  <tr>
    <td class="item">openssl 支持</td>
    <td><?php echo function_exists('openssl_open') ? YES . ' / ' . OPENSSL_VERSION_TEXT : NO;

?></td>
  </tr>

  <tr>
    <td class="item">SimpleXML 支持</td>
    <td><?php echo function_exists('simplexml_load_string') ? YES . ' / ' : NO;

?></td>
  </tr>
</table>

<hr />
<table width="100%" class="info">
  <tr>
    <th colspan="4">PHP已编译模块检测</th>
  </tr>
  <tr>
    <td colspan="4"><span class="w_small">
	<?php
		$able = get_loaded_extensions();
		foreach ($able as $key => $value)
		{
			if ($key != 0 && $key % 10 == 0)
			{
				echo '<br />';
			}
			echo "$value&nbsp;&nbsp;";
		}

	?>
	</span>
    </td>
  </tr>
</table>
<hr />

</body>
</html>