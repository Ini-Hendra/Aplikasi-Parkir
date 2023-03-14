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

$maxRows_rsCetakKeluar = 10;
$pageNum_rsCetakKeluar = 0;
if (isset($_GET['pageNum_rsCetakKeluar'])) {
  $pageNum_rsCetakKeluar = $_GET['pageNum_rsCetakKeluar'];
}
$startRow_rsCetakKeluar = $pageNum_rsCetakKeluar * $maxRows_rsCetakKeluar;

$colname_rsCetakKeluar = "-1";
if (isset($_GET['id_parkir'])) {
  $colname_rsCetakKeluar = $_GET['id_parkir'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsCetakKeluar = sprintf("SELECT * FROM tb_parkir WHERE id_parkir = %s", GetSQLValueString($colname_rsCetakKeluar, "text"));
$query_limit_rsCetakKeluar = sprintf("%s LIMIT %d, %d", $query_rsCetakKeluar, $startRow_rsCetakKeluar, $maxRows_rsCetakKeluar);
$rsCetakKeluar = mysql_query($query_limit_rsCetakKeluar, $koneksi) or die(mysql_error());
$row_rsCetakKeluar = mysql_fetch_assoc($rsCetakKeluar);

if (isset($_GET['totalRows_rsCetakKeluar'])) {
  $totalRows_rsCetakKeluar = $_GET['totalRows_rsCetakKeluar'];
} else {
  $all_rsCetakKeluar = mysql_query($query_rsCetakKeluar);
  $totalRows_rsCetakKeluar = mysql_num_rows($all_rsCetakKeluar);
}
$totalPages_rsCetakKeluar = ceil($totalRows_rsCetakKeluar/$maxRows_rsCetakKeluar)-1;
?>
<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
<!DOCTYPE html>
<html>
<head>
<title>CETAK STRUK KELUAR</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="BIG Parking By Muhamad Hendra" />
<link rel="icon" href="assets/fav.png" type="image/x-icon">
<link rel="stylesheet" href="assets/w3.css">
<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
<script src="assets/jquery-3.4.1.min.js"></script>
<script src="assets/w3.js"></script>
<style>@page { margin: 20px; }</style>
</head>
<body onLoad="cetak()" oncontextmenu="return false" onselectstart="return false">
<?php if ($totalRows_rsCetakKeluar > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php 
	$jk = $row_rsCetakKeluar['jenis_kendaraan'] ; 
	$iniID = $row_rsCetakKeluar['id_parkir'];
	$isi = $iniID;
    include "qrlib.php"; 
	$penyimpanan = "temp/";
	
	if (!file_exists($penyimpanan))
	mkdir($penyimpanan);
	
	QRcode::png($isi, $penyimpanan.$iniID.'.png', QR_ECLEVEL_L, 10, 1);
?>
<?php
	$year = substr($row_rsCetakKeluar['masuk'],0,4);
	$mont = substr($row_rsCetakKeluar['masuk'],5,2);
	$day = substr($row_rsCetakKeluar['masuk'],8,2);
	$jam = substr($row_rsCetakKeluar['masuk'],11,8);
	
	$year2 = substr($row_rsCetakKeluar['keluar'],0,4);
	$mont2 = substr($row_rsCetakKeluar['keluar'],5,2);
	$day2 = substr($row_rsCetakKeluar['keluar'],8,2);
	$jam2 = substr($row_rsCetakKeluar['keluar'],11,8);
?> 
<div class="w3-container">
	<div class="w3-row">
    	<div class="w3-col s12">
        	<div class="w3-center"><img src="assets/weblogo.png" width="127" height="26"></div>
            <div class="w3-center" style="font-size:10px">PT. Batavia Indo Global</div>

            <div style="font-size:9px;margin-top:10px;">No. Tiket<span class="w3-right"><b><?php echo $row_rsCetakKeluar['id_parkir']; ?></b></span></div>
            <div style="font-size:9px;">No. Polisi<span class="w3-right"><b><?php echo $row_rsCetakKeluar['no_polisi']; ?></b></span></div>
            <div style="font-size:9px">Jenis<span class="w3-right"><?php echo $row_rsCetakKeluar['jenis_kendaraan']; ?></span></div>
            <div style="font-size:9px">In<span class="w3-right"><?php echo $day."-".$mont."-".$year." ".$jam; ?></span></div>
            <div style="font-size:9px">Out<span class="w3-right"><?php echo $day2."-".$mont2."-".$year2." ".$jam2; ?></span></div>
            <div style="font-size:9px">Durasi<span class="w3-right"><?php echo $row_rsCetakKeluar['durasi']; ?></span></div>
            
            
            
            
            <div class="w3-center w3-padding-small w3-border-top w3-border-bottom" style="font-size:9px;margin-top:8px;">
            <div>Biaya Parkir</div>
            <div style="font-size:16px"><b>Rp. <?php echo number_format($row_rsCetakKeluar['biaya'],0,",","."); ?></b></div>
            </div>
            <?php
			$dataBiaya = mysql_query("SELECT * FROM tb_biaya_parkir WHERE jenis_kendaraan='$jk'");
		if($dataBiaya === FALSE){
			die(mysql_error());
		} while($hasil_dataBiaya = mysql_fetch_array($dataBiaya)) {
			$biayaPerjam = $hasil_dataBiaya['biaya_perjam'];
			$biayaMaksimal = $hasil_dataBiaya['biaya_maksimal'];
		}
			?>
            <div style="font-size:8px;margin-top:8px;">Tarif Perjam<span class="w3-right">Rp. <?php echo number_format($biayaPerjam,0,",","."); ?></span></div>
            <div style="font-size:8px">Tarif Max Perhari<span class="w3-right">Rp. <?php echo number_format($biayaMaksimal,0,",","."); ?></span></div>
            
            
            
            <div class="w3-center" style="margin-top:10px;">
            <div style="font-size:8px"><i>Terima Kasih</i></div>
            </div>
            
           
        </div>
    </div>	
</div>
<?php } while ($row_rsCetakKeluar = mysql_fetch_assoc($rsCetakKeluar)); ?>
<?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsCetakKeluar);
?>
<?php if ($totalRows_rsCetakKeluar == 0) { // Show if recordset empty ?>
  <center>TIKET TIDAK TERSEDIA</center>
<?php } // Show if recordset empty ?>
<script>
function cetak(){
setInterval(function(){window.print()},3000);
setInterval(function(){window.close()},5000);
}
</script>
</body>
</html>