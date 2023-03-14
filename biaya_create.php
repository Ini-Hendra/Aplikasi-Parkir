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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="biaya_error.php";
  $loginUsername = $_POST['jenis_kendaraan'];
  $LoginRS__query = sprintf("SELECT jenis_kendaraan FROM tb_biaya_parkir WHERE jenis_kendaraan=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_koneksi, $koneksi);
  $LoginRS=mysql_query($LoginRS__query, $koneksi) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tb_biaya_parkir (id_biaya, jenis_kendaraan, biaya_perjam, biaya_maksimal) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_biaya'], "text"),
                       GetSQLValueString($_POST['jenis_kendaraan'], "text"),
                       GetSQLValueString($_POST['biaya_perjam'], "int"),
                       GetSQLValueString($_POST['biaya_maksimal'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "biaya_read.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsBiayaCreate = "-1";
if (isset($_GET['id_biaya'])) {
  $colname_rsBiayaCreate = $_GET['id_biaya'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_rsBiayaCreate = sprintf("SELECT * FROM tb_biaya_parkir WHERE id_biaya = %s", GetSQLValueString($colname_rsBiayaCreate, "text"));
$rsBiayaCreate = mysql_query($query_rsBiayaCreate, $koneksi) or die(mysql_error());
$row_rsBiayaCreate = mysql_fetch_assoc($rsBiayaCreate);
$totalRows_rsBiayaCreate = mysql_num_rows($rsBiayaCreate);
$IDBiaya = "12345678912345678913456789132";
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/template-1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>TAMBAH BIAYA PARKIR</title>
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
        <div class="w3-large w3-border-bottom" style="padding-bottom:8px;"><a style="cursor:pointer" onClick="window.history.back();document.getElementById('loading').style.display='block'"><i class="fa fa-arrow-left"></i></a> &nbsp;&nbsp;Tambah Biaya Parkir</div>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="100%" align="center" style="margin-top:8px;">
    <tr valign="baseline" style="display:none">
      <td width="43%" align="right" nowrap="nowrap">Id_biaya:</td>
      <td width="57%"><input type="text" name="id_biaya" value="<?php echo substr(str_shuffle($IDBiaya),0,10) ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="left" valign="middle" nowrap="nowrap">Jenis Kendaraan</td>
      <td><select name="jenis_kendaraan" class="w3-input w3-border w3-small" required style="cursor:pointer;">
<option value="">Pilih Jenis Kendaraan</option>
      <?php
  $datametode = mysql_query("SELECT * FROM tb_jenis_kendaraan ORDER BY jenis_kendaraan ASC");
  if($datametode === FALSE){
	  die(mysql_error());
	  }
	while($hasil_datametode = mysql_fetch_array($datametode)){
		$metode = $hasil_datametode['jenis_kendaraan'];
   ?>
        <option value="<?php echo $metode ?>" <?php if (!(strcmp("$metode", ""))) {echo "SELECTED";} ?>><?php echo $metode ?></option>
        
         <?php } ?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td align="left" valign="middle" nowrap="nowrap">Biaya Perjam</td>
      <td><input class="w3-input w3-border w3-small" required type="number" min="0" name="biaya_perjam" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="left" valign="middle" nowrap="nowrap">Biaya Maksimal (Perhari)</td>
      <td><input class="w3-input w3-border w3-small" required type="number" min="0" name="biaya_maksimal" value="" size="32" /></td>
    </tr>
    
  </table>
  <hr>
  <div class="w3-center">
  <a onClick="window.history.back();document.getElementById('loading').style.display='block'" style="cursor:pointer" class="w3-btn w3-small w3-red"><i class="fa fa-times-circle fa-fw"></i> Batal</a> <button type="submit" class="w3-btn w3-small w3-green"><i class="fa fa-save fa-fw"></i> Simpan</button>
  </div>

  <input type="hidden" name="MM_insert" value="form1" />
</form>
<br>

<?php
mysql_free_result($rsBiayaCreate);
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