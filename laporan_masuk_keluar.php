<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_koneksi, $koneksi);
$query_rsMasukKeluar = "SELECT * FROM tb_parkir ORDER BY masuk DESC";
$rsMasukKeluar = mysql_query($query_rsMasukKeluar, $koneksi) or die(mysql_error());
$row_rsMasukKeluar = mysql_fetch_assoc($rsMasukKeluar);
$totalRows_rsMasukKeluar = mysql_num_rows($rsMasukKeluar);
?>
<p>LAPORAN DATA MASUK DAN KELUAR</p>
<p>&nbsp;</p>
<?php
mysql_free_result($rsMasukKeluar);
?>
