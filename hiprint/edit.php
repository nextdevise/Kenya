<?php
    $kk = $_GET['kk'];
    include_once("../config.php");
    $field = array('gender','like','email','address','phone','target','professional','university','universityAddress','universityDate');
    $sql = "select value from config where title='$kk'";
    $requ = mysqli_query($con,$sql);
    $rs = mysqli_fetch_array($requ);
    $value = $rs['value'];
    $d = json_decode($value,true);
    $zd = $d['content'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <title>hiprint.io</title>
    <link href="css/hinnn.css" rel="stylesheet" />
	<link href="plugins/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/document.css" rel="stylesheet" />
    <link href="css/hinnn.css" rel="stylesheet" />
    <link href="css/home.css" rel="stylesheet" />
    <link href="css/font-awesome-ie7.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/hiprint.css" rel="stylesheet" />
    <link href="css/print-lock.css" rel="stylesheet" />
	<link media="print" href="css/print-lock.css" rel="stylesheet" />
    <script src="plugins/jquery.min.js"></script>
</head>
<body>

<layout class="layout hinnn-layout hinnn-layout-has-sider height-100-per" style="background:#fff;min-width:1000px;">

    <content class="hinnn-layout-content" style="border-left:1px solid #e8e8e8; ">
        <div class="container-fluid height-100-per print-content">

            <div class="row">

                <div class="col-md-2 " style="padding-right:0px;">

                    <div class="small-printElement-types hiprintEpContainer">

                    </div>

                </div>
                <div class="col-md-10 ">
                    <div class="hiprint-toolbar" style="margin-top:15px;">
                        <ul>
                            <li><a class="hiprint-toolbar-item" onclick="setPaper('A4')">A4</a></li>
                            <li><a class="hiprint-toolbar-item" onclick="setPaper('A5')">A5</a></li>
                            <li><a class="hiprint-toolbar-item"><input type="text" id="customWidth" style="width: 50px;height: 19px;border: 0px;" value="80" placeholder="宽/mm" /></a></li>
                            <li><a class="hiprint-toolbar-item"><input type="text" id="customHeight" style="width: 50px;height: 19px;border: 0px;" value="40" placeholder="高/mm" /></a></li>

                            <li><a class="hiprint-toolbar-item" onclick="setPaper($('#customWidth').val(),$('#customHeight').val())">确定</a></li>
                            <li><a class="hiprint-toolbar-item" onclick="rotatePaper()">旋转</a></li>
                            <li><a class="hiprint-toolbar-item" onclick="clearTemplate()">清空</a></li>
                            <li>
                                <a class="btn hiprint-toolbar-item " style="color: #fff;
    background-color: #d9534f;
    border-color: #d43f3a;" onclick="preview()">预览</a>
                            </li>
                            <li>
                                <a class="btn hiprint-toolbar-item " style="color: #fff;
    background-color: #d9534f;
    border-color: #d43f3a;" onclick="directPrint()">打印测试</a>
                            </li>
                        </ul>
                    </div>
                    <div id="hiprint-printTemplate" class="hiprint-printTemplate" style="margin-top:20px;margin-left:20px;">

                    </div>
                    <div style="padding-top:15px;">
                        <button type="button" class="btn btn-primary" onclick="getJsonTid()"> 确 定 </button>
                    </div>
                </div>
            </div>
        </div>
    </content>
    <sider class="hinnn-layout-sider" style="">
        <div class="container height-100-per" style="padding-top:65px;width:278px;">
            <div class="row">
                <div class="col-sm-12">
                    <div id="PrintElementOptionSetting" style="margin-top:10px;"></div>
                </div>
            </div>
        </div>
    </sider>
</layout>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="display: inline-block; width: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">打印预览</h4>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-danger" onclick="printByHtml()">打印</button>
                <div class="prevViewDiv"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<div>



</div>

    
    <script src="plugins/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="plugins/jquery.nicescroll.min.js"></script>
    <script src="hiprint.bundle.js"></script>


<script type="text/javascript">
var configElementTypeProvider = (function () {
    return function (options) {
        var addElementTypes = function (context) {
            context.addPrintElementTypes(
                "testModule",
                [
                    new hiprint.PrintElementTypeGroup("标题", [
                        {
                            tid: "configModule.name", title: "卡片名称", field: "name", data: "资产卡片", type: "text",
                            "options": {
                                "height": 28,
                                "width": 150,
                                "fontSize": 15,
                                "fontWeight": "600",
                                "textAlign": "center",
                                "lineHeight": 28,
                                "hideTitle": true,
                                "field": "name",
                                "testData": "资产卡片"
                            }
                        }
                    ]),
                    new hiprint.PrintElementTypeGroup("选项", [
<?php 
$i=0;
foreach($zd as $v){
    switch($v){
        case 'zcbh':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "资产编号", type: "text",
							"options":{
								title: "资产编号", 
								field: "zcbh", 
								testData:"A202010104321"
							}
						},
            ';
        break;
        case 'xlh':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "序列号", type: "text",
							"options":{
								title: "序列号", 
								field: "xlh", 
								testData:"78dGn9_431A"
							}
						},
            ';
        break;
        case 'zclx':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "资产类型", type: "text",
							"options":{
								title: "资产类型", 
								field: "zclx", 
								testData:"台式计算机"
							}
						},
            ';
        break;
        case 'cw':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "财务资产名", type: "text",
							"options":{
								title: "财务资产名", 
								field: "cw", 
								testData:"联想台式机"
							}
						},
            ';
        break;
        case 'zczt':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "资产状态", type: "text",
							"options":{
								title: "资产状态", 
								field: "zczt", 
								testData:"在用"
							}
						},
            ';
        break;
        case 'bm':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "所属单位", type: "text",
							"options":{
								title: "所属单位", 
								field: "bm", 
								testData:"信息中心"
							}
						},
            ';
        break;
        case 'bgr':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "保管人", type: "text",
							"options":{
								title: "保管人", 
								field: "bgr", 
								testData:"李文明"
							}
						},
            ';
        break;
        case 'dz':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "保存地址", type: "text",
							"options":{
								title: "保存地址", 
								field: "dz", 
								testData:"滨海办公区407"
							}
						},
            ';
        break;
        case 'cgsj':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "采购时间", type: "text",
							"options":{
								title: "采购时间", 
								field: "cgsj", 
								testData:"2020-01-01"
							}
						},
            ';
        break;
        case 'rzsj':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "入账时间", type: "text",
							"options":{
								title: "入账时间", 
								field: "rzsj", 
								testData:"2020-10-10"
							}
						},
            ';
        break;
        case 'zbsc':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "质保时长", type: "text",
							"options":{
								title: "质保时长", 
								field: "zbsc", 
								testData:"3年"
							}
						},
            ';
        break;
        case 'sysc':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "报废年限", type: "text",
							"options":{
								title: "报废年限", 
								field: "sysc", 
								testData:"6年"
							}
						},
            ';
        break;
        case 'pp':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "品牌", type: "text",
							"options":{
								title: "品牌", 
								field: "pp", 
								testData:"联想"
							}
						},
            ';
        break;
        case 'xh':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "型号", type: "text",
							"options":{
								title: "型号", 
								field: "xh", 
								testData:"昭阳E40"
							}
						},
            ';
        break;
        case 'gg':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "规格", type: "text",
							"options":{
								title: "规格", 
								field: "gg", 
								testData:"规格"
							}
						},
            ';
        break;
        case 'zcly':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "资产来源", type: "text",
							"options":{
								title: "资产来源", 
								field: "zcly", 
								testData:"自购"
							}
						},
            ';
        break;
        case 'zcjz':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "资产价值", type: "text",
							"options":{
								title: "资产价值", 
								field: "zcjz", 
								testData:"￥5000.00"
							}
						},
            ';
        break;
        case 'wlbs':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "网络标识", type: "text",
							"options":{
								title: "网络标识", 
								field: "wlbs", 
								testData:"内网"
							}
						},
            ';
        break;
        case 'ip':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "IP", type: "text",
							"options":{
								title: "IP", 
								field: "ip", 
								testData:"76.40.118.118"
							}
						},
            ';
        break;
        case 'xsq':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "显示器", type: "text",
							"options":{
								title: "显示器", 
								field: "xsq", 
								testData:"惠普21.5"
							}
						},
            ';
        break;
        case 'yp':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "硬盘", type: "text",
							"options":{
								title: "硬盘", 
								field: "yp", 
								testData:"1000G"
							}
						},
            ';
        break;
        case 'nc':
            echo '
                        { tid: "configModule.'.$field[$i].'", title: "内存", type: "text",
							"options":{
								title: "内存", 
								field: "nc", 
								testData:"4G"
							}
						},
            ';
        break;
    }
    $i++;
}
?>
                    ]),
                    new hiprint.PrintElementTypeGroup("条码", [
<?php
if($d['ma'] == 1){echo '
                        {
                            tid: "configModule.mySite", field: "ewm", title: "QrCode",  text: "二维码", 
                            "options": {
                                "height": 50,
                                "width": 50,
                                "fontSize": 19,
                                "fontWeight": "700",
                                "textAlign": "center",
                                "lineHeight": 39,
                                "hideTitle": true,
                                "textType": "qrcode",
								"field": "ewm",
								"title":"123",
								"testData": "123"
                            }
                        }
';}else{echo '
                        {tid: "configModule.barcode", field: "txm", text: "条形码", title:"BarCode", 
                        "options": {
                            "width": 150, 
                            "height": 15,
                            "textAlign": "center", 
                            "textType": "barcode",
                            "fontFamily":"Microsoft YaHei",
                            "hideTitle":"true",
                            "barcodeMode":"CODE128",
							"field": "txm",
							"title":"321",
							"testData": "321"
                            }
                        }
';}
?>
                    ])
                ]
            );
        };
        return {
            addElementTypes: addElementTypes
        };
    };
})();
</script>

    
    <script src="custom_test/config-print-json.js"></script>
    <script src="custom_test/config-print-data.js"></script>
    <script src="polyfill.min.js"></script>
    <script src="plugins/jquery.hiwprint.js"></script>
    <script src="plugins/JsBarcode.all.min.js"></script>
    <script src="plugins/qrcode.js"></script>
    <script src="plugins/jquery.minicolors.min.js"></script>


