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
<a style="display:none" id="cetakMasuk" href="parkir_cetak_masuk.php" 
  target="popup" 
  onclick="window.open('parkir_cetak_masuk.php','popup','width=300,height=400'); return false;">
    Cetak Struk Masuk
</a>
<script>
function cetak(){
document.getElementById('cetakMasuk').click();
setInterval(function(){window.close()},4000);
}
</script>
</body>
</html>