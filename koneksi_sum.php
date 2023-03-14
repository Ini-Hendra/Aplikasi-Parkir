<?php
error_reporting(E_ALL ^ E_DEPRECATED);
global $conn;
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbdatabase = "db_big_parking";

$conn = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbdatabase);
if(!$conn){
	die("Koneksi Error ( Koneksi Sum )");
	}
?>