<script src="hiprint.config.js"></script>
    <script>
        var hiprintTemplate;
        $(document).ready(function () {
            $(".print-content").niceScroll();
            //初始化打印插件
            hiprint.init({
                providers: [new configElementTypeProvider()]
            });

            //hiprint.PrintElementTypeManager.build('.hiprintEpContainer', 'testModule');
            //设置左侧拖拽事件
            hiprint.PrintElementTypeManager.build('.hiprintEpContainer', 'testModule');

            hiprintTemplate = new hiprint.PrintTemplate({
                template: configPrintJson,
                settingContainer: '#PrintElementOptionSetting',
                paginationContainer: '.hiprint-printPagination'
            });
            //打印设计
            hiprintTemplate.design('#hiprint-printTemplate');
        });

        //预览
        var preview = function () {
            $('#myModal .modal-body').html(hiprintTemplate.getHtml(printData))
            $('#myModal').modal('show')
        }

        //调整纸张
        var setPaper = function (paperTypeOrWidth, height) {
            hiprintTemplate.setPaper(paperTypeOrWidth, height);
        }
        //旋转
        var rotatePaper = function () {
            hiprintTemplate.rotatePaper();
        }
        var clearTemplate = function () {
            hiprintTemplate.clear();
        }

        var getJsonTid = function () {
			var html = hiprintTemplate.getHtml(printData)[0].outerHTML;
			console.log(html);
            var ka = JSON.stringify(hiprintTemplate.getJsonTid());
            $.post("write.php",{html:html,ka:ka,kk:'<?php echo $kk; ?>'},function(res){
				console.log(res);
                if(res==1){
                    alert("修改成功");
                }else{
                    alert("修改失败");
                }
            });
        }


        //预览
        var jsonPreview = function () {
            var testTemplate = new hiprint.PrintTemplate({ template: JSON.parse($('#textarea').val()) });

            $('#myModal .modal-body .prevViewDiv').html(testTemplate.getHtml(printData))
            $('#myModal').modal('show')

        }


        //直接调用浏览器的打印
        directPrint = function () {
            hiprintTemplate.print(printData);
        }

        var printByHtml = function () {
            hiprintTemplate.printByHtml($('#myModal .modal-body .prevViewDiv'));
        }
    </script>


</body>
</html>