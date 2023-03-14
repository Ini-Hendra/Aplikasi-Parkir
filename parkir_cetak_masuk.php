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

$maxRows_rsCetakMasuk = 1;
$pageNum_rsCetakMasuk = 0;
if (isset($_GET['pageNum_rsCetakMasuk'])) {
  $pageNum_rsCetakMasuk = $_GET['pageNum_rsCetakMasuk'];
}
$startRow_rsCetakMasuk = $pageNum_rsCetakMasuk * $maxRows_rsCetakMasuk;

mysql_select_db($database_koneksi, $koneksi);
$query_rsCetakMasuk = "SELECT * FROM tb_parkir WHERE status='Masuk' ORDER BY masuk DESC";
$query_limit_rsCetakMasuk = sprintf("%s LIMIT %d, %d", $query_rsCetakMasuk, $startRow_rsCetakMasuk, $maxRows_rsCetakMasuk);
$rsCetakMasuk = mysql_query($query_limit_rsCetakMasuk, $koneksi) or die(mysql_error());
$row_rsCetakMasuk = mysql_fetch_assoc($rsCetakMasuk);

if (isset($_GET['totalRows_rsCetakMasuk'])) {
  $totalRows_rsCetakMasuk = $_GET['totalRows_rsCetakMasuk'];
} else {
  $all_rsCetakMasuk = mysql_query($query_rsCetakMasuk);
  $totalRows_rsCetakMasuk = mysql_num_rows($all_rsCetakMasuk);
}
$totalPages_rsCetakMasuk = ceil($totalRows_rsCetakMasuk/$maxRows_rsCetakMasuk)-1;
?>
<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
<!DOCTYPE html>
<html>
<head>
<title>CETAK STRUK MASUK</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="BIG Parking By Muhamad Hendra" />
<link rel="icon" href="assets/fav.png" type="image/x-icon">
<link rel="stylesheet" href="assets/w3.css">
<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
<script src="assets/jquery-3.4.1.min.js"></script>
<script src="assets/w3.js"></script>
<style>@page { margin: 20px; }</style>
</head>
<body onLoad="<?php if ($totalRows_rsCetakMasuk == 0) { echo "keluar()"; } else { echo "cetak()"; } ?>" oncontextmenu="return false" onselectstart="return false">
<?php if ($totalRows_rsCetakMasuk > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php    
	$iniID = $row_rsCetakMasuk['id_parkir'];
	$isi = $iniID;
    include "qrlib.php"; 
	$penyimpanan = "temp/";
	
	if (!file_exists($penyimpanan))
	mkdir($penyimpanan);
	
	QRcode::png($isi, $penyimpanan.$iniID.'.png', QR_ECLEVEL_L, 10, 1);
?>
<?php
	$year = substr($row_rsCetakMasuk['masuk'],0,4);
	$mont = substr($row_rsCetakMasuk['masuk'],5,2);
	$day = substr($row_rsCetakMasuk['masuk'],8,2);
	$jam = substr($row_rsCetakMasuk['masuk'],11,8);
?> 
<div class="w3-container">
	<div class="w3-row">
    	<div class="w3-col s12">
        	<div class="w3-center"><img src="assets/weblogo.png" width="127" height="26"></div>
            <div class="w3-center" style="font-size:10px">PT. Batavia Indo Global</div>

            <div style="font-size:9px;margin-top:10px;">No. Polisi<span class="w3-right"><b><?php echo $row_rsCetakMasuk['no_polisi']; ?></b></span></div>
            <div style="font-size:9px">Jenis<span class="w3-right"><?php echo $row_rsCetakMasuk['jenis_kendaraan']; ?></span></div>
            <div style="font-size:9px">Tanggal<span class="w3-right"><?php echo $day."-".$mont."-".$year; ?></span></div>
            <div style="font-size:9px">Jam<span class="w3-right"><?php echo $jam; ?></span></div>
            
            
            <div class="w3-center" style="margin-top:10px;">
            <div><img src="<?php echo $penyimpanan.$iniID.'.png' ?>" width="95" height="95" class="w3-image"></div>
            <div style="font-size:20px"><b><?php echo $iniID ?></b></div>
            <div style="font-size:8px"><i>Jangan Meninggalkan Tiket<br>
Serta Barang Berharga Anda</i></div>
            </div>
            
           
        </div>
    </div>	
</div>
<?php } while ($row_rsCetakMasuk = mysql_fetch_assoc($rsCetakMasuk)); ?>
<?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsCetakMasuk);
?>
<?php if ($totalRows_rsCetakMasuk == 0) { // Show if recordset empty ?>
  <center>TIKET TIDAK TERSEDIA<br>
MOHON INPUT DENGAN BENAR</center>
<?php } // Show if recordset empty ?>
<script>
function cetak(){
setInterval(function(){window.print()},3000);
setInterval(function(){window.close()},5000);
}

function keluar(){
setInterval(function(){window.close()},1000);
}
</script>
</body>
</html>