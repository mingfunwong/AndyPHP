<?php
$username = 'admin';
$password = 'admin';
switch (true) {
    case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
    case $_SERVER['PHP_AUTH_USER'] !== $username:
    case $_SERVER['PHP_AUTH_PW']   !== $password:
        header('WWW-Authenticate: Basic realm="Enter username and password."');
        header('Content-Type: text/plain; charset=utf-8');
        die('Enter username and password.');
}
ini_set('max_execution_time', '0');
ini_set('memory_limit','10240M');
$host = 'localhost';
$user = 'root';
$passwd = '';
$con = @mysql_connect($host, $user, $passwd);
if (!$con){die('Could not connect: ' . mysql_connect_error());}
$result = mysql_query('show databases');
$data = array();
$path = "./backup_mysql/" . date("Y-m-d_H-i-s") . "/";
mk_dir($path);
while ($row = mysql_fetch_assoc($result)) {
    $dbname = $row['Database'];
    db_dump($host,$user,$passwd,$dbname,$path.$dbname.".sql");
}
echo "Backedup data successfully.";

function mk_dir($dir, $mode = 0755)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) {
        return true;
    }
    if (!mk_dir(dirname($dir), $mode)) {
        return false;
    }
    return @mkdir($dir, $mode);
}

function db_dump($host,$user,$pwd,$db,$file) {
    $mysqlconlink = @mysql_connect($host,$user,$pwd , true);
    if (!$mysqlconlink)
        echo sprintf('No MySQL connection: %s',mysql_error())."<br/>";
    mysql_set_charset( 'utf8', $mysqlconlink );
    $mysqldblink = mysql_select_db($db,$mysqlconlink);
    if (!$mysqldblink)
        echo sprintf('No MySQL connection to database: %s',mysql_error())."<br/>";
    $tabelstobackup=array();
    $result=mysql_query("SHOW TABLES FROM `$db`");
    if (!$result)
        echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW TABLE STATUS FROM `$db`;")."<br/>";
    while ($data = mysql_fetch_row($result)) {
            $tabelstobackup[]=$data[0];
    }
    $result=mysql_query("SHOW TABLE STATUS FROM `$db`");
    if (!$result)
        echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW TABLE STATUS FROM `$db`;")."<br/>";
    while ($data = mysql_fetch_assoc($result)) {
        $status[$data['Name']]=$data;
    }
    if ($file = fopen($file, 'wb')) {
        fwrite($file, "-- ---------------------------------------------------------\n");
        fwrite($file, "-- Database Name: $db\n");
        fwrite($file, "-- ---------------------------------------------------------\n\n");
        fwrite($file, "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n");
        fwrite($file, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n");
        fwrite($file, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n");
        fwrite($file, "/*!40101 SET NAMES '".mysql_client_encoding()."' */;\n");
        fwrite($file, "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n");
        fwrite($file, "/*!40103 SET TIME_ZONE='".mysql_result(mysql_query("SELECT @@time_zone"),0)."' */;\n");
        fwrite($file, "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n");
        fwrite($file, "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n");
        fwrite($file, "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n");
        fwrite($file, "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n");
        foreach($tabelstobackup as $table) {
            _db_dump_table($table,$status[$table],$file);
        }
        fwrite($file, "\n");
        fwrite($file, "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n");
        fwrite($file, "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n");
        fwrite($file, "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n");
        fwrite($file, "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n");
        fwrite($file, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n");
        fwrite($file, "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n");
        fwrite($file, "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n");
        fwrite($file, "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n");
        fclose($file);
        echo date("[Y-md H:i:s] ") . $db . ' Database dump done!'."<br/>";
    } else {
        echo date("[Y-md H:i:s] ") . $db . ' Can not create database dump!'."<br/>";
    }

}
function _db_dump_table($table,$status,$file) {
    fwrite($file, "\n");
    fwrite($file, "--\n");
    fwrite($file, "-- Table structure for table $table\n");
    fwrite($file, "--\n\n");
    fwrite($file, "DROP TABLE IF EXISTS `" . $table .  "`;\n");
    fwrite($file, "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n");
    fwrite($file, "/*!40101 SET character_set_client = '".mysql_client_encoding()."' */;\n");
    $result=mysql_query("SHOW CREATE TABLE `".$table."`");
    if (!$result) {
        echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW CREATE TABLE `".$table."`")."<br/>";
        return false;
    }
    $tablestruc=mysql_fetch_assoc($result);
    fwrite($file, $tablestruc['Create Table'].";\n");
    fwrite($file, "/*!40101 SET character_set_client = @saved_cs_client */;\n");
    $result=mysql_query("SELECT * FROM `".$table."`");
    if (!$result) {
        echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SELECT * FROM `".$table."`")."<br/>";
        return false;
    }
    fwrite($file, "--\n");
    fwrite($file, "-- Dumping data for table $table\n");
    fwrite($file, "--\n\n");
    if ($status['Engine']=='MyISAM')
        fwrite($file, "/*!40000 ALTER TABLE `".$table."` DISABLE KEYS */;\n");
    while ($data = mysql_fetch_assoc($result)) {
        $keys = array();
        $values = array();
        foreach($data as $key => $value) {
            if($value === NULL)
                $value = "NULL";
            elseif($value === "" or $value === false)
                $value = "''";
            elseif(!is_numeric($value))
                $value = "'".mysql_real_escape_string($value)."'";
            $values[] = $value;
        }
        fwrite($file, "INSERT INTO `".$table."` VALUES ( ".implode(", ",$values)." );\n");
    }
    if ($status['Engine']=='MyISAM')
        fwrite($file, "/*!40000 ALTER TABLE ".$table." ENABLE KEYS */;\n");
}

