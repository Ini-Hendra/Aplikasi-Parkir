<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
<?php require_once('Connections/koneksi.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$colname_rsPendapatanBulanan = "-1";
$colname_rsPendapatanBulanan2 = "-1";
if (isset($_POST['txtBulan']) && isset($_POST['txtTahun'])) {
  $colname_rsPendapatanBulanan = $_POST['txtBulan'];
  $colname_rsPendapatanBulanan2 = $_POST['txtTahun'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsPendapatanBulanan = sprintf("SELECT * FROM tb_parkir WHERE MONTH(masuk) = %s AND YEAR(masuk) = %s AND status='Masuk' ORDER BY masuk DESC", GetSQLValueString($colname_rsPendapatanBulanan, "date"),GetSQLValueString($colname_rsPendapatanBulanan2, "date"));
$rsPendapatanBulanan = mysql_query($query_rsPendapatanBulanan, $koneksi) or die(mysql_error());
$row_rsPendapatanBulanan = mysql_fetch_assoc($rsPendapatanBulanan);
$totalRows_rsPendapatanBulanan = mysql_num_rows($rsPendapatanBulanan);
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/template-1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>DI LOKASI PARKIR</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="BIG Parking By Muhamad Hendra" />
<link rel="icon" href="assets/fav.png" type="image/x-icon">
<!--<meta name="theme-color" content="" >-->
<link rel="stylesheet" href="assets/w3.css">
<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
<script src="assets/jquery-3.4.1.min.js"></script>
<script src="assets/w3.js"></script>
<style>.preloader {position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;background-color: #fff;}.loading {position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);font: 14px arial;}</style>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body oncontextmenu="return false" onselectstart="return false">
<div class="w3-container w3-top w3-white" style="box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);">
	<div class="w3-row">
    	<div class="w3-col s7 m9 l9 w3-padding-16"><img style="margin-top:4px;" src="assets/weblogo.png" width="183" height="38"> <sup>Versi 1.0</sup></div>
        <div class="w3-col s5 m3 l3 w3-padding-16 w3-center"><b><div style="font-size:20px;color:#ed1b24" id="jam"></div></b>
        <div style="font-size:12px">
		<?php 
		date_default_timezone_set('Asia/Jakarta');
		$tanggalnya = date('d-m-Y');
		$harinya = date('w', strtotime($tanggalnya));
		if($harinya == "0"){
			echo "Minggu, ".$tanggalnya;
			} elseif($harinya == "1"){
				echo "Senin, ".$tanggalnya;
				} elseif($harinya == "2"){
				echo "Selasa, ".$tanggalnya;
				} elseif($harinya == "3"){
				echo "Rabu, ".$tanggalnya;
				} elseif($harinya == "4"){
				echo "Kamis, ".$tanggalnya;
				} elseif($harinya == "5"){
				echo "Jumat, ".$tanggalnya;
				} elseif($harinya == "6"){
				echo "Sabtu, ".$tanggalnya;
				} ?></div>
        </div>
    </div>
</div>
<br><br><br><br>
	<div class="w3-row">
    	<div class="w3-col m4 l3 w3-padding-large">
        <ul class="w3-ul w3-border">
  <a href="home.php" style="text-decoration:none"><li class="w3-hover-pale-blue w3-border-bottom"><i class="fa fa-home fa-fw"></i> Beranda</li></a>
  <a href="parkir_create.php" style="text-decoration:none"><li class="w3-hover-pale-blue w3-border-bottom"><i class="fa fa-sign-in fa-fw"></i> Masuk Parkir</li></a>
  <a href="parkir_out.php" style="text-decoration:none"><li class="w3-hover-pale-blue w3-border-bottom"><i class="fa fa-sign-out fa-fw"></i> Keluar Parkir</li></a>
  <li style="border-bottom:1px solid #DDD; width:100%" class="w3-dropdown-hover"><i class="fa fa-map-pin fa-fw"></i> Laporan Parkir
  <div class="w3-dropdown-content w3-bar-block w3-border" style="margin-top:10px; width:100%">
    <a href="parkir_read.php" class="w3-bar-item w3-hover-pale-blue w3-button w3-border-bottom"><i class="fa fa-motorcycle fa-fw"></i> Kendaraan Di Lokasi</a>
    <a href="make_read.php" class="w3-bar-item w3-hover-pale-blue w3-button w3-border-bottom"><i class="fa fa-motorcycle fa-fw"></i> Kendaraan Masuk & Keluar</a>
    
    
  </div>
  </li>
  <a href="pendapatan_read.php" style="text-decoration:none"><li class="w3-hover-pale-blue w3-border-bottom"><i class="fa fa-money fa-fw"></i> Laporan Pendapatan</li></a>
  <li style="border-bottom:1px solid #DDD; width:100%" class="w3-dropdown-hover"><i class="fa fa-database fa-fw"></i> Data Master
  <div class="w3-dropdown-content w3-bar-block w3-border" style="margin-top:10px; width:100%">
    <a href="petugas_read.php" class="w3-bar-item w3-hover-pale-blue w3-button w3-border-bottom"><i class="fa fa-user-circle fa-fw"></i> Petugas Parkir</a>
    <a href="jenis_read.php" class="w3-bar-item w3-hover-pale-blue w3-button w3-border-bottom"><i class="fa fa-motorcycle fa-fw"></i> Jenis Kendaraan</a>
    <a href="biaya_read.php" class="w3-bar-item w3-hover-pale-blue w3-button w3-border-bottom"><i class="fa fa-money fa-fw"></i> Biaya Parkir</a>
    
  </div>
  </li>
  <a href="bantuan_read.php" style="text-decoration:none"><li class="w3-hover-pale-blue w3-border-bottom"><i class="fa fa-question-circle fa-fw"></i> Bantuan</li></a>
  <a onClick="return confirm('Anda Yakin Ingin Keluar?')" href="<?php echo $logoutAction ?>" style="text-decoration:none"><li class="w3-hover-pale-blue"><i class="fa fa-sign-out fa-fw"></i> Keluar</li></a>
</ul>
        </div>
        <div class="w3-col m8 l9 w3-padding-large"><!-- InstanceBeginEditable name="EditRegion1" -->
        <div class="w3-large w3-border-bottom" style="padding-bottom:8px;">Data Kendaraan Di Lokasi Parkir Berdasarkan Bulan & Tahun</div>
<?php if ($totalRows_rsPendapatanBulanan > 0) { // Show if recordset not empty ?>
  <div class="w3-row">
 	
	<div class="w3-col l3" style="padding-right:4px;"><input oninput="w3.filterHTML('#penga', '.penga', this.value)" placeholder="Pencarian Data" class="w3-input w3-border w3-center w3-small" style="outline:none;margin-top:8px;" autofocus></div>
    <div class="w3-col l9" style="padding-left:4px;"><div class="w3-border w3-border-yellow w3-pale-yellow w3-padding w3-small" style="margin-top:8px;">
        Sub Total Bulan <?php echo $colname_rsPendapatanBulanan ?> Tahun <?php echo $colname_rsPendapatanBulanan2 ?><span class="w3-right"><?php echo $totalRows_rsPendapatanBulanan ?></span>
        </div></div>
</div>

        
  <table class="w3-table w3-striped w3-small w3-hoverable w3-bordered w3-border" id="penga" style="margin-top:8px;">
      <tr style="font-weight:bold" class="w3-hover-none">
      <td class="w3-border-right w3-center">No</td>
      <td class="w3-border-right">No. Polisi</td>
      <td class="w3-border-right">Jenis Kendaraan</td>
      <td class="w3-border-right w3-center">Masuk</td>
      <td class="w3-border-right w3-center">Keluar</td>
      <td class="w3-border-right w3-center">Durasi</td>
      <td class="w3-center">Biaya</td>
    </tr>
    <?php $nomor = 1; do { ?>
      <tr class="penga">
        <td class="w3-border-right w3-center"><?php echo $nomor++; ?></td>
        <td class="w3-border-right"><?php echo $row_rsPendapatanBulanan['no_polisi']; ?></td>
        <td class="w3-border-right"><?php echo $row_rsPendapatanBulanan['jenis_kendaraan']; ?></td>
        <td class="w3-border-right w3-center"><?php
		$year = substr($row_rsPendapatanBulanan['masuk'],0,4);
		$mont = substr($row_rsPendapatanBulanan['masuk'],5,2);
		$day = substr($row_rsPendapatanBulanan['masuk'],8,2);
		$jam = substr($row_rsPendapatanBulanan['masuk'],11,5);
		 ?> <?php echo $day."-".$mont."-".$year." ".$jam; ?>
		 </td>
        <td class="w3-border-right w3-center"><?php
		$year2 = substr($row_rsPendapatanBulanan['keluar'],0,4);
		$mont2 = substr($row_rsPendapatanBulanan['keluar'],5,2);
		$day2 = substr($row_rsPendapatanBulanan['keluar'],8,2);
		$jam2 = substr($row_rsPendapatanBulanan['keluar'],11,5);
		 ?> <?php echo $day2."-".$mont2."-".$year2." ".$jam2; ?></td>
        <td class="w3-border-right w3-center"><?php echo $row_rsPendapatanBulanan['durasi']; ?></td>
        <td>Rp.<span class="w3-right"><?php echo number_format($row_rsPendapatanBulanan['biaya'],0,",","."); ?></span></td>
      </tr>
      <?php } while ($row_rsPendapatanBulanan = mysql_fetch_assoc($rsPendapatanBulanan)); ?>
      
     
      
  </table>
  
  
  
  <br>

  <?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsPendapatanBulanan);
?>
<?php if ($totalRows_rsPendapatanBulanan == 0) { // Show if recordset empty ?>
  <div class="w3-row">
 	
	<div class="w3-col l3" style="padding-right:4px;"><input oninput="w3.filterHTML('#penga', '.penga', this.value)" placeholder="Pencarian Data" class="w3-input w3-border w3-center w3-small" style="outline:none;margin-top:8px;" autofocus></div>
    <div class="w3-col l9" style="padding-left:4px;"><div class="w3-border w3-border-yellow w3-pale-yellow w3-padding w3-small" style="margin-top:8px;">
        Sub Total Bulan <?php echo $colname_rsPendapatanBulanan ?> Tahun <?php echo $colname_rsPendapatanBulanan2 ?><span class="w3-right"><?php echo $totalRows_rsPendapatanBulanan ?></span>
        </div></div>
</div><table class="w3-table w3-border" style="margin-top:8px;">
    <tr>
      <td class="w3-center">Data Masih Kosong</td>
      </tr>
    </table>
  <?php } // Show if recordset empty ?>

		<!-- InstanceEndEditable -->
        
        </div>
    </div>

<!-- PRE LOADER -->
<div class="preloader">
    <div class="loading w3-center">
    <div><img src="assets/loading.gif" width="50" height="50"></div>
    </div>
</div>
<!-- PRE LOADER -->
<!-- LOADING -->
<div id="loading" class="w3-modal" style="z-index:9999">
<div class="w3-row w3-animate-top">
  <div class="w3-col s1 w3-hide-medium w3-hide-large"><p></p></div>
  <div class="w3-col s10 m12 l12 w3-round-large w3-center w3-text-white">
    <img src="assets/loading.gif" width="50" height="50">
  </div>
  <div class="w3-col s1 w3-hide-medium w3-hide-large"><p></p></div>
</div>
</div>
<!-- LOADING -->
<script type="text/javascript">
    window.onload = function() { jam(); }
   
    function jam() {
     var e = document.getElementById('jam'),
     d = new Date(), h, m, s;
     h = d.getHours();
     m = set(d.getMinutes());
     s = set(d.getSeconds());
   
     e.innerHTML = h +':'+ m +':'+ s;
   
     setTimeout('jam()', 1000);
    }
   
    function set(e) {
     e = e < 10 ? '0'+ e : e;
     return e;
    }
</script>
<script>
$(document).ready(function() {
        $(".preloader").fadeOut("slow");
    });
</script>
</body>
<!-- InstanceEnd --></html>