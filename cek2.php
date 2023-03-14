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

$maxRows_rsTampil = 10;
$pageNum_rsTampil = 0;
if (isset($_GET['pageNum_rsTampil'])) {
  $pageNum_rsTampil = $_GET['pageNum_rsTampil'];
}
$startRow_rsTampil = $pageNum_rsTampil * $maxRows_rsTampil;

$colname_rsTampil = "-1";
if (isset($_GET['id_parkir'])) {
  $colname_rsTampil = $_GET['id_parkir'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsTampil = sprintf("SELECT * FROM tb_parkir WHERE id_parkir = %s", GetSQLValueString($colname_rsTampil, "text"));
$query_limit_rsTampil = sprintf("%s LIMIT %d, %d", $query_rsTampil, $startRow_rsTampil, $maxRows_rsTampil);
$rsTampil = mysql_query($query_limit_rsTampil, $koneksi) or die(mysql_error());
$row_rsTampil = mysql_fetch_assoc($rsTampil);

if (isset($_GET['totalRows_rsTampil'])) {
  $totalRows_rsTampil = $_GET['totalRows_rsTampil'];
} else {
  $all_rsTampil = mysql_query($query_rsTampil);
  $totalRows_rsTampil = mysql_num_rows($all_rsTampil);
}
$totalPages_rsTampil = ceil($totalRows_rsTampil/$maxRows_rsTampil)-1;
?>
<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
<!DOCTYPE html>
<html>
<head>
<title>CETAK STRUK</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="BIG Parking By Muhamad Hendra" />
<link rel="icon" href="assets/fav.png" type="image/x-icon">
<link rel="stylesheet" href="assets/w3.css">
<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
<script src="assets/jquery-3.4.1.min.js"></script>
<script src="assets/w3.js"></script>
</head>
<body onLoad="cetak()" oncontextmenu="return false" onselectstart="return false">
<center>Sedang Mencetak</center>
<?php do { ?>
<a style="display:none" id="cetakKeluar" href="parkir_cetak_keluar.php?id_parkir=<?php echo $row_rsTampil['id_parkir']; ?>" 
  target="popup" 
  onclick="window.open('parkir_cetak_keluar.php?id_parkir=<?php echo $row_rsTampil['id_parkir']; ?>','popup','width=300,height=400'); return false;">
    Cetak Struk Keluar
</a>
<?php } while ($row_rsTampil = mysql_fetch_assoc($rsTampil)); ?>
<script>
function cetak(){
document.getElementById('cetakKeluar').click();
setInterval(function(){window.close()},2000);
}
</script>
</body>
</html>
<?php
mysql_free_result($rsTampil);
?>
