<meta charset="utf-8">
<title>消耗流量统计</title>
<h3>消耗流量 TOP 25</h3>
<?php 
define('ANDYPHP_DIR', substr($_SERVER['DOCUMENT_ROOT'], 0, -23));
define('LOG_DIR', ANDYPHP_DIR . 'apache/logs/');

$log_file  = LOG_DIR . 'access_' .date('Ymd', strtotime("yesterday")) . ".log";   
$list = array();

$log = @file_get_contents($log_file);
$count = 0;

foreach (explode("\n", $log) as $key => $value) {
	$temp_url = $temp_byte = 0;
	$tmp2 = explode(' ', $value);
	$temp_url = from($tmp2, 0);
	
	if (from($tmp2, 1) != "-") $temp_url .= from($tmp2, 1);
	if (!$temp_url) continue;
	$temp_byte = intval(from($tmp2, 2));
	if (isset($list[$temp_url])) {
		$list[$temp_url] += $temp_byte;
	} else {
		$list[$temp_url] = $temp_byte;
	}
	$count += $temp_byte;
}
echo "<b>昨日总消耗 " . sprintf("%.2f", $count / 1024 / 1024) . "M</b> <br /> <br />";
arsort($list);
$i = 0;
echo '<table border="0">';
foreach ($list as $key => $value) {
	$i ++;
	$value = sprintf("%.2f", $value / 1024 / 1024);   
	$value .= "M";
	echo "<tr><th><a href='http://{$key}' target='_blank'>{$key}</a></th><th>{$value}</th></tr>";
	if ($i == 25) break;
}
echo '</table>';

$count = 0;
$list = array();
foreach (explode("\n", $log) as $key => $value) {
	$temp_url = $temp_byte = 0;
	$tmp2 = explode(' ', $value);
	$temp_url = from($tmp2, 0);
	
	if (from($tmp2, 1) != "-") $temp_url .= from($tmp2, 1);
	if (!$temp_url) continue;
	$temp_byte = intval(from($tmp2, 2));
	if (isset($list[$temp_url])) {
		$list[$temp_url] ++;
	} else {
		$list[$temp_url] = 1;
	}
	$count ++;
}
echo "<br /><br /><b>总访问 " . number_format($count) . " 次</b> <br /> <br />";
arsort($list);
$i = 0;
echo '<table border="0">';
foreach ($list as $key => $value) {
	$i ++;
	$value = number_format($value);   
	echo "<tr><th><a href='http://{$key}' target='_blank'>{$key}</a></th><th>{$value}次</th></tr>";
	if ($i == 25) break;
}
echo '</table>';


$log_file  = LOG_DIR . 'access_' .date('Ymd') . ".log";   
$list = array();

$log = @file_get_contents($log_file);
$count = 0;

foreach (explode("\n", $log) as $key => $value) {
	$temp_url = $temp_byte = 0;
	$tmp2 = explode(' ', $value);
	$temp_url = from($tmp2, 0);
	
	if (from($tmp2, 1) != "-") $temp_url .= from($tmp2, 1);
	if (!$temp_url) continue;
	$temp_byte = intval(from($tmp2, 2));
	if (isset($list[$temp_url])) {
		$list[$temp_url] += $temp_byte;
	} else {
		$list[$temp_url] = $temp_byte;
	}
	$count += $temp_byte;
}
echo "<br /><br /><b>今日总消耗 " . sprintf("%.2f", $count / 1024 / 1024) . "M</b> <br /> <br />";
arsort($list);
$i = 0;
echo '<table border="0">';
foreach ($list as $key => $value) {
	$i ++;
	$value = sprintf("%.2f", $value / 1024 / 1024);   
	$value .= "M";
	echo "<tr><th><a href='http://{$key}' target='_blank'>{$key}</a></th><th>{$value}</th></tr>";
	if ($i == 25) break;
}
echo '</table>';

$count = 0;
$list = array();
foreach (explode("\n", $log) as $key => $value) {
	$temp_url = $temp_byte = 0;
	$tmp2 = explode(' ', $value);
	$temp_url = from($tmp2, 0);
	
	if (from($tmp2, 1) != "-") $temp_url .= from($tmp2, 1);
	if (!$temp_url) continue;
	$temp_byte = intval(from($tmp2, 2));
	if (isset($list[$temp_url])) {
		$list[$temp_url] ++;
	} else {
		$list[$temp_url] = 1;
	}
	$count ++;
}
echo "<br /><br /><b>总访问 " . number_format($count) . " 次</b> <br /> <br />";
arsort($list);
$i = 0;
echo '<table border="0">';
foreach ($list as $key => $value) {
	$i ++;
	$value = number_format($value);   
	echo "<tr><th><a href='http://{$key}' target='_blank'>{$key}</a></th><th>{$value}次</th></tr>";
	if ($i == 25) break;
}
echo '</table>';


function from($array, $key, $default = FALSE)
{
    $return = $default;
    if (is_object($array)) $return = (isset($array->$key) === TRUE && empty($array->$key) === FALSE) ? $array->$key : $default;
    if (is_array($array))  $return = (isset($array[$key]) === TRUE && empty($array[$key]) === FALSE) ? $array[$key] : $default;

    return $return;
}