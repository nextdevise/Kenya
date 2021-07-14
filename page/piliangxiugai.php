<?php
include_once("../config.php");
$do = $_GET['do'];
$id = $_GET['id'];
$bmz = $_SESSION['bm'];
if($bmz == 0){
	$bmz = '';
}else{
	$bmz = " and id in ($bmz)";
}
if($do == 'zt'){
	$sql = "select id,name from zhuangtai where status=1";
	$s = "状态";
}else{
	$sql = "select id,name from danwei where status=1 $bmz";
	$s = "部门";
}
$requ = mysqli_query($con,$sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>批量修改</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layui-form layuimini-form">
	<div class="layui-form-item">
		<label class="layui-form-label">选择新<?php echo $s; ?></label>
		<div class="layui-input-block">
			<select name="juse">
				<?php 
					while($rs = mysqli_fetch_array($requ)){
						echo '<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
					}
				?>
			</select>
		</div>
	</div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="saveBtn">确认保存</button>
        </div>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>

    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
			$.post("../action.php?mode=plxg",{v:data.field.juse,cz:'<?php echo $do; ?>',id:'<?php echo $id; ?>'},function(res){
				console.log(res);
				var r = JSON.parse(res);
				if(r.status==1){
					var index = layer.alert('修改完成', {
						title: '信息'
					}, function () {
						// 关闭弹出层
						layer.close(index);
						var iframeIndex = parent.layer.getFrameIndex(window.name);
						parent.layer.close(iframeIndex);
						parent.layui.table.reload('zichanbiao', {
							url: '../action.php?mode=searchzichandemo&dw=1'
						});
					});
				}else{
					layer.msg(r.msg);
				}
			});
            return false;
        });

    });
</script>
</body>
</html>