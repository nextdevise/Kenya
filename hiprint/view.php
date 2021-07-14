<?php
    $kk = $_GET['kk'];
    $zz = $_GET['zz'];
    $id = $_GET['id'];
    include_once("../config.php");
    $sql = "select value,kapian from config where title='$kk'";
    $requ = mysqli_query($con,$sql);
    $rs = mysqli_fetch_array($requ);
    $data = json_decode($rs['value'],true);
    $title = $data['title'];//卡片标题
    $bianma = $data['bianma'];
    $kapian = $rs['kapian'];
    mysqli_free_result($requ);
    $sql = "select a.zcbh as zcbh,a.xlh as xlh,
				  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
				  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
				  a.pp as pp,a.xh as xh,a.zcly as zcly,
				  a.zcjz as zcjz,a.gg as gg,
				  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
				  a.xsq as xsq,a.nc as nc,a.cw as cw,
				  zhuangtai.name as zczt,
				  danwei.name as bm,zclx.name as zclx 
				  from $zz as a 
				  left join zclx on a.zclx=zclx.id 
				  left join zhuangtai on a.zczt=zhuangtai.id 
				  left join danwei on a.bm=danwei.id 
				  where a.id=$id";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <title>hiprint.io</title>
    <link href="css/hiprint.css" rel="stylesheet" />
    <link href="css/print-lock.css" rel="stylesheet" />
    <link media="print" href="css/print-lock.css" rel="stylesheet" />
    <script src="plugins/jquery.min.js"></script>
</head>
<body>

    <a class="btn hiprint-toolbar-item " style="color: #fff;background-color: #d9534f;border-color: #d43f3a;" onclick="directPrint()">打印</a>
    <div id="hiprint-printTemplate" class="hiprint-printTemplate" style="margin-top:20px;margin-left:20px;"></div>
    
    
    <script src="hiprint.bundle.js"></script>
    <script src="custom_test/config-etype-provider.js"></script>
    <script src="polyfill.min.js"></script>
    <script src="plugins/jquery.hiwprint.js"></script>
    <script src="plugins/JsBarcode.all.min.js"></script>
    <script src="plugins/qrcode.js"></script>
    <script src="hiprint.config.js"></script>



    <script>
var printData = {
	zcbh: '<?php echo $rs['zcbh']; ?>',
	xlh: '<?php echo $rs['xlh']; ?>',
	zclx: '<?php echo $rs['zclx']; ?>',
	cw: '<?php echo $rs['cw']; ?>',
	zczt: '<?php echo $rs['zczt']; ?>',
	bm: '<?php echo $rs['bm']; ?>',
	bgr: '<?php echo $rs['bgr']; ?>',
	dz: '<?php echo $rs['dz']; ?>',
	cgsj: '<?php echo date('Y-m-d',$rs['cgsj']); ?>',
	rzsj: '<?php echo date('Y-m-d',$rs['rzsj']); ?>',
	zbsc: '<?php echo $rs['zbsc']; ?>年',
	sysc: '<?php echo $rs['sysc']; ?>年',
	pp: '<?php echo $rs['pp']; ?>',
	xh: '<?php echo $rs['xh']; ?>',
	zcly: '<?php echo $rs['zcly']; ?>',
	zcjz: '<?php echo $rs['zcjz']; ?>',
	gg: '<?php echo $rs['gg']; ?>',
	wlbs: '<?php echo $rs['wlbs']; ?>',
	ip: '<?php echo $rs['ip']; ?>',
	xsq: '<?php echo $rs['xsq']; ?>',
	yp: '<?php echo $rs['yp']; ?>G',
	nc: '<?php echo $rs['nc']; ?>G',
	ewm: '<?php echo $rs[$bianma]; ?>',
	txm: '<?php echo $rs[$bianma]; ?>',
	name: '<?php echo $title; ?>'
};

    var configPrintJson = <?php echo $kapian; ?>;
    
        var hiprintTemplate;
        $(document).ready(function () {
            //初始化打印插件
            hiprint.init({
                providers: [new configElementTypeProvider()]
            });
            hiprintTemplate = new hiprint.PrintTemplate({
                template: configPrintJson
            });
            //打印设计
            hiprintTemplate.design('#hiprint-printTemplate');
        });
        //直接调用浏览器的打印
        directPrint = function () {
            hiprintTemplate.print(printData);
        }
    </script>


</body>
</html>