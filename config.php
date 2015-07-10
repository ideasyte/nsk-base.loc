<?
error_reporting(E_ERROR); // E_WARNING | E_PARSE
$dbpnt = @mysql_connect("localhost", "h65041_nsk_syte", "evuges80");
$mysql_err = mysql_error();
if (!$dbpnt) die("Сервер базы данных перегружен. Приносим извинения. Попробуйте повторить попытку позже.<br />" . $mysql_err);
mysql_select_db("nsk-base", $dbpnt);
mysql_query("SET NAMES 'UTF8'");
mysql_set_charset("utf8");
$ip = $_SERVER['REMOTE_ADDR'];
$self = $_SERVER['PHP_SELF'];
$self = substr($self,1,100);

define('MC_ROOT', dirname(__FILE__));
$subdomain = '';

mysql_query ("set character_set_results='utf8'");
mysql_query ("set character_set_client='utf8'");
mysql_query ("set collation_connection='utf8_general_ci'");

$test_inc="connection OK";
header("Content-Type: text/html; charset=UTF-8");
?>