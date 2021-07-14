<?php
	include_once("../config.php");
	$zz = $_GET['zz'];
	$cs = array("","xxzxauto","bgsauto","wgbauto");
	$cs = $cs[$zz];
	$sql = "select value from config where title='$cs'";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
	$ziz = $rs['value'];
	
	$kk = array("","xxkapian","bgskapian","wgbkapian");
	$kk = $kk[$zz];
	$sqll = "select value,kpyl from config where title='$kk'";
	$requu = mysqli_query($con,$sqll);
	$rss = mysqli_fetch_array($requu);
	$ka = $rss['value'];
	$html = $rss['kpyl'];
	$ka = json_decode($ka,true);
	
	$zd = array("","xinxizichan","bgszichan","wgbzichan");
	$zd = $zd[$zz];
	$ssql = "select COLUMN_NAME,COLUMN_COMMENT from information_schema.COLUMNS where table_name='$zd'";
	$reqq = mysqli_query($con,$ssql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>参数</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
		<form class="layui-form" action="">
			<div class="layui-form-item">
				<label class="layui-form-label">连续录入</label>
				<div class="layui-input-block">
					<input type="checkbox" <?php if($ziz){ echo 'checked=""'; } ?> name="open" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
					<span style="margin-left:20px;color:#a8a8a8;">开启后，资产录入时，会保留上次录入结果。</span>
				</div>
			</div>
		</form>
    </div>
</div>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	<legend>资产卡片设置</legend>
</fieldset>
<div class="layuimini-container">
    <div class="layuimini-main">
		<form class="layui-form" action="">		
            <div class="layui-form-item">
                <label class="layui-form-label">卡片名称</label>
                <div class="layui-input-block">
                    <input type="text" name="title" value="<?php echo $ka['title']; ?>" lay-verify="title" autocomplete="off" placeholder="请输入卡片名称" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">条码设置</label>
                <div class="layui-input-block">
                    <input type="radio" name="ma" value="0" title="条形码" <?php if($ka['ma'] == 0){echo 'checked=""';} ?>>
                    <input type="radio" name="ma" value="1" title="二维码" <?php if($ka['ma'] == 1){echo 'checked=""';} ?>>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">编码字段</label>
                <div class="layui-input-block">
                    <input type="radio" name="bianma" value="zcbh" title="资产编号" <?php if($ka['bianma'] == 'zcbh'){echo 'checked=""';} ?>>
                    <input type="radio" name="bianma" value="xlh" title="序列号" <?php if($ka['bianma'] == 'xlh'){echo 'checked=""';} ?>>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示字段</label>
                <div class="layui-input-block">
					<?php
						$zd = $ka['content'];
						if(!is_array($zd)){$zd=array();}
						while($rrs = mysqli_fetch_array($reqq)){
							if(in_array($rrs['COLUMN_NAME'],$zd)){$xz = 'checked=""';}else{$xz = '';}
							if(in_array($rrs['COLUMN_NAME'],['id','bz','ll','dotime','img'])) continue;
							echo '<input type="checkbox" '.$xz.' name="'.$rrs['COLUMN_NAME'].'" title="'.$rrs['COLUMN_COMMENT'].'">';
						}
					?>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
		</form>
    </div>
</div>
<!--资产卡片预览start-->
	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
		<legend>卡片预览</legend>
	</fieldset>
    <link href="/hiprint/css/hiprint.css" rel="stylesheet" />
    <link href="/hiprint/css/print-lock.css" rel="stylesheet" />
    <link media="print" href="/hiprint/css/print-lock.css" rel="stylesheet" />
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document" style="display: inline-block; width: auto;">
			<div class="modal-content">
				<div class="modal-body" style="background-color:#fff;">
					<?php
						echo $html;
					?>
				</div>
			</div>
		</div>
	</div>
<!--资产卡片预览end-->
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form
			,$ = layui.jquery
            , layer = layui.layer;

        //监听指定开关
        form.on('switch(switchTest)', function (data) {
			if(this.checked){
				var v = 1;
			}else{
				var v = 0;
			}
			console.log(v);
			$.post("../action.php",{mode:'xgcs',sjk:'<?php echo $cs; ?>',v:v},function(res){
				console.log(res);
				var r=JSON.parse(res);
				if(r.status==1){
					layer.tips('修改成功', data.othis);
				}else{
					layer.tips('修改失败', data.othis);
					location.reload();
				}
				
			});
        });

        form.on('submit(demo1)', function (data) {
			var a = data.field;
			var jg = {};
			jg['title'] = a["title"];
			delete a["title"];
			jg['ma'] = a["ma"];
			delete a["ma"];
			jg['bianma'] = a["bianma"];
			delete a["bianma"];
			var s = [];
			for (var key in a){
				s.push(key);
			}
			if(s.length > 10){
				    layer.alert('不得超过10项', {icon: 5});
					return false;
			}
			jg['content'] = s;
			console.log(JSON.stringify(jg));
			var v = JSON.stringify(jg);
			$.post("../action.php",{mode:'zckpsz',sjk:'<?php echo $kk; ?>',v:v},function(res){
				console.log(res);
				var r=JSON.parse(res);
				if(r.status==1){
				    layer.alert(r.msg + "<br>请设计资产卡片", {icon: 6},function () {
						location.href='/hiprint/edit.php?kk=<?php echo $kk; ?>';
					});
				}else{
				    layer.alert(r.msg, {icon: 5},function () {
						location.reload();
					});
				}
			});
            return false;
        });
    });
</script>

</body>
</html>