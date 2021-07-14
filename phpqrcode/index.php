<?php    
if (isset($_REQUEST['data'])) { 
	require_once 'phpqrcode.php';
	$value = $_REQUEST['data'];         //二维码内容
	$errorCorrectionLevel = 'H';  //容错级别
	$matrixPointSize = 5;      //生成图片大小
	//生成二维码图片
	$QR = QRcode::png($value,false,$errorCorrectionLevel, $matrixPointSize, 0);
}