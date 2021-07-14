<?php
	include_once('../config.php');
	
	if(!isset($_SESSION['admin'])){//如果未登录，放回登录页面
		header("location:/page/login.php");
		die();
	}
	
	if(isset($_GET['rzqi'])){//查询入账日期起
		$rzqi = $_GET['rzqi'];
	}else{
		$rzqi = '';
	}
	if(isset($_GET['rzzhi'])){//查询入账日期止
		$rzzhi = $_GET['rzzhi'];
	}else{
		$rzzhi = '';
	}
	if(isset($_GET['mhss'])){//查询模糊搜索
		$mhss = $_GET['mhss'];
	}else{
		$mhss = '';
	}
	$bm = $_SESSION['bm'];//权限部门
	if($bm == 0){
		$bm = '';
	}else{
		$bm = " and id in ($bm)";
	}
	$shanchu = $_SESSION['shanchu'];//删除权限
	$xiugai = $_SESSION['xiugai'];//修改权限
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>资产查询</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="/js/lay-module/soulTable.css" media="all">
	<link rel="stylesheet" href="../lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
	<!--Print Start-->
    <link href="/hiprint/css/hiprint.css" rel="stylesheet" />
    <link href="/hiprint/css/print-lock.css" rel="stylesheet" />
    <link media="print" href="/hiprint/css/print-lock.css" rel="stylesheet" />
    <script src="/hiprint/plugins/jquery.min.js"></script>
    <script src="/hiprint/hiprint.bundle.js"></script>
    <script src="/hiprint/custom_test/config-etype-provider.js"></script>
    <script src="/hiprint/polyfill.min.js"></script>
    <script src="/hiprint/plugins/jquery.hiwprint.js"></script>
    <script src="/hiprint/plugins/JsBarcode.all.min.js"></script>
    <script src="/hiprint/plugins/qrcode.js"></script>
    <script src="/hiprint/hiprint.config.js"></script>
    <?php
        $sql = "select value,kapian from config where title='xxkapian'";
        $requ = mysqli_query($con,$sql);
        $rs = mysqli_fetch_array($requ);
        $data = json_decode($rs['value'],true);
        $title = $data['title'];//卡片标题
        $bianma = $data['bianma'];//使用编号还是序列号编码
        $kapian = $rs['kapian'];//打印模板json
        mysqli_free_result($requ);
    ?>
    <script>
        var configPrintJson = <?php echo $kapian; ?>;
            var hiprintTemplate;
            hiprint.init({
                providers: [new configElementTypeProvider()]
            });
            hiprintTemplate = new hiprint.PrintTemplate({
                template: configPrintJson
            });
    </script>
	<!--Print End-->
	<script>
		function showimg(t) {
			layer.open({
				type: 1,
				title: false,
				closeBtn: 0,
				area: '680px',
				skin: 'layui-layer-nobg',
				shadeClose: true,
				content: '<img style="display: inline-block; width: 100%; height: 100%;" src="'+t+'">'
			});
		}
	</script>
	<style>
        .layui-table-cell{
            height:auto !important;
        }
	</style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
						<div class="layui-inline">
							<label class="layui-form-label">入账时间</label>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="rzqi" id="rzqi" value="<?php echo $rzqi; ?>" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid">-</div>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="rzzhi" id="rzzhi" value="<?php echo $rzzhi; ?>" autocomplete="off" class="layui-input">
							</div>
						</div>
                        <div class="layui-inline">
                            <label class="layui-form-label">模糊查询</label>
                            <div class="layui-input-inline">
                                <input type="text" id="mhss" name="mhss" value="<?php echo $mhss; ?>" placeholder="编号、地址等模糊查询" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary" lay-submit  lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
		

		
		
		<script type="text/html" id="toolbarDemo">
		  <div class="layui-btn-container">
<?php if($shanchu){ //删除权限 ?>
			<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="getSelected" title="删除选中的资产">删除资产</button>
<?php } ?>
			<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="dayinkapian" title="打印选中的资产卡片">打印卡片</button>
			<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="qianchu" title="将选中的资产打包迁出">数据迁出</button>
