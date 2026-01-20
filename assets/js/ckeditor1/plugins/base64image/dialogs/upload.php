<?php
$uploadDir="upload/ckeditor/";
$BaseUrl = $_POST['baseurl'];
$FileExt = $_POST['fileExt'];
$image_parts = explode(";base64,", $_POST['base64Img']);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);

//saving
$dir="../../../../../../".$uploadDir;if (!file_exists( $dir)) {mkdir( $dir, 0777, true);}
$fileName=date("Ymd")."-".uniqid().".".$FileExt;
file_put_contents($dir.$fileName, $image_base64);
echo $BaseUrl.$uploadDir.$fileName;
?>