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
date_default_timezone_set('Asia/Jakarta');
$tglSekarang = date('Y-m-d');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tb_parkir SET no_polisi=%s, jenis_kendaraan=%s, masuk=%s, keluar=%s, durasi=%s, biaya=%s, status=%s WHERE id_parkir=%s",
                       GetSQLValueString($_POST['no_polisi'], "text"),
                       GetSQLValueString($_POST['jenis_kendaraan'], "text"),
                       GetSQLValueString($_POST['masuk'], "date"),
                       GetSQLValueString($_POST['keluar'], "date"),
                       GetSQLValueString($_POST['durasi'], "text"),
                       GetSQLValueString($_POST['biaya'], "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['id_parkir'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "#";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['no_polisi'] == "" || trim($_POST['no_polisi']) == "" || $_POST['jenis_kendaraan'] == ""){
		header('location: #');
		} else {
  $insertSQL = sprintf("INSERT INTO tb_parkir (id_parkir, no_polisi, jenis_kendaraan, masuk, keluar, durasi, biaya, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_parkir'], "text"),
                       GetSQLValueString(strtoupper($_POST['no_polisi']), "text"),
                       GetSQLValueString($_POST['jenis_kendaraan'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($_POST['keluar'], "date"),
                       GetSQLValueString($_POST['durasi'], "text"),
                       GetSQLValueString($_POST['biaya'], "int"),
                       GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "#";
  //if (isset($_SERVER['QUERY_STRING'])) {
    //$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    //$insertGoTo .= $_SERVER['QUERY_STRING'];
  //}
  header(sprintf("Location: %s", $insertGoTo));	
		}
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tb_parkir SET no_polisi=%s, jenis_kendaraan=%s, masuk=%s, keluar=%s, durasi=%s, biaya=%s, status=%s WHERE id_parkir=%s",
                       GetSQLValueString($_POST['no_polisi'], "text"),
                       GetSQLValueString($_POST['jenis_kendaraan'], "text"),
                       GetSQLValueString($_POST['masuk'], "date"),
                       GetSQLValueString($_POST['keluar'], "date"),
                       GetSQLValueString($_POST['durasi'], "text"),
                       GetSQLValueString($_POST['biaya'], "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['id_parkir'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "#";
  //if (isset($_SERVER['QUERY_STRING'])) {
    //$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    //$updateGoTo .= $_SERVER['QUERY_STRING'];
  //}
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsParkirCreate = "-1";
if (isset($_GET['id_parkir'])) {
  $colname_rsParkirCreate = $_GET['id_parkir'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsParkirCreate = sprintf("SELECT * FROM tb_parkir WHERE id_parkir = %s", GetSQLValueString($colname_rsParkirCreate, "text"));
$rsParkirCreate = mysql_query($query_rsParkirCreate, $koneksi) or die(mysql_error());
$row_rsParkirCreate = mysql_fetch_assoc($rsParkirCreate);
$totalRows_rsParkirCreate = mysql_num_rows($rsParkirCreate);

mysql_select_db($database_koneksi, $koneksi);
$query_rsParkirRead = "SELECT * FROM tb_parkir WHERE status='Masuk' AND date(masuk)='$tglSekarang' ORDER BY masuk DESC";
$rsParkirRead = mysql_query($query_rsParkirRead, $koneksi) or die(mysql_error());
$row_rsParkirRead = mysql_fetch_assoc($rsParkirRead);
$totalRows_rsParkirRead = mysql_num_rows($rsParkirRead);

$maxRows_rsParkirCari = 10;
$pageNum_rsParkirCari = 0;
if (isset($_GET['pageNum_rsParkirCari'])) {
  $pageNum_rsParkirCari = $_GET['pageNum_rsParkirCari'];
}
$startRow_rsParkirCari = $pageNum_rsParkirCari * $maxRows_rsParkirCari;

$colname_rsParkirCari = "-1";
if (isset($_POST['txtCari'])) {
  $colname_rsParkirCari = $_POST['txtCari'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsParkirCari = sprintf("SELECT * FROM tb_parkir WHERE id_parkir = %s AND status='Masuk'", GetSQLValueString($colname_rsParkirCari, "text"));
$query_limit_rsParkirCari = sprintf("%s LIMIT %d, %d", $query_rsParkirCari, $startRow_rsParkirCari, $maxRows_rsParkirCari);
$rsParkirCari = mysql_query($query_limit_rsParkirCari, $koneksi) or die(mysql_error());
$row_rsParkirCari = mysql_fetch_assoc($rsParkirCari);

if (isset($_GET['totalRows_rsParkirCari'])) {
  $totalRows_rsParkirCari = $_GET['totalRows_rsParkirCari'];
} else {
  $all_rsParkirCari = mysql_query($query_rsParkirCari);
  $totalRows_rsParkirCari = mysql_num_rows($all_rsParkirCari);
}
$totalPages_rsParkirCari = ceil($totalRows_rsParkirCari/$maxRows_rsParkirCari)-1;

mysql_select_db($database_koneksi, $koneksi);
$query_rsTampilKeluar = "SELECT * FROM tb_parkir WHERE status='Keluar' AND date(keluar)='$tglSekarang' ORDER BY keluar DESC";
$rsTampilKeluar = mysql_query($query_rsTampilKeluar, $koneksi) or die(mysql_error());
$row_rsTampilKeluar = mysql_fetch_assoc($rsTampilKeluar);
$totalRows_rsTampilKeluar = mysql_num_rows($rsTampilKeluar);
$IDParkir = "23456789234567898232564654987";
?>
<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/template-1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>MASUK / KELUAR</title>
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
        <div class="w3-large w3-border-bottom" style="padding-bottom:8px;">Masuk / Keluar Parkir<span class="w3-right"><span style="cursor:pointer;" class="w3-tag w3-small w3-padding-small w3-green tablink" onclick="openCity(event, 'London')"><i class="fa fa-sign-in fa-fw"></i> Masuk Parkir</span> <span  style="cursor:pointer" class="w3-tag w3-small w3-green w3-padding-small tablink" onclick="openCity(event, 'Paris')"><i class="fa fa-sign-out fa-fw"></i> Keluar Parkir</span></span></div>
<div id="London" style="margin-top:8px;" class="w3-row city">
	<div class="w3-col s12 m3 l3">
    <div style="margin-top:8px;" class="w3-tag w3-block w3-center w3-small w3-padding-large w3-pale-yellow">
  Silahkan Masukan No. Polisi<br>
Dan Jenis Kendaraan
</div>
    <form method="post" id="formMasuk" name="form1" action="<?php echo $editFormAction; ?>">
    <div style="margin-top:12px;">
    <input placeholder="No. Polisi" autofocus type="text" class="w3-input w3-center w3-border w3-small" required name="no_polisi" value="" size="32">
    </div>
    <div style="margin-top:8px;">
    <fieldset style="margin-top:8px;">
      <legend class="w3-center">&nbsp;&nbsp;Jenis Kendaraan&nbsp;&nbsp;</legend>
	  <?php
  $datametode = mysql_query("SELECT * FROM tb_jenis_kendaraan ORDER BY jenis_kendaraan ASC");
  if($datametode === FALSE){
	  die(mysql_error());
	  }
	while($hasil_datametode = mysql_fetch_array($datametode)){
		$metode = $hasil_datametode['jenis_kendaraan'];
   ?>
      <input type="radio" required name="jenis_kendaraan" value="<?php echo $metode ?>" <?php if (!(strcmp("$metode", ""))) {echo "checked=\"checked\"";} ?>> <?php echo $metode ?><br>
      <?php } ?>
      </fieldset>
      </div>
  <table width="100%" align="center">
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Id_parkir:</td>
      <td><input type="text" name="id_parkir" value="<?php echo substr(str_shuffle($IDParkir),0,6) ?>" size="32"></td>
    </tr>
    
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Masuk:</td>
      <td><input type="text" name="masuk" value="" size="32"></td>
    </tr>
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Keluar:</td>
      <td><input type="text" name="keluar" value="" size="32"></td>
    </tr>
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Durasi:</td>
      <td><input type="text" name="durasi" value="" size="32"></td>
    </tr>
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Biaya:</td>
      <td><input type="text" name="biaya" value="" size="32"></td>
    </tr>
    <tr valign="baseline" style="display:none">
      <td align="left" valign="middle" nowrap>Status:</td>
      <td><input type="text" name="status" value="Masuk" size="32"></td>
    </tr>
    
  </table>
  <div style="margin-top:8px;">
  <button type="button" onClick="document.getElementById('cetakMasuk').click();document.getElementById('formMasuk').submit()" class="w3-btn w3-small w3-block w3-green"><i class="fa fa-check-circle fa-fw"></i> Masuk</button>
  </div>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<a style="display:none" id="cetakMasuk" href="cek.php" 
  target="popup" 
  onclick="window.open('cek.php','popup','width=300,height=400'); return false;">
    Cetak Struk Masuk
</a>
    </div>
    <div class="w3-col s12 m9 l9" style="padding-left:15px;">
    
<?php if ($totalRows_rsParkirRead > 0) { // Show if recordset not empty ?>
  <div class="w3-row">
 	
	<div class="w3-col l3" style="padding-right:4px;"><input oninput="w3.filterHTML('#mas', '.mas', this.value)" placeholder="Pencarian Data" class="w3-input w3-border w3-center w3-small" style="outline:none;margin-top:8px;" autofocus></div>
    <div class="w3-col l9" style="padding-left:4px;"><div class="w3-border w3-border-yellow w3-pale-yellow w3-padding w3-small" style="margin-top:8px;">
        Total Yang Parkir<span class="w3-right"><?php echo $totalRows_rsParkirRead ?></span>
        </div></div>
</div>

        
  <table class="w3-table w3-striped w3-small w3-hoverable w3-bordered w3-border" id="mas" style="margin-top:8px;">
      <tr style="font-weight:bold" class="w3-hover-none">
      <td class="w3-border-right w3-center">Kode</td>
      <td class="w3-border-right w3-center">No. Polisi</td>
      <td class="w3-border-right">Jenis Kendaraan</td>
      <td class="w3-border-right w3-center">Masuk</td>
      <td class="w3-center">Opsi</td>
    </tr>
    <?php do { ?>
      <tr class="mas">
        <td class="w3-border-right w3-center"><?php echo $row_rsParkirRead['id_parkir']; ?></td>
        <td class="w3-border-right w3-center"><?php echo $row_rsParkirRead['no_polisi']; ?></td>
        <td class="w3-border-right"><?php echo $row_rsParkirRead['jenis_kendaraan']; ?></td>
        <td class="w3-border-right w3-center"><?php
		$year = substr($row_rsParkirRead['masuk'],0,4);
		$mont = substr($row_rsParkirRead['masuk'],5,2);
		$day = substr($row_rsParkirRead['masuk'],8,2);
		$jam = substr($row_rsParkirRead['masuk'],11,5);
		 ?> <?php echo $day."-".$mont."-".$year." ".$jam; ?></td>
        <td class="w3-center"><a class="w3-tag w3-small w3-red" style="text-decoration:none" onClick="return confirm('Anda Yakin Ingin Menghapus?\nNo. Polisi : <?php echo $row_rsParkirRead['no_polisi']; ?>\nJenis Kendaraan : <?php echo $row_rsParkirRead['jenis_kendaraan']; ?>')" href="parkir_delete.php?id_parkir=<?php echo $row_rsParkirRead['id_parkir']; ?>">Hapus</a></td>
      </tr>
      <?php } while ($row_rsParkirRead = mysql_fetch_assoc($rsParkirRead)); ?>
  </table><br>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsParkirRead == 0) { // Show if recordset empty ?>
  <div class="w3-row">
 	
	<div class="w3-col l3" style="padding-right:4px;"><input oninput="w3.filterHTML('#mas', '.mas', this.value)" placeholder="Pencarian Data" class="w3-input w3-border w3-center w3-small" style="outline:none;margin-top:8px;" autofocus></div>
    <div class="w3-col l9" style="padding-left:4px;"><div class="w3-border w3-border-yellow w3-pale-yellow w3-padding w3-small" style="margin-top:8px;">
        Total Yang Parkir<span class="w3-right"><?php echo $totalRows_rsParkirRead ?></span>
        </div></div>
</div><table class="w3-table w3-border" style="margin-top:8px;">
    <tr>
      <td class="w3-center">Belum Ada Yang Parkir</td>
      </tr>
    </table>
  <?php } // Show if recordset empty ?>
    </div>
</div>









<div id="Paris" style="margin-top:8px;display:none" class="w3-row city">
	<div class="w3-col s12 m3 l3">
    MASUKAN KODE STRUK
<form id="fCari" name="formKeluar" action="parkir_cr.php#fCari" method="post">
	<input type="text" name="txtCari" value="" />
    <input type="submit" value="Keluar" />
</form>
<br>
<br>
<br>
<table border="1">
  <tr>
    <td>id_parkir</td>
    <td>no_polisi</td>
    <td>jenis_kendaraan</td>
    <td>masuk</td>
    <td>keluar</td>
    <td>durasi</td>
    <td>biaya</td>
    <td>status</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsParkirCari['id_parkir']; ?></td>
      <td><?php echo $row_rsParkirCari['no_polisi']; ?></td>
      <td><?php $jk = $row_rsParkirCari['jenis_kendaraan']; echo $jk ?></td>
      <td><?php echo $row_rsParkirCari['masuk']; ?></td>
      <td><?php echo $row_rsParkirCari['keluar']; ?></td>
      <td><?php echo $row_rsParkirCari['durasi']; ?></td>
      <td><?php echo $row_rsParkirCari['biaya']; ?></td>
      <td><?php echo $row_rsParkirCari['status']; ?></td>
    </tr>
    <tr>
    	<td colspan="8">
        <?php
		// Metode Hitungan Lama Parkir
		$awal  = date_create($row_rsParkirCari['masuk']);
		$akhir = date_create();
		$diff  = date_diff( $awal, $akhir );
		$hariNya = $diff->d;
		$jamNya = $diff->h;
		$menitNya = $diff->i;
		
		echo 'Penggunaan : <br>';
		echo $hariNya." Hari<br>";
		echo $jamNya." Jam<br>";
		echo $menitNya." Menit<br><br>";
		
		// Penambahan Angka 0 Pada Jam Jika < 10
		if($jamNya < 10){
			$jamNya2 = "0".$jamNya;
		} else {
			$jamNya2 = $jamNya;
		}
		
		if($menitNya < 10){
			$menitNya2 = "0".$menitNya;
		} else {
			$menitNya2 = $menitNya;
		}
		
		// Penambahan Keterangan 00 Jika Hari = 0
		if($hariNya < 1){
			$durasi = $jamNya2.":".$menitNya2;
		} else {
			$durasi = $jamNya2.":".$menitNya2;
		}
		
		// Ambil Data Biaya
		$dataBiaya = mysql_query("SELECT * FROM tb_biaya_parkir WHERE jenis_kendaraan='$jk'");
		if($dataBiaya === FALSE){
			die(mysql_error());
		} while($hasil_dataBiaya = mysql_fetch_array($dataBiaya)) {
			$biayaPerjam = $hasil_dataBiaya['biaya_perjam'];
			$biayaMaksimal = $hasil_dataBiaya['biaya_maksimal'];
			
			echo "Biaya Perjam : ".$biayaPerjam."<br>";
			echo "Biaya Maksimal : ".$biayaMaksimal."<br>";
			
			if($hariNya >= "1"){
				if($jamNya > "0" && $menitNya >= "10"){
					$sisa = ($jamNya*$biayaPerjam)+$biayaPerjam;
				} elseif($jamNya == "0" && $menitNya >= "10"){
					$sisa = $biayaPerjam;
					}
				$gt = ($hariNya*$biayaMaksimal) + $sisa;
				echo "Sudah Sehari : Rp.".$gt."<br>";
			} elseif($hariNya < "1") {
				if($jamNya > "0" && $menitNya >= "10"){
					$totalJam = ($jamNya*$biayaPerjam)+$biayaPerjam;
					echo "Biaya Yang Harus Dibayar : Rp. ".$totalJam;
				} elseif($jamNya == "0") {
					echo "Bayar Cuma : ".$biayaPerjam;
			} elseif($hariNya < "1" && $menitNya < "10") {
				$totalJam = ($jamNya*$biayaPerjam);
				echo "Biaya Yang Harus Dibayar 0 : Rp. ".$totalJam;
			}
			
		} 
	}
	?>
        <form method="post" id="formKeluar" name="form2" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right">Id_parkir:</td>
                <td><?php echo $row_rsParkirCari['id_parkir']; ?></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">No_polisi:</td>
                <td><input type="text" name="no_polisi" value="<?php echo htmlentities($row_rsParkirCari['no_polisi'], ENT_COMPAT, ''); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Jenis_kendaraan:</td>
                <td><input type="text" name="jenis_kendaraan" value="<?php echo htmlentities($row_rsParkirCari['jenis_kendaraan'], ENT_COMPAT, ''); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Masuk:</td>
                <td><input type="text" name="masuk" value="<?php echo htmlentities($row_rsParkirCari['masuk'], ENT_COMPAT, ''); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Keluar:</td>
                <td><input type="text" name="keluar" value="<?php echo date('Y-m-d H:i:s') ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Durasi:</td>
                <td><input type="text" name="durasi" value="<?php echo $durasi ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Biaya:</td>
                <td><input type="text" name="biaya" value="<?php if($hariNya >= "1"){
				if($jamNya > "0" && $menitNya > "10"){
					$sisa = ($jamNya*$biayaPerjam)+$biayaPerjam;
				}
				$gt = ($hariNya*$biayaMaksimal) + $sisa;
				echo $gt;
			} elseif($hariNya < "1") {
				if($jamNya > "0" && $menitNya >= "10"){
					$totalJam = ($jamNya*$biayaPerjam)+$biayaPerjam;
					echo $totalJam;
				} elseif($jamNya == "0") {
					echo $biayaPerjam;
			} elseif($hariNya < "1" && $menitNya < "10") {
				$totalJam = ($jamNya*$biayaPerjam);
				echo $totalJam;
			}
			
		} ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Status:</td>
                <td><input type="text" name="status" value="<?php echo htmlentities($row_rsParkirCari['status'], ENT_COMPAT, ''); ?>" size="32"></td>
              </tr>
              
            </table>
            <button type="button" onClick="document.getElementById('cetakKeluar').click();document.getElementById('formKeluar').submit()" class="w3-btn w3-small w3-green"><i class="fa fa-sign-out fa-fw"></i> Keluar</button>
            <input type="hidden" name="MM_update" value="form2">
            <input type="hidden" name="id_parkir" value="<?php echo $row_rsParkirCari['id_parkir']; ?>">
          </form>
          
<a id="cetakKeluar" href="cek2.php?id_parkir=<?php echo $row_rsParkirCari['id_parkir']; ?>" 
  target="popup" 
  onclick="window.open('cek2.php?id_parkir=<?php echo $row_rsParkirCari['id_parkir']; ?>','popup','width=300,height=400'); return false;">
    Cetak Struk Keluar
</a>
          </td>
    </tr>
    <?php } while ($row_rsParkirCari = mysql_fetch_assoc($rsParkirCari)); ?>
    
</table>
<br>
<br>

    </div>
    <div class="w3-col s12 m9 l9">
    DATA YANG KELUAR PARKIR HARI INI<br>
<?php if ($totalRows_rsTampilKeluar > 0) { // Show if recordset not empty ?>
  <table border="1">
    <tr>
      <td>id_parkir</td>
      <td>no_polisi</td>
      <td>jenis_kendaraan</td>
      <td>masuk</td>
      <td>keluar</td>
      <td>durasi</td>
      <td>biaya</td>
      <td>status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsTampilKeluar['id_parkir']; ?></td>
        <td><?php echo $row_rsTampilKeluar['no_polisi']; ?></td>
        <td><?php echo $row_rsTampilKeluar['jenis_kendaraan']; ?></td>
        <td><?php echo $row_rsTampilKeluar['masuk']; ?></td>
        <td><?php echo $row_rsTampilKeluar['keluar']; ?></td>
        <td><?php echo $row_rsTampilKeluar['durasi']; ?></td>
        <td><?php echo $row_rsTampilKeluar['biaya']; ?></td>
        <td><?php echo $row_rsTampilKeluar['status']; ?></td>
      </tr>
      <?php } while ($row_rsTampilKeluar = mysql_fetch_assoc($rsTampilKeluar)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<br>
<?php if ($totalRows_rsTampilKeluar == 0) { // Show if recordset empty ?>
  <table width="100%" border="1">
    <tr>
      <td>Belum Ada Yang Masuk Parkir</td>
    </tr>
  </table>
  <?php } // Show if recordset empty ?>
    </div>
</div>        
 

<script>
function openCity(evt, cityName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" w3-red", ""); 
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " w3-red";
}
</script>

<?php
mysql_free_result($rsParkirCreate);

mysql_free_result($rsParkirRead);

mysql_free_result($rsParkirCari);

mysql_free_result($rsTampilKeluar);
?>
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