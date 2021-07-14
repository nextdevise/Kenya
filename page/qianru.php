<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数据迁入</title>
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <blockquote class="layui-elem-quote">
            <span style="color:#f00;">使用此功能前请做好数据备份！</span><br>
            请选择“数据迁出”功能生成的ZIP文件。<br>
            Zip文件不得编辑、重命名，否则可能造成数据错误。<br>
            文件最大不得超过50M。
        </blockquote>
        <form class="layui-form" action="">
			<div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">上传文件</label>
					<button type="button" class="layui-btn layui-btn-primary" id="upload4"><i class="layui-icon"></i>Zip文件</button>
					<input type="hidden" name="upifle" id="upifle" />
				</div>
			</div>
			<input type="hidden" name="oldname" id="oldname" />
			<input type="hidden" id="sjk" name="sjk" value="<?php echo $_GET['sjk']; ?>" />
			<div class="layui-form-item">
				<div class="layui-input-block">
					<button class="layui-btn" lay-submit="" lay-filter="demo1">迁入</button>
				</div>
			</div>
		</form>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'upload'], function () {
        var form = layui.form
            , layer = layui.layer
			, upload = layui.upload;

		  upload.render({
			elem: '#upload4'
			,url: '/upzip.php'
			,accept: 'file'
			,exts: 'zip'
			,choose: function(obj){
			    //取原始文件名
                var files = obj.pushFile();
                obj.preview(function(index,file,result){
                    console.log(file.name);
                    $("#oldname").val(file.name);
                })
            }
			,done: function(res){
				if(res[0].status==1){
					layer.alert('上传成功，点击“迁入”按钮完成操作。');
					$("#upifle").val(res[0].file);
				}else{
					layer.alert(res[0].msg);
				}
			}
		  });
        form.on('submit(demo1)', function (data) {
			var f = $("#upifle").val();
			var q = $("#sjk").val();
			var o = $("#oldname").val();
			if(f == ''){
				layer.alert("请上传文件");
				return false;
			}
			if(q == ''){
				layer.alert("参数错误");
				return false;
			}
			$.post("../action.php?mode=qianru",{q:q,f:f,o:o},function(result){
				console.log(result);
				$("#oldname").val('');
				$("#upifle").val('');
				var index = layer.alert('迁入完成,请检查数据是否正确。', {
					title: '信息'
				}, function () {
					// 关闭弹出层
					layer.close(index);
					var iframeIndex = parent.layer.getFrameIndex(window.name);
					parent.layer.close(iframeIndex);
					//parent.location.reload();  
					parent.layui.table.reload('zichanbiao', {
						url: '../action.php?mode=searchzichandemo&dw=1'
					});
				});
			});
            return false;
        });

	});
</script>
</body>
</html>