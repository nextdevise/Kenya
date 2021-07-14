<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>备份</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="/js/lay-module/soulTable.css" media="all">
	<link rel="stylesheet" href="../lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
	<style>
        .layui-table-cell{
            height:auto !important;
        }
	</style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
		<script type="text/html" id="toolbarDemo">
		  <div class="layui-btn-container">
			<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="backupdb">备份数据库</button>
			<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="backupfile">备份文件</button>
		  </div>
		</script>
		<script type="text/html" id="switchTpl">
			<i lay-event="shanchu" title="删除" class="fa fa-recycle"></i>
			{{# if(d.type == '数据库'){ }}
			&nbsp;&nbsp;
			<i lay-event="huifu" title="恢复" class="fa fa-mail-reply-all"></i>
			&nbsp;&nbsp;
			<i lay-event="xiazai" title="下载" class="fa fa-download"></i>
			{{# } }}
		</script>
        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>
    layui.use(['form', 'table','soulTable'], function () {
        var $ = layui.jquery,//jQuery 
            form = layui.form,//表单
			soulTable = layui.soulTable,//表格拓展
            table = layui.table;//表格


        var cols = table.render({
            elem: '#currentTableId',//绑定表
            url: '../action.php?mode=getbackupfile',//接口
			where: {},//参数
            toolbar: '#toolbarDemo',//表格工具
			id: 'zichanbiao',
            defaultToolbar: ['filter', 'exports', 'print']
			,height:'full-200'
			,overflow: {//内容超出表格设置
				type: 'tips'
				,hoverTime: 30 // 悬停时间，单位ms, 悬停 hoverTime 后才会显示，默认为 0
				,color: 'white' // 字体颜色
				,bgColor: 'blue' // 背景色
				,minWidth: 100 // 最小宽度
				,maxWidth: 500 // 最大宽度
			}
			,rowDrag: {trigger: 'row', done: function(obj) {}},//拖拽行
            cols: [[
                //{type: "checkbox", width: 50, fixed: "left"},
                {field: 'id', width: 80, title: 'ID', sort: true, align: "center"},
				{field: 'file', width: 500, title: '文件', align: "center"},
				{field: 'shijian', width: 200, title: '备份时间', align: "center", sort: true},
				{field: 'size', width: 110, title: '大小', align: "center", sort: true},
				{field: 'type', width: 100, title: '类型', align: "center", sort: true},
				{fixed: 'right', width: 150, title: '操作', templet: '#switchTpl', align: "center"}
            ]],
            limits: [10, 15, 20, 30, 50, 100],
            limit: 10,
            page: true
			  ,autoColumnWidth: {//宽自动
			  	//init: true
			  }
			  ,done: function () {
				soulTable.render(this)
			  }
        }).config.cols;
		
		table.on('toolbar(currentTableFilter)', function(obj){
			var checkStatus = table.checkStatus(obj.config.id);
			switch(obj.event){
				case 'backupdb':
					layer.load();
					$.get("../action.php?mode=backupdb",function(res){
						var r=JSON.parse(res);
						if(r.status == 1){
							layer.msg("备份完成");
							table.reload('zichanbiao', {
								url: '../action.php?mode=getbackupfile'
							});
						}else{
							layer.msg(r.msg);
						}
						layer.closeAll('loading');
					});
				break;
				case 'backupfile':
					layer.load();
					$.get("../action.php?mode=backupfile",function(res){
						var r=JSON.parse(res);
						if(r.status == 1){
							layer.msg("备份完成");
							table.reload('zichanbiao', {
								url: '../action.php?mode=getbackupfile'
							});
						}else{
							layer.msg(r.msg);
						}
						layer.closeAll('loading');
					});
				break;
			};
		});
		
        table.on('tool(currentTableFilter)', function (obj) {
            var id = obj.data.id;
			console.log(id);
            if (obj.event === 'shanchu') {
				layer.confirm('确定要删除吗？', {
					btn: ['确定','取消'], //按钮
					title: '删除询问'
				}, function(){
					$.post("../action.php",{mode:"deletebackupfile",id:id},function(res){
						var r=JSON.parse(res);
						if(r.status == 1){
							layer.msg("删除完成");
							table.reload('zichanbiao', {
								url: '../action.php?mode=getbackupfile'
							});
						}else{
							layer.msg(r.msg);
						}
					});
				}, function(){
					//取消
				});
                return false;
            }else if(obj.event === 'huifu'){
				layer.confirm('确定要恢复吗？', {
					btn: ['确定','取消'], //按钮
					title: '恢复询问'
				}, function(){
					layer.confirm('恢复可能造成数据丢失！', {
						btn: ['知道了','取消吧'], //按钮
						title: '恢复询问'
					}, function(){
						$.post("../action.php",{mode:"huifubackupsql",id:id},function(res){
							console.log(res);
							var r=JSON.parse(res);
							if(r.status == 1){
								layer.msg("恢复完成");
							}else{
								layer.msg(r.msg);
							}
						});
					}, function(){
						//取消
					});
				}, function(){
					//取消
				});
                return false;
			}else if (obj.event === 'xiazai') {
                location.href="../action.php?mode=downloadbackupfile&id=" + id;
                return false;
            }else{

			}
        });
    });
</script>
</body>
</html>