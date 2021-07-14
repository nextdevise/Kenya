<?php
include_once("../config.php");

//$sql = "SELECT auto_increment FROM information_schema.tables  WHERE table_name='system_menu'";
$sql = "select max(id) as id from system_menu";
$requ = mysqli_query($con,$sql);
$rs = mysqli_fetch_array($requ);
//$id = $rs['auto_increment'];
$id = $rs['id'] + 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>菜单管理</title>
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="../lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
</head>
<body>
<input type="text" class="layui-input" id="aassss" placeholder="搜索">
<table class="layui-hide" id="tableId" lay-filter="tableEvent"></table>
</body>
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button type="button" lay-event="tableTreeEdit" id="btn1" class="layui-btn  layui-btn-sm">添加菜单</button>
        <button type="button" lay-event="tableTreeEdit" id="btn6" class="layui-btn  layui-btn-sm">折叠/展开</button>
		<button type="button" lay-event="tableTreeEdit" id="btn3" class="layui-btn layui-btn-sm layui-btn-normal">保存修改</button>
	</div>
</script>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>

<script>
    layui.use(['table','layer',"tableTree"], function () {
		var zt = [{name:0,value:"禁用"},{name:1,value:"启用"}];
		var ni=<?php echo $id; ?>;
		var openall = true;
        var table = layui.table
            ,$ = layui.$
            ,tableTree = layui.tableTree
            ,layer = layui.layer
            ,treeTable = tableTree.render({
                elem: '#tableId'
                ,id:'tableTree'
                ,url:'/action.php?mode=getmenu'
				//,url:'/demo/tableTree/module/json/data.json'
                ,height: 'full-90'
                ,size:'sm'
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '图标列表'
                    ,layEvent: 'LAYTABLE_TIPS'
                    ,icon: 'layui-icon-tips'
                }]
                ,page: true
                ,treeConfig:{ //表格树所需配置
                    showField:'treeName' //表格树显示的字段
                    ,treeid:'id' //treeid所对应字段的值在表格数据中必须是唯一的，且不能为空。
                    ,treepid:'pid'//父级id字段名称
                    ,iconClass:'layui-icon-triangle-r' //小图标class样式 窗口图标 layui-icon-layer
					,showToolbar: true
				}
                ,cols: [[
                    {type:'checkbox'}
                    ,{field:'treeName',title: '名称',width:400, align: "center"}
                    ,{field:'id',title:'id',sort:true,event:'id',config:{type:'input'},width:80, align:"center"}
                    ,{field:'url',title:'地址',event:'url',config:{type:'input'},width:180, align: "center"}
                    ,{field:'sort', title: '排序',event:'sort',width:80, align: "center",config:{type:'input'}}
					,{field:'icon',width:150, title: '图标',event:'icon', align: "center",config:{type:'input'}
						,templet:function(d){
							return '<i class="'+d.icon+'"></i>';
						}
					}
                    //,{field:'createDate', title: '创建时间',event:'date',width:120,config:{type:'date',dateType:'date'}}
                    ,{field:'szt',title:'状态',event:'szt',config:{type:'select',data:zt}, align: "center",width:80,
						templet:function (d) {
							if(d.szt){
								if(d.szt.value){
									if(d.szt.name == 1){
										return  '✔';
									}else{
										return  '✘';
									}
								}else{
									return  d.szt;
								}
							}else{
								return ''
							}
						}
					}
                ]],done:function () {
                    //treeTable.closeAllTreeNodes();
                    //treeTable.openTreeNode(1);
					treeTable.openAllTreeNodes();
                }
            });
        /**
         *表格的增删改都会回调此方法
         * 与table.on(tool(lay-filter))用法一致。
         **/
        tableTree.on('tool(tableEvent)',function (obj) {
            var field = obj.field; //单元格字段
            var value = obj.value; //修改后的值
            var data = obj.data; //当前行数据
            var event = obj.event; //当前单元格事件属性值
			console.log(event);
            //event为del为删除 add则新增 edit则修改。这个三个值固定死了，切莫定义与之三个重复的event。
            if(event !== 'del' && event !== 'add' && event !== 'async'){
				console.log("非删除、增加、状态");
                var update = {};
                update[field] = value;
                obj.update(update);
                console.log(obj)
            }

            if(event === 'del'){
                obj.del();
            }
            if(event === 'add'){
                //可ajax异步请求后台,后台返回数据后用 obj.add(rs) 进行回调生成表格树。
                setTimeout(function () {
                    //在此模拟ajax异步的请求，返回数据调用以下函数。
                    //该方法新增下级节点，可以直接新表格树。
                    //有参数则，按照参数生成行，无参数则生成空行。参数类型为数组
                    //obj.add() 空行
					var data = [{"id":ni,"pid":0,"treeName":"请改名",url:"",icon:"fa fa-newspaper-o",sort:0,szt:{name:1,value:'✔'}}];
                    obj.add(data);
					ni++;
                },2);
            }
            if(event === 'id'){
                $(this).parents('tr').attr('tree-id',value);
            }
        });

        /**
         *监听复选框选中状态
         **/
        tableTree.on('checkbox(tableEvent)', function(obj){
            console.log(obj.checked); //当前是否选中状态
            console.log(obj.data); //选中行的相关数据
            console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
            //layer.msg(JSON.stringify(obj.data));
            console.log(JSON.stringify(obj.data));
        });
        //var isAsc = true;
        table.on('toolbar(tableEvent)', function(obj){
            var id = $(this).attr("id");
            if(id==="btn1"){
                //data可以为空，为空则创建空行，否则按照data数据生成行
                var data = {"id":ni,"pid":0,"treeName":"请改名",url:"",icon:"fa fa-newspaper-o",sort:0,szt:{name:1,value:'✔'}};
                treeTable.addTopTreeNode(data);//新增最上级节点
				ni++;
            }else if(id === 'btn3') {
                //layer.msg(JSON.stringify(treeTable.getTableTreeData()));//获取表格树所有数据
                console.log(JSON.stringify(treeTable.getTableTreeData()))
                //console.log(treeTable.getTableTreeData())
                //console.log(table.cache['tableTree'])
				var d = JSON.stringify(treeTable.getTableTreeData());
				$.post("../action.php",{mode:"changemenu",data:d},function(res){
					console.log(res);
					var r=JSON.parse(res);
					if(r.status==1){
						layer.msg('修改保存成功', {icon: 6});
					}else{
						layer.msg(r.msg + '条记录保存失败', {icon: 5});
						treeTable.refresh();
					}
				});
            }else if(id === 'btn6') {
				if(openall){
					treeTable.closeAllTreeNodes();  //关闭所有树节点
				}else{
					treeTable.openAllTreeNodes(); //展开所有树节点
				}
				openall=!openall;
            }
			if(obj.event == 'LAYTABLE_TIPS'){
				layer.open({
                    title: '图标列表',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['50%', '95%'],
                    content: '/page/icon.html',
                });
			}
        });

        /**
         * 整个表格树排序，与layui进行了整合。
         */
        table.on('sort(tableEvent)', function(obj){
            treeTable.sort({field:obj.field,desc:obj.type === 'desc'})
        });

        $('#aassss').on('change',function () {
            treeTable.keywordSearch(this.value); //关键词搜索树
        });
    });
</script>
</html>