<?php if($xiugai){ //修改权限 ?>
			<button class="layui-btn layui-btn-sm" lay-event="qianru" title="将迁出的资产迁入系统">数据迁入</button>
			<button class="layui-btn layui-btn-sm" lay-event="xgzhuangtai" style="background-color:#7c27ee;" title="批量修改选中资产的状态">批改状态</button>
			<button class="layui-btn layui-btn-sm" lay-event="xgfenzu" style="background-color:#051cec;" title="批量修改选中资产所属单位">批改分组</button>
<?php } ?>
		  </div>
		</script>
		
		<script type="text/html" id="simggs">
			{{# if(d.img != ''){ }}
				<img src="{{d.img}}" style="width:60px;height:25px;" onclick="showimg('{{d.img}}');" />
			{{# } }}
		</script>
		<script type="text/html" id="switchTpl">
			<?php if($xiugai){ ?>
			<i lay-event="baocunxiugai" title="保存修改" class="fa fa-floppy-o"></i>
			&nbsp;&nbsp;
			<i lay-event="xiugai" title="编辑资产" class="fa fa-edit"></i>
			<?php } ?>
			&nbsp;&nbsp;
			<i lay-event="rizhi" title="资产履历" class="fa fa-list-alt"></i>
			&nbsp;&nbsp;
			<i lay-event="kapian" title="资产卡片" class="fa fa-credit-card"></i>
		</script>
        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>
    layui.use(['form', 'table', 'soulTable', 'laydate', 'tableEdit'], function () {
        var $ = layui.jquery,//jQuery 
            form = layui.form,//表单
			soulTable = layui.soulTable,//表格拓展
			laydate = layui.laydate,//日期
			tableEdit = layui.tableEdit,//表格编辑
            table = layui.table;//表格
			
		laydate.render({
            elem: '#rzqi'
        });
		laydate.render({
            elem: '#rzzhi'
        });
		
		<?php  
			$sql = "select id,name from zclx where zcfl=1";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'"},';
			}
			$z = rtrim($z,',');
			echo "var zclxarr = [$z];";
		?>
		<?php  
			$sql = "select id,name,icon,color from zhuangtai where 1=1";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'",icon:"'.$rs['icon'].'",color:"'.$rs['color'].'"},';
			}
			$z = rtrim($z,',');
			echo "var zcztarr = [$z];";
		?>
		<?php  
			$sql = "select id,name from danwei where 1=1 $bm";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'"},';
			}
			$z = rtrim($z,',');
			echo "var bmarr = [$z];";
		?>
		var wlbsarr = [{name:0,value:"未指定"},{name:1,value:"内网"},{name:2,value:"外网"}];

        var cols = table.render({
            elem: '#currentTableId',//绑定表
            url: '../action.php?mode=searchzichandemo&dw=1',//接口
			where: {rzqi:'<?php echo $rzqi; ?>',rzzhi:'<?php echo $rzzhi; ?>',mhss:'<?php echo $mhss; ?>'},//参数
            toolbar: '#toolbarDemo',//表格工具
			id: 'zichanbiao',
            defaultToolbar: ['filter', 'exports', 'print', {//右上角按钮
                title: '导出全部',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-download-circle'
            },{
				title: '导出本次查询结果',
				layEvent: 'export_now',
				icon: 'layui-icon-triangle-d'
			},{
				title: '帮助',
				layEvent: 'Show_Help',
				icon: 'layui-icon-read'
			}]
			,height:'full-200'
			,overflow: {//内容超出表格设置
				type: 'tips'
				,hoverTime: 30 // 悬停时间，单位ms, 悬停 hoverTime 后才会显示，默认为 0
				,color: 'white' // 字体颜色
				,bgColor: 'blue' // 背景色
				,minWidth: 100 // 最小宽度
				,maxWidth: 500 // 最大宽度
			}
			,contextmenu: {
				// 表头右键菜单配置
				header: [
					{
						name: '重载表格',
						icon: 'layui-icon layui-icon-refresh-1',
						click: function() {
							table.reload(this.id)
						}
					}
				],
				// 表格内容右键菜单配置
				body: [
					{
					   name: '复制',
					   icon: 'layui-icon layui-icon-template',
					   click: function(obj) {
						   soulTable.copy(obj.text)
						   layer.msg('复制成功！') 
					   }
					},
					{
						name: '标记行',
						icon: 'layui-icon layui-icon-rate-half',
						click: function(obj) {
							obj.trElem.css('background', '#01AAED')
							obj.trElem.css('color', 'white')
						}
					}
				]
			}
			,rowDrag: {trigger: 'row', done: function(obj) {}},//拖拽行
            cols: [[
                {type: "checkbox", width: 50, fixed: "left"},
                {field: 'id', width: 80, title: 'ID', sort: true, align: "center"},
                {field: 'zclx', width: 120, title: '资产类型', align: "center", filter: true, event:'zclx',config:{type:'select',data:zclxarr}
					,templet:function (d) {
                        if(d.zclx){
                            if(d.zclx.value){
                                return  d.zclx.value;
                            }
                            return  d.zclx;
                        }
                        return ''
                    }
				},
				{field: 'zcbh', width: 150, title: '资产编号', align: "center", event:'zcbh', config:{type:'input'}},
				{field: 'xlh', width: 150, title: '序列号', align: "center", event:'xlh', config:{type:'input'}},
				{field: 'cw', width: 150, title: '财务资产名', align: "center", event:'cw', filter: true, config:{type:'input'}},
				{field: 'zczt', width: 120, title: '资产状态', sort: true, align: "center", filter: true, event:'zczt',config:{type:'select',data:zcztarr}
					,templet:function (d) {
                        if(d.zczt){
                            if(d.zczt.value){
                                return  '<i style="color:'+d.zczt.color+';" class="fa '+d.zczt.icon+'">'+d.zczt.value+'</i>';
                            }
                            return  d.zczt;
                        }
                        return ''
                    }
				},
				{field: 'bm', width: 120, title: '所属单位', align: "center", filter: true, event:'bm',config:{type:'select',data:bmarr}
					,templet:function (d) {
                        if(d.bm){
                            if(d.bm.value){
                                return  d.bm.value;
                            }
                            return  d.bm;
                        }
                        return ''
                    }
				},
				{field: 'bgr', width: 100, title: '责任人', align: "center", event:'bgr', config:{type:'input'}},
				{field: 'dz', width: 120, title: '存放地点', align: "center", event:'dz', config:{type:'input'}},
				{field: 'pp', width: 110, title: '品牌', align: "center", sort: true, filter: true, event:'pp', config:{type:'input'}},
				{field: 'xh', width: 100, title: '型号', align: "center", sort: true, event:'xh', config:{type:'input'}},
				{field: 'gg', width: 90, title: '规格', align: "center", sort: true, event:'gg', config:{type:'input'}},
				{field: 'cgsj', width: 140, title: '采购时间', align: "center", sort: true, event:'cgsj', config:{type:'date',dateType:'date'}},
				{field: 'rzsj', width: 140, title: '入账时间', align: "center", sort: true, event:'rzsj', config:{type:'date',dateType:'date'}},
				{field: 'zcly', width: 100, title: '资产来源', align: "center", sort: true, event:'zcly', config:{type:'input'}},
				{field: 'zcjz', width: 100, title: '资产价值', align: "center", event:'zcjz', config:{type:'signedInput'}},
				{field: 'zbsc', width: 100, title: '质保时长', align: "center", sort: true, event:'zbsc', config:{type:'signedInput'}},
				{field: 'sysc', width: 100, title: '报废年限', align: "center", sort: true, event:'sysc', config:{type:'signedInput'}},
				{field: 'img', width: 100, title: '资产图片', align: "center", templet: "#simggs"},
				{field: 'wlbs', width: 110, title: '网络标识', align: "center", sort: true, event:'wlbs',config:{type:'select',data:wlbsarr}
					,templet:function (d) {
                        if(d.wlbs){
                            if(d.wlbs.value){
                                return  d.wlbs.value;
                            }
                            return  d.wlbs;
                        }
                        return ''
                    }
				},
				{field: 'ip', width: 120, title: 'IP', align: "center", event:'ip', config:{type:'input'}},
				{field: 'xsq', width: 100, title: '显示器', align: "center", event:'xsq', config:{type:'input'}},
				{field: 'yp', width: 80, title: '硬盘', align: "center", sort: true, event:'yp', config:{type:'signedInput'}},
				{field: 'nc', width: 80, title: '内存', align: "center", sort: true, event:'nc', config:{type:'signedInput'}},
				{field: 'bz', width: 200, title: '备注', align: "center", event:'bz', config:{type:'input'}},
                {field: 'dotime', width: 200, title: '最后操作时间', align: "center", sort: true},
				{fixed: 'right', width: 150, title: '操作', templet: '#switchTpl', align: "center"}
            ]],
            limits: [10, 15, 20, 30, 50, 100],
            limit: 10,
            page: true
			  ,autoColumnWidth: {//宽自动
			  	//init: true
			  }
			  ,filter: {//筛选
				items:['data']
				,cache: true
				,bottom: false 
			  }
			  ,done: function () {
				soulTable.render(this)
			  }
        }).config.cols;
		
		var aopTable = tableEdit.aopObj(cols);

		table.on('toolbar(currentTableFilter)', function(obj){
			var checkStatus = table.checkStatus(obj.config.id);
			switch(obj.event){
				case 'LAYTABLE_TIPS':
					location.href="../action.php?mode=downloadzclist&zz=xinxizichan";
				break;
				case 'export_now':
					location.href="../action.php?mode=downloadnow";
				break;
				case 'getSelected':
					var data = checkStatus.data;
					if(data.length > 0){
						var s="";
						for(var i=0;i<data.length;i++){
							s = s + data[i].id + ",";
						}
						s = s.substr(s,s.length - 1);
						layer.confirm('确定要删除吗？', {
							btn: ['确定','取消'], //按钮
							title: '删除询问'
						}, function(){
							layer.confirm('删除后将无法恢复！', {
								btn: ['知道了','吓死我了'], //按钮
								title: '删除询问'
							}, function(){
								layer.confirm('再考虑考虑？', {
									btn: ['好','不'], //按钮
									title: '询问'
								}, function(){
									//考虑考虑，不删了 
									layer.closeAll();
								}, function(){
									$.post("../action.php",{mode:'deletezichan',sjk:'xinxizichan',id:s},function(result){
										console.log(result);
										var r=JSON.parse(result);
										if(r.status==1){
											layer.alert('删除成功', {icon: 1});
											table.reload('zichanbiao', {
												url: '../action.php?mode=searchzichandemo&dw=1'
											});
										}else{
											layer.alert('删除失败', {icon: 2});
										}
									});
								});
							}, function(){
								//取消删除
							});
						}, function(){
							//取消删除
						});
					}
				break;
				case 'Show_Help':
					layer.open({
						type: 1,
						title: false,
						closeBtn: 0,
						area: '680px',
						skin: 'layui-layer-nobg',
						shadeClose: true,
						content: '<h1 style="margin:auto;text-align:center;color:#fff;">没有</h1>'
					});
				break;
				case 'dayinkapian':
				    //<!--Print Start-->
					var data = checkStatus.data;
					console.log(data);
					if(data.length > 0){
					    var printData = [];
						for(var i=0;i<data.length;i++){
						    printData.push({});
						    var d = data[i];
                            printData[i] = {
                            	zcbh: d.zcbh,
                            	xlh: d.xlh,
                            	zclx: d.zclx.value,
                            	cw: d.cw,
                            	zczt: d.zczt.value,
                            	bm: d.bm.value,
                            	bgr: d.bgr,
                            	dz: d.dz,
                            	cgsj: d.cgsj,
                            	rzsj: d.rzsj,
                            	zbsc: d.zbsc + '年',
                            	sysc: d.sysc + '年',
                            	pp: d.pp,
                            	xh: d.xh,
                            	zcly: d.zcly,
                            	zcjz: d.zcjz,
                            	gg: d.gg,
                            	wlbs: d.wlbs.value,
                            	ip: d.ip,
                            	xsq: d.xsq,
                            	yp: d.yp + 'G',
                            	nc: d.nc + 'G',
                            	ewm: <?php if($bianma == "zcbh"){echo "d.zcbh";}else{echo "d.xlh";} ?>,
                            	txm: <?php if($bianma == "zcbh"){echo "d.zcbh";}else{echo "d.xlh";} ?>,
                            	name: '<?php echo $title; ?>'
                            };
						    
						}
					    hiprintTemplate.print(printData);
					}
				break;
				case 'qianchu':
				    var data = checkStatus.data;
					if(data.length > 0){
					    var index = layer.load(0, {shade: false});
						var s="";
						for(var i=0;i<data.length;i++){
							s = s + data[i].id + ",";
						}
						s = s.substr(s,s.length - 1);
                        location.href="../action.php?mode=qianchu&sjk=xinxizichan&id=" + s;
                        layer.close(index);
					}
				break;
				case 'qianru':
                    layer.open({
                        title: '数据迁入',
                        type: 2,
                        shade: 0.2,
                        maxmin:true,
                        shadeClose: true,
                        area: ['680px', '420px'],
                        content: '/page/qianru.php?sjk=xinxizichan',
                    });
				break;
				case 'xgzhuangtai':
					var data = checkStatus.data;
					if(data.length > 0){
						var s="";
						for(var i=0;i<data.length;i++){
							s = s + data[i].id + ",";
						}
						s = s.substr(s,s.length - 1);
						layer.open({
							title: '批量修改',
							type: 2,
							shade: 0.2,
							maxmin:true,
							shadeClose: true,
							area: ['680px', '420px'],
							content: '/page/piliangxiugai.php?do=zt&id=' + s,
						});
					}
				break;
				case 'xgfenzu':
					var data = checkStatus.data;
					if(data.length > 0){
						var s="";
						for(var i=0;i<data.length;i++){
							s = s + data[i].id + ",";
						}
						s = s.substr(s,s.length - 1);
							layer.open({
								title: '批量修改',
								type: 2,
								shade: 0.2,
								maxmin:true,
								shadeClose: true,
								area: ['680px', '420px'],
								content: '/page/piliangxiugai.php?do=fz&id=' + s,
							});
					}
				break;
			};
		});
		
        aopTable.on('tool(currentTableFilter)', function (obj) {
            var id = obj.data.id;
			console.log(id);
            if (obj.event === 'xiugai') {
                layer.open({
                    title: '编辑资产',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: '/page/editxxzichan.php?id='+id,
                });
                return false;
            }else if(obj.event === 'kapian'){
                //<!--Print Start-->
                console.log(obj.data)
                var printData = {
                	zcbh: obj.data.zcbh,
                	xlh: obj.data.xlh,
                	zclx: obj.data.zclx.value,
                	cw: obj.data.cw,
                	zczt: obj.data.zczt.value,
                	bm: obj.data.bm.value,
                	bgr: obj.data.bgr,
                	dz: obj.data.dz,
                	cgsj: obj.data.cgsj,
                	rzsj: obj.data.rzsj,
                	zbsc: obj.data.zbsc + '年',
                	sysc: obj.data.sysc + '年',
                	pp: obj.data.pp,
                	xh: obj.data.xh,
                	zcly: obj.data.zcly,
                	zcjz: obj.data.zcjz,
                	gg: obj.data.gg,
                	wlbs: obj.data.wlbs.value,
                	ip: obj.data.ip,
                	xsq: obj.data.xsq,
                	yp: obj.data.yp + 'G',
                	nc: obj.data.nc + 'G',
                	ewm: <?php if($bianma == "zcbh"){echo "obj.data.zcbh";}else{echo "obj.data.xlh";} ?>,
                	txm: <?php if($bianma == "zcbh"){echo "obj.data.zcbh";}else{echo "obj.data.xlh";} ?>,
                	name: '<?php echo $title; ?>'
                };
                hiprintTemplate.print(printData);
                return false;
			}else if (obj.event === 'rizhi') {
                layer.open({
                    title: '资产履历',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: '/page/zichanlvli.php?zz=xinxizichan&id='+id,
                });
                return false;
            } else if (obj.event === 'baocunxiugai') {
                var data = obj.data;
				//layer.alert(JSON.stringify(data));
				var zclx = data.zclx.name;
				var zczt = data.zczt.name;
				var bm = data.bm.name;
				var wlbs = data.wlbs.name;
				data.zclx=zclx;
				data.zczt=zczt;
				data.bm=bm;
				data.wlbs=wlbs;
				var d = JSON.stringify(data);
				console.log(d);
				
				d=d.replace(/\'/g,"’");
				$.post("../action.php?mode=xiugaixxzxzc",{id:data.id,zz:1,data:d},function(result){
					console.log(result);
					var r = JSON.parse(result);
					if(r.status==1){
						var index = layer.alert('修改成功',function () {
							layer.close(index);
						});
					}else{
						layer.alert(r.msg);
					}
				});
                return false;
            }else{
				<?php if($xiugai){ ?>
				var field = obj.field; //单元格字段
				var value = obj.value; //修改后的值
				var data = obj.data; //当前行旧数据
				var event = obj.event; //当前单元格事件属性值
				console.log("单元格字段",field,"修改后的值",value,"当前行旧数据",data,"事件",event);
				if(field == 'zcjz' || field == 'zbsc' || field == 'sysc' || field == 'yp' || field == 'nc'){
					if(isNaN(value)){
						layer.msg('数值非法');
					}else{
						var update = {};
						update[field] = value;
						//把value更新到行中
						obj.update(update);
					}
				}else{
					if(field == 'zczt'){//如果要修改的是资产状态
						var ztid = value.name;//取新的资产状态ID
						for(var j=0;j<zcztarr.length;j++){
							if(zcztarr[j].name == ztid){//从资产状态数组中取新状态的图标和颜色
								value['icon'] = zcztarr[j].icon;
								value['color'] = zcztarr[j].color;
							}
						}
						var update = {};
						update[field] = value;
						//把value更新到行中
						obj.update(update);
					}else{
						var update = {};
						update[field] = value;
						//把value更新到行中
						obj.update(update);
					}
				}
				layer.msg('修改后记得点击“操作”栏的“保存修改”。');
				<?php  } ?>
			}
        });
    });
</script>
</body>
</html>