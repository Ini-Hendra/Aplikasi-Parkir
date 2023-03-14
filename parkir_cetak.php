<?php require_once('Connections/koneksi.php'); ?>
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

$maxRows_rsStrukCetak = 1;
$pageNum_rsStrukCetak = 0;
if (isset($_GET['pageNum_rsStrukCetak'])) {
  $pageNum_rsStrukCetak = $_GET['pageNum_rsStrukCetak'];
}
$startRow_rsStrukCetak = $pageNum_rsStrukCetak * $maxRows_rsStrukCetak;

mysql_select_db($database_koneksi, $koneksi);
$query_rsStrukCetak = "SELECT * FROM tb_parkir ORDER BY masuk DESC";
$query_limit_rsStrukCetak = sprintf("%s LIMIT %d, %d", $query_rsStrukCetak, $startRow_rsStrukCetak, $maxRows_rsStrukCetak);
$rsStrukCetak = mysql_query($query_limit_rsStrukCetak, $koneksi) or die(mysql_error());
$row_rsStrukCetak = mysql_fetch_assoc($rsStrukCetak);

if (isset($_GET['totalRows_rsStrukCetak'])) {
  $totalRows_rsStrukCetak = $_GET['totalRows_rsStrukCetak'];
} else {
  $all_rsStrukCetak = mysql_query($query_rsStrukCetak);
  $totalRows_rsStrukCetak = mysql_num_rows($all_rsStrukCetak);
}
$totalPages_rsStrukCetak = ceil($totalRows_rsStrukCetak/$maxRows_rsStrukCetak)-1;
?>
<p>CETAK STRUK </p>
<table border="1">
  <tr>
    <td>id_parkir</td>
    <td>no_polisi</td>
    <td>jenis_kendaraan</td>
    <td>masuk</td>
    <td>keluar</td>
    <td>durasi</td>
    <td>status</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsStrukCetak['id_parkir']; ?></td>
      <td><?php echo $row_rsStrukCetak['no_polisi']; ?></td>
      <td><?php echo $row_rsStrukCetak['jenis_kendaraan']; ?></td>
      <td><?php echo $row_rsStrukCetak['masuk']; ?></td>
      <td><?php echo $row_rsStrukCetak['keluar']; ?></td>
      <td><?php echo $row_rsStrukCetak['durasi']; ?></td>
      <td><?php echo $row_rsStrukCetak['status']; ?></td>
    </tr>
    <?php } while ($row_rsStrukCetak = mysql_fetch_assoc($rsStrukCetak)); ?>
</table>
<?php
mysql_free_result($rsStrukCetak);
?>
