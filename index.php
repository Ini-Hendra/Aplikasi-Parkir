<!-- --------------------------------//
Programmer  : Muhamad Hendra, S.Kom
Phone / WA  : 0838 1212 1231
------------------------------------->
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=base64_encode($_POST['textfield2']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "home.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_koneksi, $koneksi);
  
  $LoginRS__query=sprintf("SELECT username, password FROM tb_petugas WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $koneksi) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>LOGIN - BIG PARKING</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="BIG Parking By Muhamad Hendra" />
<link rel="icon" href="assets/fav.png" type="image/x-icon">
<!--<meta name="theme-color" content="" >-->
<link rel="stylesheet" href="assets/w3.css">
<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
<script src="assets/jquery-3.4.1.min.js"></script>
<script src="assets/w3.js"></script>
<style>body{
	background-image:url(assets/bg2.png);
	background-size:cover;
	background-repeat:no-repeat;
}.preloader {position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;background-color: #fff;}.loading {position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);font: 14px arial;}</style>
</head>
<body oncontextmenu="return false" onselectstart="return false">
<br>
<br><br>

<div class="w3-container">
	<div class="w3-row">
    	<div class="w3-col w3-hide-small m4 l4">&nbsp;</div>
        <div class="w3-col s12 m4 l4">
        <div class="w3-center"><img src="assets/weblogo.png" width="448" height="94"></div>
        <div class="w3-center" style="font-size:24px">PT. Batavia Indo Global</div>
        <br>
<br>

        <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
<div>
  <input autofocus class="w3-input w3-center w3-border w3-small" required placeholder="Username" type="text" name="textfield" id="textfield" />
</div>
<div style="margin-top:10px;">
  <input class="w3-input w3-border w3-center w3-small" required placeholder="Password" type="password" name="textfield2" id="textfield2" />
</div>
  <div style="margin-top:10px;">
  <button type="submit" class="w3-btn w3-block w3-small w3-green"><i class="fa fa-lock fa-fw"></i> Masuk</button>
  </div>
</form>
<br>
<br>
<div class="w3-center" style="font-size:10px">
Copyright &copy; 2023 <b>Muhamad Hendra</b><br>
<b>Aplikasi BIG Parking</b><br>
All Right Reserved
</div></div>
        <div class="w3-col w3-hide-small m4 l4">&nbsp;</div>
    </div>
</div>

</body>
</html>
