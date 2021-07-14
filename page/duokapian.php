<?php
//SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_NAME = 'xinxizichan' and COLUMN_NAME = 'pp'
//获取指定列备注

//http://76.40.119.20/phpqrcode/index.php?data=like_that
//http://76.40.119.20/barcode/html/img.php?scale=2&font_family=0&font_size=8&text=cv6fd3adg&thickness=30&start=NULL&code=BCGcode128
//kk=xxkapian&zz=xinxizichan&id=5
$kk = $_GET['kk'];
$zz = $_GET['zz'];
$id = $_GET['id'];
$id = explode(",", $id);
include_once("../config.php");
$sql = "select value from config where title='$kk'";
$requ = mysqli_query($con,$sql);
$rs = mysqli_fetch_array($requ);

$data = json_decode($rs['value'],true);

$title = $data['title'];//卡片标题
$bianma = $data['bianma'];
$ma = $data['ma'];//条码或二维码
$content = $data['content'];
$innerhtml='';
foreach($id as $d){
	$innerhtml .= '<div class="maindiv"><div class="titlediv">'.$title.'</div><div class="leftdiv">';
	foreach($content as $v){
		if($v == 'zclx'){
			$sql = "select a.$bianma as bima,zclx.name as zhi from $zz as a left join zclx on a.zclx=zclx.id where a.id=$d";
		}elseif($v == 'zczt'){
			$sql = "select a.$bianma as bima,zhuangtai.name as zhi from $zz as a left join zhuangtai on a.zczt=zhuangtai.id where a.id=$d";
		}elseif($v == 'bm'){
			$sql = "select a.$bianma as bima,danwei.name as zhi from $zz as a left join danwei on a.bm=danwei.id where a.id=$d";
		}else{
			$sql = "select a.$bianma as bima,a.$v as zhi from $zz as a where a.id=$d";
		}
		//echo $sql.'<br>';
		$requ = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($requ);
		$bima = $rs['bima'];//将被编码内容
		$zhi = $rs['zhi'];//值
		if($v == 'wlbs'){
			$w = array("未指定","内网","外网");
			$zhi = $w[$zhi];
		}
		$sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_NAME = '$zz' and COLUMN_NAME = '$v'";
		$requ = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($requ);
		$name = $rs['COLUMN_COMMENT'];//条目名
		$innerhtml .= '<div class="listview"><span class="namespan">'.$name.'</span>:<span class="zhispan">'.$zhi.'</span></div>';
	}
	if($ma == 0){
		$innerhtml .= '</div><div class="imgview"><img src="'.$url.'barcode/html/img.php?scale=2&font_family=0&font_size=8&text='.$bima.'&thickness=30&start=NULL&code=BCGcode128" /></div>';
	}else{
		$innerhtml .= '</div><div class="imgview"><img src="'.$url.'phpqrcode/index.php?data='.$bima.'" /></div>';
	}
	$innerhtml .= '</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>资产卡片</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<script src="../lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
	<script src="../js/jquery.print.min.js" charset="utf-8"></script>
    <style>
		.no-print{
			width:100px;
			height:48px;
			text-align:center;
			color:#fff;
			background-color:#1296db;
			border-radius:15px;
			line-height:48px;
			margin:20px auto;
		}
		#dayin{
			width:48px;
			height:36px;
			margin:7px 10px;
			background-color:#1296db;
			color:#fff;
			line-height:36px;
			text-align:center;
		}
		<?php if($ma == 0){ ?>
        body {background-color:#eee;margin:0;padding:0;}
		.maindiv{
			background-color:#fff;
			margin:0 auto;
			width:calc(80mm - 20px);
			height:39.5mm;
			padding:0 10px;
			border-radius:10px;
		}
		.titlediv{
			width:100%;
			height:16.5mm;
			font-size:20px;
			line-height:16.5mm;
			text-align:right;
		}

		.listview{
			height:5mm;
			font-size:4mm;
			line-height:5mm;
		}
		.imgview{width:100%;text-align:center;height:5mm;} 
		.imgview img{height:5mm;width:48mm;}
		<?php }else{ ?>
        body {background-color:#eee;margin:0;padding:0;}
		.maindiv{
			background-color:#fff;
			margin:0 auto;
			width:calc(80mm - 20px);
			height:39.5mm;
			padding:0 10px;
			border-radius:10px;
		}
		.titlediv{
			width:100%;
			height:16.5mm;
			font-size:20px;
			line-height:16.5mm;
			text-align:right;
		}
		.leftdiv{
			width:calc(50mm - 10px);
			height:22mm;
			float:left;
		}
		.listview{
			height:5mm;
			font-size:4mm;
			line-height:5mm;
		}
		.imgview{
			width:19mm;
			height:22mm;
			float:right;
		} 
		.imgview img{height:19mm;width:19mm;margin-top:1.5mm;}
		<?php } ?>
	</style>
	<style media="print">
		@page {
			size: auto;
			margin: 0mm;
		}
	</style>
</head>
<body>
	<div style="width:100%;height:50px;position:fixed;top:0;left:0;">
	<div id="dayin">打印</div>
	</div>
	<div id="maindiv">
		<?php echo $innerhtml; ?>
	</div>
	<div id="btnprint" class="no-print">打 印</div>
	<script type="text/javascript">
	$("#btnprint").click(function(){
		$("#maindiv").print({
			mediaPrint: true
		});
	});
	$("#dayin").click(function(){
		$("#maindiv").print({
			mediaPrint: true
		});
	});
	</script>
</body>
</html>