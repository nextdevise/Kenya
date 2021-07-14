<?php

include_once("config.php");
include_once("PasswordStorage.php");
if(!isset($_REQUEST['mode'])){
	$result = '{"status":0}';
}else{
	$mode=$_REQUEST['mode'];
	
	if($mode=='login'){//后台登录
		//session_start();
		$u=$_POST['user'];
		$p=$_POST['pass'];
		$isyzm=$_POST['isyzm'];
		if($isyzm == 'block'){
			$y=strtolower($_POST['yzm']);
			$yzm = $_SESSION['code'];
			if($y != $yzm){
				if(isset($_SESSION['errpwd'])){
					$cw = $_SESSION['errpwd'];
				}else{
					$cw = 0;
				}
				die('{"status":-9,"msg":"验证码错误","err":'.$cw.'}');
			}
		}
		$_SESSION['user']=$u;
		//$p=PasswordStorage::create_hash($p);
		$sql="select password,status,juese,bm from user where username='$u'";
		$requ=mysqli_query($con,$sql);
		if($requ){
			$rs=mysqli_fetch_array($requ);
			if($rs){
				if($rs['status']==1){
					$r=PasswordStorage::verify_password($p, $rs['password']);
					if($r){
						$result = '{"status":1,"msg":"ok","err":0}';
						$_SESSION['admin']=$u;
						$_SESSION['juese']=$rs['juese'];
						$_SESSION['bm'] = $rs['bm'];
						$_SESSION['errpwd']=0;
						$_SESSION['code']='';
						rlog('登录成功');
					}else{
						if(isset($_SESSION['errpwd'])){
							$_SESSION['errpwd']++;
						}else{
							$_SESSION['errpwd']=1;
						}
						$result = '{"status":0,"msg":"密码错误","err":'.$_SESSION['errpwd'].'}';
						rlog('登录失败，密码错误');
					}
				}else{
					$result = '{"status":0,"msg":"用户被禁用","err":0}';
					rlog('登录失败，被禁用');
				}
			}else{
				$result = '{"status":0,"msg":"用户名错误","err":0}';
				rlog('登录失败，用户名错误');
			}
		}else{
			$result = '{"status":0,"msg":"系统错误","err":0}';
			rlog('登录失败');
		}
	}else{
		if(!isset($_SESSION['admin'])){
			die('{"status":-98,"msg":"无权限"}');
		}
	}
	if($mode=='logout'){//后台退出
		rlog('退出成功');
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		session_unset();
		session_destroy();
		header("location:/page/login.php");
		die();
	}
	if($mode=='changepassword'){//修改密码
		//session_start();
		$u=$_SESSION['admin'];
		$o=$_POST['oldp'];
		$p=$_POST['newp'];
		$sql="select password from user where username='$u'";
		$requ=mysqli_query($con,$sql);
		$rs=mysqli_fetch_array($requ);
		$r=PasswordStorage::verify_password($o, $rs['password']);
		if($r){
			$p=PasswordStorage::create_hash($p);
			$sql="update user set password='$p' where username='$u'";
			mysqli_query($con,$sql);
			if(mysqli_affected_rows($con)){
				$result = '{"status":1}';
				rlog('修改密码成功');
			}else{
				$result = '{"status":0,"msg":"密码修改失败，请重试"}';
				rlog('修改密码失败');
			}
		}else{
			$result = '{"status":0,"msg":"原密码错误"}';
			rlog('修改密码失败，原密码错误');
		}
	}
	if($mode=='getzichanleixing'){//获取资产类型列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		if(isset($_GET['dw'])){
			$dw = ' and zcfl='.$_GET['dw'];
		}else{
			$dw = '';
		}
		$sqls="select id,name,zcfl,status from zclx where 1=1 $dw";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$zcfz=array("","信息中心","办公室","物管办");
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'",
					   "zcfz":"'.$zcfz[$rs['zcfl']].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog('获取资产类型列表');
	}
	if($mode=='chengezclxzt'){//更改资产类型状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update zclx set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("ok!更改资产类型状态$id");
		}else{
			$result = '{"status":0}';
			rlog("no!更改资产类型状态$id");
		}
	}
	if($mode=='addzclx'){//添加资产类型
		$name=$_POST['name'];
		$sql = "select id from zclx where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("no!添加资产类型，重复。$name");
		}else{
			$zcfz = $_POST['zcfz'];
			$sql = "insert into zclx (name, zcfl, status) values ('$name', $zcfz, 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("OK！添加资产类型$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("NO！添加资产类型$name");
			}
		}
	}
	if($mode=='getzichanzhuangtai'){//获取资产状态列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select id,name,status,icon,color from zhuangtai where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'",
			"icon":"'.$rs['icon'].'","color":"'.$rs['color'].'",
			"status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取资产状态列表");
	}
	if($mode=='chengezcztzt'){//更改资产状态的状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update zhuangtai set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("OK！更改资产状态$id");
		}else{
			$result = '{"status":0}';
			rlog("no！更改资产状态$id");
		}
	}
	if($mode=='addzczt'){//添加资产状态
		$name=$_POST['name'];
		$icon=$_POST['icon'];
		$color=$_POST['color'];
		$sql = "select id from zhuangtai where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("no！添加资产状态$name");
		}else{
			$sql = "insert into zhuangtai (name, status, icon, color) values ('$name', 1, '$icon', '$color')";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("ok！添加资产状态$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("no！添加资产状态$name");
			}
		}
	}
	if($mode=='getdanweilist'){//获取单位列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select id,name,status from danwei where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取单位列表");
	}
	if($mode=='chengedwzt'){//更改单位状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update danwei set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("OK！ 更改单位状态$id");
		}else{
			$result = '{"status":0}';
			rlog("NO！ 更改单位状态$id");
		}
	}
	if($mode=='adddanwei'){//添加单位
		$name=$_POST['name'];
		$sql = "select id from danwei where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
			rlog("NO！添加单位重复$name");
		}else{
			$sql = "insert into danwei (name, status) values ('$name', 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("OK！添加单位$name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("NO！ 添加单位失败$name");
			}
		}
	}
	if($mode=='getjueselist'){//获取角色列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select a.id as id,a.name as name,a.status as status,
			   a.shanchu as shanchu,a.xiugai as xiugai,
			   a.value as value,danwei.name as bm 
			   from juese as a 
			   left join danwei on a.bm=danwei.id
			   where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$v = $rs['value'];
			$bm = $rs['bm'];
			if(empty($bm)){$bm = '全局';}
			$v = rtrim($v,',');
			$sqq = "select title from system_menu where id in ($v)";
			//echo $sqq;
			$req = mysqli_query($con,$sqq);
			$s = '';
			while($rss = mysqli_fetch_array($req)){
				$s.=$rss['title'].',';
			}
			$s = rtrim($s,',');
			$result.='{"id":'.$rs['id'].',"qx":"'.$s.'","name":"'.$rs['name'].'",
					   "shanchu":'.$rs['shanchu'].',"xiugai":'.$rs['xiugai'].',
					   "qxbm":"'.$bm.'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取角色列表");
	}
	if($mode=='chengejszt'){//更改角色状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='chengejssc'){//更改角色删除权限
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set shanchu=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='chengejsxg'){//更改角色修改权限
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update juese set xiugai=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改角色$id 状态ok");
		}else{
			$result = '{"status":0}';
			rlog("更改角色$id 状态no");
		}
	}
	if($mode=='addjuese'){//添加角色
		$name=$_POST['name'];
		$v = $_POST['value'];
		$qxbm = $_POST['qxbm'];
		$sql = "select id from juese where name='$name'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result = '{"status":0,"msg":"名称已存在"}';
		}else{
			$sql = "insert into juese (name, value, bm, status) values ('$name', '$v', $qxbm, 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("添加角色 $name 成功,权限 $v ");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("添加角色 $name 失败");
			}
		}
	}
	if($mode == 'editjuese'){//编辑角色权限
		$id = $_REQUEST['id'];
		$v = $_POST['value'];
		$u = $_POST['u'];
		$qxbm = $_POST['qxbm'];
		$sql = "update juese set name='$u',value='$v',bm=$qxbm where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("编辑角色 $id 权限 $v ok ");
		}else{
			$result = '{"status":0,"msg":"未修改或修改失败"}';
			rlog("编辑角色 $id 权限 $v no ");
		}
	}
	if($mode=='getuserlist'){//用户列表
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqlsa="select a.id as id,a.username as username,a.juese as juese,
			   a.status as status,danwei.name as bm
			  from user as a 
			  left join danwei on a.bm=danwei.id
			  where 1=1";
		$sqls="select a.id as id,a.username as username,a.juese as juese,
			   a.status as status,a.bm as bm
			  from user as a 
			  where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$ssq = "select name from juese where id=".$rs['juese'];
			$requu=mysqli_query($con,$ssq);
			$rsr=mysqli_fetch_array($requu);
			$bm = $rs['bm'];
			$ssqq = "select name from danwei where id in ($bm)";
			$rree=mysqli_query($con,$ssqq);
			$bm='';
			while($rrss = mysqli_fetch_array($rree)){
				$bm.=$rrss['name'].',';
			}
			$bm = rtrim($bm,",");
			if(empty($bm)){$bm='全局';}
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['username'].'","qxbm":"'.$bm.'",
					   "juese":"'.$rsr['name'].'","status":"'.$rs['status'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog('get用户列表');
	}
	if($mode == 'addyonghu'){//添加用户
		$name = $_POST['name'];
		$sql = "select id from user where username='$name'";
		$requ=mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			$result='{"status":"0","msg":"用户名已存在"}';
		}else{
			$js = $_POST['js'];
			$bm = $_POST['qxbm'];
			$sql = "insert into user (username, password, juese, bm, status) 
					values ('$name', 'sha512:10000:24:hXJefLjmwWFX4gcbuo3+/gHyJoAV8FFd:/JDiHC7RyWfjJljsshZZvKcx1KJFoCK+', $js, '$bm', 1)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":1}';
				rlog("添加用户成功 $name");
			}else{
				$result = '{"status":0,"msg":"添加失败，请重试"}';
				rlog("添加用户失败 $name");
			}
		}
	}
	if($mode=='chengeyhzt'){//更改用户状态
		$id=$_POST['id'];
		$zhi=$_POST['zhi'];
		$sql="update user set status=$zhi where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("更改用户 $id 状态 $zhi OK ");
		}else{
			$result = '{"status":0}';
			rlog("更改用户 $id 状态 $zhi no ");
		}
	}
	if($mode == 'chengeuserpassword'){//重置用户密码
		$id=$_POST['id'];
		$sql="update user set password='sha512:10000:24:hXJefLjmwWFX4gcbuo3+/gHyJoAV8FFd:/JDiHC7RyWfjJljsshZZvKcx1KJFoCK+' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("重置用户 $id 密码 OK ");
		}else{
			$result = '{"status":0}';
			rlog("重置用户 $id 密码 no ");
		}
	}
	if($mode == 'edityonghu'){//编辑用户
		$id=$_POST['id'];
		$n=$_POST['name'];
		$js=$_POST['js'];
		$bm = $_POST['qxbm'];
		$sql="update user set username='$n',juese=$js,bm='$bm' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":1}';
			rlog("编辑用户 $id $n $js 成功");
		}else{
			$result = '{"status":0,"msg":"修改失败或未做修改"}';
			rlog("编辑用户 $id $n $js 失败");
		}
	}
	if($mode=='addwgbgszc'){//添加物管办和办公室资产
		$data=$_POST['data'];
		$ll='[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
			   "act":"新增","new":'.$data.'}]';
		$d=json_decode($data);
		$zz=$_POST['zz'];
		$sjk = $zz==2?'bgszichan':'wgbzichan';
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		if(!empty($zcbh)){
			$sql = "select zcbh from $sjk where zcbh='$zcbh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				die('{"status":"0","msg":"资产编号重复"}');
			}
		}
		$sql = "select xlh from $sjk where xlh='$xlh'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			die('{"status":"0","msg":"序列号重复"}');
		}
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$cw=$d->cw;
		$sql = "insert into $sjk (zcbh,xlh,zclx,cw,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll) values 
								 ('$zcbh','$xlh',$zclx,'$cw',$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll')";
		//echo $sql;
		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result='{"status":"1"}';
			rlog("添加 $sjk 资产 成功");
		}else{
			$result='{"status":"0","msg":"添加失败，请重试"}';
			rlog("添加 $sjk 资产 失败");
		}
		$_SESSION['addlishi']=$data;
	}
	if($mode =='daoru'){//导入
		$zz = $_POST['zz'];
		$q = $_POST['q'];
		$z = $_POST['z'];
		$f = $_POST['f'];
		$f = "upfile/$f";
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$zz];
		$bmz = $_SESSION['bm'];//角色权限部门ID 

		require_once './Excel/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load($f);
		$objWorksheet = $objPHPExcel->getSheet(0); 
		//$objWorksheet = $objPHPExcel->getActiveSheet();
		if($z == '' || $z < 1){
			$z = $objWorksheet->getHighestRow(); //总行数
		}
		for($row=$q;$row<=$z;$row++){
			$zcbh=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
			$xlh=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
			$zclx=$objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
			$zczt=$objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
			$bm=$objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
			if($bmz != 0){
				$ar = explode(",", $bmz);
				if(!in_array($bm, $ar)){
					echo $row.'无权限<br>';
					continue;
				}
			}
			$bgr=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
			$dz=$objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
			$cgsj=$objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
			$cgsj=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($cgsj));
			$rzsj=$objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
			$rzsj=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($rzsj));
			$zbsc=$objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
			$sysc=$objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
			$pp=$objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
			$xh=$objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
			$zcly=$objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
			$zcjz=$objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
			$gg=$objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
			$bz=$objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
			$img=$objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
			$cw=$objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
			
			$wlbs=$objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
			if(empty($wlbs)){$wlbs=0;}
			$ip=$objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
			$xsq=$objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
			$yp=$objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
			if(empty($yp)){$yp=0;}
			$nc=$objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
			if(empty($nc)){$nc=0;}
				
			$sql = "select xlh from $sjk where xlh='$xlh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				echo $row . "：序列号重复<br>";
			}else{
				if(!empty($zcbh)){
					$sql = "select zcbh from $sjk where zcbh='$zcbh'";
					$requ = mysqli_query($con,$sql);
					if(mysqli_num_rows($requ)){
						$bhcf = true;
					}else{
						$bhcf = false;
					}
				}else{
					$bhcf = false;
				}
				if($bhcf){
					echo $row . "：资产编号重复<br>";
				}else{
					if($zz == 1){
						$ll = '[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
								"act":"导入新增","new":{"zclx":"'.$zclx.'","zczt":"'.$zczt.'","zcbh":"'.$zcbh.'",
								"xlh":"'.$xlh.'","bgr":"'.$bgr.'","bm":"'.$bm.'","dz":"'.$dz.'","cgsj":"'.$cgsj.'",
								"rzsj":"'.$rzsj.'","zbsc":"'.$zbsc.'","sysc":"'.$sysc.'","pp":"'.$pp.'","xh":"'.$xh.'",
								"gg":"'.$gg.'","zcly":"'.$zcly.'","zcjz":"'.$zcjz.'","bz":"'.$bz.'","file":"",
								"wlbs":"'.$wlbs.'","ip":"'.$ip.'","yp":"'.$yp.'","xsq":"'.$xsq.'",
								"cw":"'.$cw.'","nc":"'.$nc.'","img":"'.$img.'"}}]';
						$cgsj=strtotime($cgsj);
						$rzsj=strtotime($rzsj);
						$sql = "insert into $sjk (zcbh,xlh,zclx,cw,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll,wlbs,ip,xsq,yp,nc) values 
												 ('$zcbh','$xlh',$zclx,'$cw',$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll',$wlbs,'$ip','$xsq',$yp,$nc)";
					}else{
						$ll = '[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'",
								"act":"导入新增","new":{"zclx":"'.$zclx.'","zczt":"'.$zczt.'","zcbh":"'.$zcbh.'",
								"xlh":"'.$xlh.'","bgr":"'.$bgr.'","bm":"'.$bm.'","dz":"'.$dz.'","cgsj":"'.$cgsj.'",
								"rzsj":"'.$rzsj.'","zbsc":"'.$zbsc.'","sysc":"'.$sysc.'","pp":"'.$pp.'","xh":"'.$xh.'",
								"cw":"'.$cw.'","gg":"'.$gg.'","zcly":"'.$zcly.'","zcjz":"'.$zcjz.'","bz":"'.$bz.'","file":"","img":"'.$img.'"}}]';
						$cgsj=strtotime($cgsj);
						$rzsj=strtotime($rzsj);
						$sql = "insert into $sjk (zcbh,xlh,zclx,cw,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll) values 
												 ('$zcbh','$xlh',$zclx,'$cw',$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll')";
					}
					mysqli_query($con,$sql);
					if(mysqli_insert_id($con)){
						echo $row . "：导入成功<br>";
					}else{
						echo $row . "：导入失败<br> $sql <br>";
					}
				}
			}
		}
		$result='';
		rlog("导入 $f ");
	}
	if($mode=='addxxzxzc'){//添加信息中心资产
		$data=$_POST['data'];
		$ll='[{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'","act":"新增","new":'.$data.'}]';
		$d=json_decode($data);
		//$zz=$_POST['zz'];
		//$sjk = $zz==2?'bgszichan':'wgbzichan';
		$sjk = 'xinxizichan';
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		if(!empty($zcbh)){
			$sql = "select zcbh from $sjk where zcbh='$zcbh'";
			$requ = mysqli_query($con,$sql);
			if(mysqli_num_rows($requ)){
				die('{"status":"0","msg":"资产编号重复"}');
			}
		}
		$sql = "select xlh from $sjk where xlh='$xlh'";
		$requ = mysqli_query($con,$sql);
		if(mysqli_num_rows($requ)){
			die('{"status":"0","msg":"序列号重复"}');
		}
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$cw=$d->cw;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$wlbs=$d->wlbs;
		$ip=$d->ip;
		$xsq=$d->xsq;
		$yp=$d->yp;
		$nc=$d->nc;
		if(empty($wlbs)){$wlbs=0;}
		if(empty($yp)){$yp=0;}
		if(empty($nc)){$nc=0;}
		$sql = "insert into $sjk (zcbh,xlh,zclx,cw,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,sysc,pp,xh,zcly,zcjz,bz,img,gg,ll,wlbs,ip,xsq,yp,nc) values 
								 ('$zcbh','$xlh',$zclx,'$cw',$zczt,$bm,'$bgr','$dz',$cgsj,$rzsj,$zbsc,$sysc,'$pp','$xh','$zcly',$zcjz,'$bz','$img','$gg','$ll',$wlbs,'$ip','$xsq',$yp,$nc)";

		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result='{"status":"1"}';
			rlog("添加 $sjk 资产ok");
		}else{
			$result='{"status":"0","msg":"添加失败，请重试"}';
			rlog("添加 $sjk 资产no");
		}
		$_SESSION['addlishi']=$data;
	}
	if($mode=='searchzichan'){//资产查询
		$dw=$_GET['dw'];//部门代码
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$dw];
		$bmz = $_SESSION['bm'];//角色权限部门ID 
		if($bmz == 0){
			$dwa='';
			$dwz = '';
		}else{
			$dwa=" and id in ($bmz)";
			$dwz = " and a.bm in ($bmz)";
		}
		if(isset($_POST['columns'])){//获取筛选数据
			$res='{';
			$col = urldecode($_POST['columns']);
			header('content-type:application/json,charset=UTF-8');
			$sql = "select name from zclx where status=1 and zcfl=$dw";
			$requ=mysqli_query($con,$sql);
			$res.='"zclx":[';
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"zczt":[';
			$sql = "select name from zhuangtai where status=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"bm":[';
			$sql = "select name from danwei where status=1 $dwa";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"pp":[';
			$sql = "select DISTINCT pp from $sjk where 1=1";
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['pp'].'",';
			}
			$res=rtrim($res,',');
			$res.=']}';
			die($res);
		}else{
			//获取资产数据
			if(isset($_REQUEST['filterSos'])){//筛选
				$shaixuan = json_decode(urldecode($_REQUEST['filterSos']));
				$xx='';
				foreach ($shaixuan as $item) {
					$v=$item->values;
					if(empty($v)){
						continue;
					}
					$sj = $item->field;
					if($sj == 'pp'){
						$str = '';
						foreach($v as $a){
							$str .= "'$a',";
						}
						$str = rtrim($str,',');
						$xx.=" and a.pp in ($str)";
					}else{
						if($sj=='zclx'){$sjkk='zclx';}
						if($sj=='zczt'){$sjkk='zhuangtai';}
						if($sj=='bm'){$sjkk='danwei';}
						$d = '';
						foreach($v as $a){
							$sql = "select id from $sjkk where name='$a'";
							//echo 'sql:'.$sql.'<br>';
							$requ = mysqli_query($con,$sql);
							$rs = mysqli_fetch_array($requ);
							$d.=$rs['id'].',';
						}
						$d=rtrim($d,',');
						$xx.=" and a.$sj in ($d)";
					}
				}
				//echo $xx.'<br>';
			}else{
				$xx = '';
			}


			$p=$_GET['page'];
			$l=$_GET['limit'];
			$p=($p-1)*$l;
			
			if(isset($_GET['rzqi'])){
				$rzqi = $_GET['rzqi'];
				$rzzhi = $_GET['rzzhi'];
				$mhss = $_GET['mhss'];
				if($rzqi == ''){
					$qi = 0;
				}else{
					$qi = strtotime($rzqi);
				}
				if($rzzhi == ''){
					$zi = 9999999999;
				}else{
					$zi = strtotime($rzzhi);
				}
				$shijian = " and a.rzsj between $qi and $zi";
				if(empty($mhss)){
					$mhss='';
				}else{
					$mhss = " and (a.zcbh like '%$mhss%' 
								or a.xlh like '%$mhss%' 
								or a.bgr like '%$mhss%'
								or a.dz like '%$mhss%'
								or a.pp like '%$mhss%'
								or a.xh like '%$mhss%'
								or a.gg like '%$mhss%'
								or a.bz like '%$mhss%'
								or a.ip like '%$mhss%'
								or a.zcly like '%$mhss%')";
				}
			}else{
				$shijian = '';
				$mhss = '';
			}
			
			if($dw == 1){
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  zhuangtai.name as zczt,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $shijian $mhss $dwz";
			}else{
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $dwz";
			}		  
			$sql=$sqls." limit $p,$l";
			$requ=mysqli_query($con,$sqls);
			if (!$requ) {
				echo $sqls.'<br>';
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}
			$num=mysqli_num_rows($requ);
			$requ=mysqli_query($con,$sql);
			$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
			while($rs=mysqli_fetch_array($requ)){
				$cgsj = date("Y-m-d",$rs['cgsj']);
				$rzsj = date("Y-m-d",$rs['rzsj']);
				$result.='{"id":"'.$rs['id'].'","zcbh":"'.$rs['zcbh'].'","xlh":"'.$rs['xlh'].'",
						   "zclx":"'.$rs['zclx'].'","zczt":"'.$rs['zczt'].'",
						   "bm":"'.$rs['bm'].'","bgr":"'.$rs['bgr'].'","dz":"'.$rs['dz'].'",
						   "cgsj":"'.$cgsj.'","rzsj":"'.$rzsj.'",
						   "zbsc":"'.$rs['zbsc'].'","sysc":"'.$rs['sysc'].'",
						   "pp":"'.$rs['pp'].'","xh":"'.$rs['xh'].'","zcly":"'.$rs['zcly'].'",
						   "zcjz":"'.$rs['zcjz'].'","gg":"'.$rs['gg'].'",
						   "bz":"'.$rs['bz'].'","img":"'.$rs['img'].'"';
				if($dw == 1){
					$w=array("未指定","内网","外网");
					$result.=',"wlbs":"'.$w[$rs['wlbs']].'","ip":"'.$rs['ip'].'",
							  "xsq":"'.$rs['xsq'].'","yp":"'.$rs['yp'].'","nc":"'.$rs['nc'].'"},';
				}else{
					$result.='},';
				}
			}
			$result=rtrim($result,',');
			$result.=']}';
		}
		rlog("获取 $sjk  资产列表");
	}
	if($mode=='xiugaixxzxzc'){//修改信息中心资产
		$sjk = 'xinxizichan';
		$id=$_POST['id'];
		$sql = "select ll from $sjk where id=$id";
		$r = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($r);
		$ll = $rs['ll'];//该资产原履历
		$ll=rtrim($ll,']');
		$data=$_POST['data'];
		$d=json_decode($data);
		$new = '{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'","act":"修改","new":'.$data.'}';
		$ll.=",$new]";//新履历
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$wlbs=$d->wlbs;
		$ip=$d->ip;
		$xsq=$d->xsq;
		$yp=$d->yp;
		$nc=$d->nc;
		$cw=$d->cw;
		if(empty($wlbs)){$wlbs=0;}
		if(empty($yp)){$yp=0;}
		if(empty($nc)){$nc=0;}
		$sql = "update $sjk set zcbh='$zcbh',xlh='$xlh',zclx=$zclx,zczt=$zczt,cw='$cw',
				bm=$bm,bgr='$bgr',dz='$dz',cgsj=$cgsj,rzsj=$rzsj,zbsc=$zbsc,sysc=$sysc,
				pp='$pp',xh='$xh',zcly='$zcly',zcjz=$zcjz,bz='$bz',img='$img',gg='$gg',
				wlbs=$wlbs,ip='$ip',xsq='$xsq',yp=$yp,nc=$nc,ll='$ll' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result='{"status":"1"}';
			rlog("修改 $sjk 资产 $id 成功 ");
		}else{
			$result='{"status":"0","msg":"修改失败，请重试"}';
			rlog("修改 $sjk 资产 $id  no ");
		}
	}
	if($mode == 'xiugaizc'){//修改办公室、物管办资产
		$id = $_POST['id'];
		$sjk = $_POST['sjk'];
		$sql = "select ll from $sjk where id=$id";
		$r = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($r);
		$ll = $rs['ll'];//该资产原履历
		$ll=rtrim($ll,']');
		$data=$_POST['data'];
		$d=json_decode($data);
		$new = '{"user":"'.$_SESSION['admin'].'","time":"'.date('Y-m-d H:i:s').'","act":"修改","new":'.$data.'}';
		$ll.=",$new]";//新履历
		$zcbh=$d->zcbh;
		$xlh=$d->xlh;
		$zclx=$d->zclx;
		$zczt=$d->zczt;
		$bm=$d->bm;
		$bgr=$d->bgr;
		$dz=$d->dz;
		$cgsj=strtotime($d->cgsj);
		$rzsj=strtotime($d->rzsj);
		$zbsc=$d->zbsc;
		$sysc=$d->sysc;
		$pp=$d->pp;
		$xh=$d->xh;
		$zcly=$d->zcly;
		$zcjz=$d->zcjz;
		$gg=$d->gg;
		$bz=$d->bz;
		$img=$d->img;
		$cw=$d->cw;
		$sql = "update $sjk set zcbh='$zcbh',xlh='$xlh',zclx=$zclx,cw='$cw',zczt=$zczt,
				bm=$bm,bgr='$bgr',dz='$dz',cgsj=$cgsj,rzsj=$rzsj,zbsc=$zbsc,sysc=$sysc,
				pp='$pp',xh='$xh',zcly='$zcly',zcjz=$zcjz,bz='$bz',img='$img',gg='$gg',
				ll='$ll' where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result='{"status":"1"}';
			rlog("修改 $sjk 资产 $id 成功 ");
		}else{
			$result='{"status":"0","msg":"修改失败，请重试"}';
			rlog("修改 $sjk 资产 $id no ");
		}
	}
	if($mode == 'downloadzclist'){//资产导出
			$sjk = $_GET['zz'];
			if(isset($_SESSION['bm'])){
				$bmz = $_SESSION['bm'];//角色权限部门ID 
			}else{
				$bmz = 0;
			}
			if($bmz == 0){
				$bmz = '';
			}else{
				$bmz = " and a.bm in ($bmz)";
			}
			rlog("导出 $sjk 资产");
			if($sjk == 'xinxizichan'){
				$f = 'xxzx.xlsx';
				$sql="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  zhuangtai.name as zczt,a.cw as cw,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $bmz";
			}else{
				$f = 'wgbgs.xlsx';
				$sql="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,a.cw as cw,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $bmz";
			}
			require_once './Excel/PHPExcel/IOFactory.php';
			require_once './Excel/PHPExcel.php';
			$reader = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $reader->load($f);
			$sheet = $excel->getSheet(0);
			$row=2;
			$requ=mysqli_query($con,$sql);
			if (!$requ) {
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}

			while($rs=mysqli_fetch_array($requ)){
				$excel->getActiveSheet()->getRowDimension($row)->setRowHeight(22);//设置行高
				$excel->getActiveSheet(0)->setCellValue('A'.$row, $rs['zclx']);
				$excel->getActiveSheet(0)->setCellValue('B'.$row, $rs['zczt']);
				$excel->getActiveSheet(0)->setCellValue('C'.$row, $rs['zcbh']);
				$excel->getActiveSheet(0)->setCellValue('D'.$row, $rs['xlh']);
				$excel->getActiveSheet(0)->setCellValue('E'.$row, $rs['cw']);
				$excel->getActiveSheet(0)->setCellValue('F'.$row, $rs['bgr']);
				$excel->getActiveSheet(0)->setCellValue('G'.$row, $rs['bm']);
				$excel->getActiveSheet(0)->setCellValue('H'.$row, $rs['dz']);
				$excel->getActiveSheet(0)->setCellValue('I'.$row, $rs['pp']);
				$excel->getActiveSheet(0)->setCellValue('J'.$row, $rs['xh']);
				$excel->getActiveSheet(0)->setCellValue('K'.$row, $rs['gg']);
				$excel->getActiveSheet(0)->setCellValue('L'.$row, date("Y-m-d",$rs['cgsj']));
				$excel->getActiveSheet(0)->setCellValue('M'.$row, date("Y-m-d",$rs['rzsj']));
				$excel->getActiveSheet(0)->setCellValue('N'.$row, $rs['zbsc']);
				$excel->getActiveSheet(0)->setCellValue('O'.$row, $rs['sysc']);
				$excel->getActiveSheet(0)->setCellValue('P'.$row, $rs['zcly']);
				$excel->getActiveSheet(0)->setCellValue('Q'.$row, $rs['zcjz']);
				if($sjk == 'xinxizichan'){
					$wlbs = array("未指定","内网","外网");
					$wlbs = $wlbs[$rs['wlbs']];
					$excel->getActiveSheet(0)->setCellValue('R'.$row, $wlbs);
					$excel->getActiveSheet(0)->setCellValue('S'.$row, $rs['ip']);
					$excel->getActiveSheet(0)->setCellValue('T'.$row, $rs['xsq']);
					$excel->getActiveSheet(0)->setCellValue('U'.$row, $rs['yp']);
					$excel->getActiveSheet(0)->setCellValue('V'.$row, $rs['nc']);
					$excel->getActiveSheet(0)->setCellValue('X'.$row, $rs['bz']);
					$img = $rs['img'];
					if(!empty($img)){
						$excel->setActiveSheetIndex(0)->getStyle('W')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet(0)->getColumnDimension('W')->setWidth(10);
						if(substr($img,0,7) != 'http://'){
							$img = substr($img,1);
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setPath($img);
							$objDrawing->setWidth(30);
							$objDrawing->setHeight(22);
							$objDrawing->setCoordinates('W'.$row);
							$objDrawing->setOffsetX(0);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($excel->getActiveSheet());
							$img = $url.$img;
							$excel->getActiveSheet()->getCell('W'.$row)->getHyperlink()->setUrl($img);
						}else{
							$excel->getActiveSheet()->setCellValue('W'.$row, $img);
							$excel->getActiveSheet()->getCell('W'.$row)->getHyperlink()->setUrl($img);
						}
					}
				}else{
					$excel->getActiveSheet(0)->setCellValue('S'.$row, $rs['bz']);
					$img = $rs['img'];
					if(!empty($img)){
						$excel->setActiveSheetIndex(0)->getStyle('R')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet(0)->getColumnDimension('R')->setWidth(10);
						if(substr($img,0,7) != 'http://'){
							$img = substr($img,1);
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setPath($img);
							$objDrawing->setWidth(30);
							$objDrawing->setHeight(22);
							$objDrawing->setCoordinates('R'.$row);
							$objDrawing->setOffsetX(0);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($excel->getActiveSheet());
							$img = $url.$img;
							$excel->getActiveSheet()->getCell('R'.$row)->getHyperlink()->setUrl($img);
						}else{
							$excel->getActiveSheet()->setCellValue('R'.$row, $img);
							$excel->getActiveSheet()->getCell('R'.$row)->getHyperlink()->setUrl($img);
						}
					}
				}
				$row++;
			}
            $row--;
            $excel->getActiveSheet()->setAutoFilter('A1:W'.$row);//添加自动筛选，可在标题栏筛选数据
			$sheet->getDefaultRowDimension()->setRowHeight(22);//设置默认行高
			$file=$sjk.'_'.date("Y-m-d").'.xlsx';
			$objWrite = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');  
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器数据excel07文件
			header("Content-Type: application/force-download"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Type: application/download"); 
			header("Content-Disposition:attachment;filename=$file");  
			header("Pragma: no-cache"); 
			header('Cache-Control: max-age=0');//禁止缓存
			$objWrite->save('php://output');
			exit;
	}
	if($mode == 'getrizhi'){//获取操作日志
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select * from log where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"user":"'.$rs['user'].'",
						"action":"'.$rs['action'].'",
						"ip":"'.$rs['ip'].'","time":"'.$rs['time'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取日志");
	}
	if($mode == 'xgcs'){//修改参数
		$sjk = $_POST['sjk'];
		$v = $_POST['v'];
		$sql = "update config set value=$v where title='$sjk'";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":"1"}';
		}else{
			$result = '{"status":"0"}';
		}
		rlog("修改参数");
	}
	if($mode == 'deletezichan'){//删除资产
		$sjk = $_POST['sjk'];
		$id = $_POST['id'];
		if($sjk == 'xinxizichan'){
			$sql = "insert into shanchu select * from $sjk where id in ($id)";
			mysqli_query($con,$sql);
		}
		$sql = "delete from $sjk where id in ($id)";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":"1"}';
			rlog("删除资产 $id 成功");
		}else{
			$result = '{"status":"0"}';
			rlog("删除资产 $id 失败");
		}
	}
	if($mode=='getmenu'){//获取菜单
		$menuInfo = getMenuList();
		$result = '{"data":'.json_encode($menuInfo).',"code":0,"msg":"","count":10}';
		rlog("获取菜单");
	}
	if($mode == "changemenu"){//修改菜单
		$err = 0;
		$sql = "TRUNCATE `system_menu`";
		mysqli_query($con,$sql);
		$data = $_POST['data'];
		$data = json_decode($data,true);
		changeMenu($data);
		if($err){
			$result = '{"status":"0","msg":"'.$err.'"}';
			rlog("获取菜单 no ");
		}else{
			$result = '{"status":"1","msg":"ok"}';
			rlog("修改菜单 ok");
		}
	}
	if($mode == 'zckpsz'){//资产卡片配置
		$sjk = $_REQUEST['sjk'];
		$data = $_REQUEST['v'];
		$sql = "update config set value='$data' where title='$sjk'";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$result = '{"status":"1","msg":"修改成功"}';
		}else{
			$result = '{"status":"0","msg":"修改失败"}';
		}
		rlog("修改资产卡片配置");
	}
    if($mode == 'qianchu'){//数据迁出
        set_time_limit(0);//设置超时
        ini_set('memory_limit', '256M');//设置可用最大内存
        $fileName = date('YmdHis', time());//CSV文件名
        $fp = fopen("$fileName.csv", 'a');//打开CSV文件
        
        $id = $_GET['id'];
        $sjk = $_GET['sjk'];
        $sql = "select count(id) as total from `$sjk` where id in ($id)";
        //查询要导出的记录条数
        $result = mysqli_query($con,$sql);
        $rs = mysqli_fetch_array($result);
        $count = $rs['total'];
        mysqli_free_result($result);

        $nums = 500;//每次查询得记录数，根据服务器性能等实际情况调整大小
        $step = $count / $nums;
        $step = ceil($step);//根据每次查询记录数计算查询次数
        
        $imgarr=array();//照片数组
        
        for($s = 1; $s <= $step; $s++) {//循环导出数据到CSV文件
            $start = ($s - 1) * $nums;
            $result = mysqli_query($con,"SELECT * FROM `$sjk` where id in ($id) ORDER BY `id` LIMIT {$start},{$nums}");
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    if(!empty($row['img'])){
                        $im = ltrim($row['img'],'/');
                        array_push($imgarr,$im);
                    }
                    fputcsv($fp, $row);
                }
                mysqli_free_result($result);
                ob_flush();
                flush();
            }
        }
        fclose($fp);
        array_push($imgarr,$fileName.'.csv');//将CSV文件添加到待压缩文件数组
        
        $zipname = "$fileName.zip";
        $zip = new ZipArchive();
        $zip->open($zipname,ZipArchive::CREATE);  //打开压缩包
        foreach($imgarr as $file){
          $zip->addFile($file,basename($file));  //向压缩包中添加文件
        }
        $zip->close(); //关闭压缩包
        if(downloadFile($zipname,$zipname)){
            @unlink($zipname);
        }
        @unlink($fileName.'.csv');
        die();
    }
    if($mode=='qianru'){//数据迁入
        $sjk = $_POST['q'];//待操作数据库
        $filename = $_POST['f'];//上传的ZIP文件名
        $oldname = $_POST['o'];//原始zip文件名，用于取csv文件名
        if(unzip("uploads/$filename","uploads")){
            @unlink("uploads/$filename");
        }
        $on = explode(".",$oldname);
        $csv = 'uploads/'.$on[0].'.csv';
        if(file_exists($csv)){
            mysqli_query($con, "set names UTF8");
            $handle = fopen($csv,'r');
            while (($d = fgetcsv($handle)) !== false) {
                $id = $d[0];
                $sql = "select id from `$sjk` where id=$id";
                $requ = mysqli_query($con,$sql);
                if(mysqli_num_rows($requ)){
                    //ID重复，更新
                    mysqli_free_result($requ);
                    $sql = "update $sjk set zcbh='$d[1]',xlh='$d[2]',zclx=$d[3],cw='$d[4]',zczt=$d[5],
    				bm=$d[6],bgr='$d[7]',dz='$d[8]',cgsj=$d[9],rzsj=$d[10],zbsc=$d[11],sysc=$d[12],
    				pp='$d[13]',xh='$d[14]',zcly='$d[15]',zcjz=$d[16],gg='$d[17]',bz='$d[18]',img='$d[19]',
    				wlbs=$d[20],ip='$d[21]',xsq='$d[22]',yp=$d[23],nc=$d[24],ll='$d[25]' 
    				where id=$id";
                    mysqli_query($con,$sql);
                }else{
                    //ID不存在，插入
                    mysqli_free_result($requ);
                    $sql = "insert into $sjk (id,zcbh,xlh,zclx,cw,zczt,bm,bgr,dz,cgsj,rzsj,zbsc,
                                              sysc,pp,xh,zcly,zcjz,gg,bz,img,wlbs,ip,xsq,yp,nc,ll) values 
								 ($id,'$d[1]','$d[2]',$d[3],'$d[4]',$d[5],$d[6],'$d[7]','$d[8]',
								 $d[9],$d[10],$d[11],$d[12],'$d[13]','$d[14]','$d[15]',$d[16],
								 '$d[17]','$d[18]','$d[19]',$d[20],'$d[21]','$d[22]',$d[23],$d[24],'$d[25]')";
                    echo $sql;
                    mysqli_query($con,$sql);
                }
            }
            fclose($handle);
            @unlink($csv);
			die('{"status":"1","msg":"迁入完成，自己看看行不行"}');
        }else{
            die('{"status":"0","msg":"数据文件不存在"}');
        }
    }
	if($mode == 'backupdb'){//备份数据库
		require_once 'phpmysqlbackup/vendor/autoload.php';
		$config = [
			'host'        => $dbhost,
			'database'    => $dbname,
			'user'        => $dbuser,
			'password'    => $dbpwd,
			'port'        => '3306',
			'charset'     => 'utf8'
		];
		$dir = "phpmysqlbackup".DIRECTORY_SEPARATOR."backup";
		/*
		backup($path = '备份路径', 
		$tableArray = [需要备份的表集合], 
		$bool = '是否同时备份数据 默认false',
		['is_compress' => '是否写入内容文件进行压缩',
		'is_download' => '是否进行下载'])
		*/
		//$data = cocolait\sql\Backup::instance($config)->backUp($dir,[],true,['is_compress' => 0]);
		$data = cocolait\sql\Backup::instance($config)->backUp($dir,['bgszichan','config','danwei','juese','log','system_menu','user','wgbzichan','xinxizichan','zclx','zhuangtai'],true,['is_compress' => 0]);
		if($data['code'] == 200){
			$sfile = $dir.$data['fileName'].'.sql';
			$sfile=mysqli_real_escape_string($con,$sfile);
			$sql = "insert into `backup` (file) values ('$sfile')";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result = '{"status":"1","msg":"ok"}';
				rlog("备份数据库成功");
			}else{
				$result = '{"status":"-1","msg":"备份失败，请重试"}';
				@unlink($sfile);
				rlog("备份数据库失败");
			}
		}else{
			$result = '{"status":"0","msg":"备份失败，请重试"}';
			rlog("备份数据库失败");
		}
	}
	if($mode == 'backupfile'){//备份文件
		$filename = date('YmdHis', time());
		$zip = new ZipArchive();
		$zipName = "backup".DIRECTORY_SEPARATOR.$filename.".zip";
		$sfolder = ".";
		$zip->open($zipName,ZipArchive::CREATE);
		ZipFolder($sfolder,$zip);
		$zip->close();
		$zipName=mysqli_real_escape_string($con,$zipName);
		$sql = "insert into `backup` (file, type) values ('$zipName', 1)";
		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result = '{"status":"1","msg":"ok"}';
			rlog("备份文件成功");
		}else{
			$result = '{"status":"-1","msg":"备份失败，请重试"}';
			@unlink($zipName);
			rlog("备份文件失败");
		}
	}
	if($mode == 'getbackupfile'){//获取备份文件列表
		$tp = array("数据库","文件");
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select * from backup where 1=1";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$size = filesize($rs['file']);
			
			$mod = 1024;
			$units = explode(' ','B KB MB GB TB PB');
			for ($i = 0; $size > $mod; $i++) {
				$size /= $mod;
			}
			$size = round($size, 2) . ' ' . $units[$i];
			
			$result.='{"id":'.$rs['id'].',"file":"'.$rs['file'].'",
						"shijian":"'.$rs['shijian'].'",
						"size":"'.$size.'",
						"type":"'.$tp[$rs['type']].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		$result = str_replace('\\','\\\\',$result);
		rlog("获取备份文件");
	}
	if($mode == 'downloadbackupfile'){//下载备份文件
		$id = $_GET['id'];
		$tp = array("sql","zip");
		$sql = "select file,type from backup where id=$id";
		$requ = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($requ);
		$f = $rs['file'];
		$filename = date('YmdHis', time()).".".$tp[$rs['type']];
		$result = downloadFile($f,$filename);
		rlog("下载备份文件$id");
	}
	if($mode == 'deletebackupfile'){//删除备份文件
		$id = $_POST['id'];
		$sql = "select file from backup where id=$id";
		$requ = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($requ);
		$f = $rs['file'];
		$sql = "delete from backup where id=$id";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			@unlink($f);
			$result = '{"status":"1","msg":"ok"}';
			rlog("删除备份文件 $id 成功");
		}else{
			$result = '{"status":"0","msg":"删除失败"}';
			rlog("删除备份文件 $id 失败");
		}
	}
	if($mode == 'huifubackupsql'){//恢复数据库备份
		require_once 'phpmysqlbackup/vendor/autoload.php';
		$config = [
			'host'        => $dbhost,
			'database'    => $dbname,
			'user'        => $dbuser,
			'password'    => $dbpwd,
			'port'        => '3306',
			'charset'     => 'utf8'
		];
		$dir = "phpmysqlbackup".DIRECTORY_SEPARATOR."backup";
		$id = $_POST['id'];
		$sql = "select file from `backup` where type=0 and id=$id";
		$requ = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($requ);
		$f = $rs['file'];
		$name = basename($f);
		$data = cocolait\sql\Backup::instance($config)->recover($name,$dir);
		if($data['code'] == 200){
			$result='{"status":"1","msg":"ok"}';
			rlog("恢复备份数据库 $id 成功");
		}else{
			$result='{"status":"0","msg":"恢复失败"}';
			rlog("恢复备份数据库 $id 失败");
		}
	}
	if($mode == 'downloadnow'){//导出本次查询结果
			rlog("导出 xinxi 本次查询 资产");
			$sql = $_SESSION['sql'];
			require_once './Excel/PHPExcel/IOFactory.php';
			require_once './Excel/PHPExcel.php';
			$reader = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $reader->load('xxzx.xlsx');
			$sheet = $excel->getSheet(0);
			$row=2;
			$requ=mysqli_query($con,$sql);
			if (!$requ) {
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}

			while($rs=mysqli_fetch_array($requ)){
				$excel->getActiveSheet()->getRowDimension($row)->setRowHeight(22);//设置行高
				$excel->getActiveSheet(0)->setCellValue('A'.$row, $rs['zclx']);
				$excel->getActiveSheet(0)->setCellValue('B'.$row, $rs['zczt']);
				$excel->getActiveSheet(0)->setCellValue('C'.$row, $rs['zcbh']);
				$excel->getActiveSheet(0)->setCellValue('D'.$row, $rs['xlh']);
				$excel->getActiveSheet(0)->setCellValue('E'.$row, $rs['cw']);
				$excel->getActiveSheet(0)->setCellValue('F'.$row, $rs['bgr']);
				$excel->getActiveSheet(0)->setCellValue('G'.$row, $rs['bm']);
				$excel->getActiveSheet(0)->setCellValue('H'.$row, $rs['dz']);
				$excel->getActiveSheet(0)->setCellValue('I'.$row, $rs['pp']);
				$excel->getActiveSheet(0)->setCellValue('J'.$row, $rs['xh']);
				$excel->getActiveSheet(0)->setCellValue('K'.$row, $rs['gg']);
				$excel->getActiveSheet(0)->setCellValue('L'.$row, date("Y-m-d",$rs['cgsj']));
				$excel->getActiveSheet(0)->setCellValue('M'.$row, date("Y-m-d",$rs['rzsj']));
				$excel->getActiveSheet(0)->setCellValue('N'.$row, $rs['zbsc']);
				$excel->getActiveSheet(0)->setCellValue('O'.$row, $rs['sysc']);
				$excel->getActiveSheet(0)->setCellValue('P'.$row, $rs['zcly']);
				$excel->getActiveSheet(0)->setCellValue('Q'.$row, $rs['zcjz']);

				$wlbs = array("未指定","内网","外网");
				$wlbs = $wlbs[$rs['wlbs']];
				$excel->getActiveSheet(0)->setCellValue('R'.$row, $wlbs);
				$excel->getActiveSheet(0)->setCellValue('S'.$row, $rs['ip']);
				$excel->getActiveSheet(0)->setCellValue('T'.$row, $rs['xsq']);
				$excel->getActiveSheet(0)->setCellValue('U'.$row, $rs['yp']);
				$excel->getActiveSheet(0)->setCellValue('V'.$row, $rs['nc']);
				$excel->getActiveSheet(0)->setCellValue('X'.$row, $rs['bz']);
				$img = $rs['img'];
				if(!empty($img)){
					$excel->setActiveSheetIndex(0)->getStyle('W')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet(0)->getColumnDimension('W')->setWidth(10);
					if(substr($img,0,7) != 'http://'){
						$img = substr($img,1);
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setPath($img);
						$objDrawing->setWidth(30);
						$objDrawing->setHeight(22);
						$objDrawing->setCoordinates('W'.$row);
						$objDrawing->setOffsetX(0);
						$objDrawing->setOffsetY(0);
						$objDrawing->setWorksheet($excel->getActiveSheet());
						$img = $url.$img;
						$excel->getActiveSheet()->getCell('W'.$row)->getHyperlink()->setUrl($img);
					}else{
						$excel->getActiveSheet()->setCellValue('W'.$row, $img);
						$excel->getActiveSheet()->getCell('W'.$row)->getHyperlink()->setUrl($img);
					}
				}
				
				$row++;
			}
            $row--;
            $excel->getActiveSheet()->setAutoFilter('A1:W'.$row);//添加自动筛选，可在标题栏筛选数据
			$sheet->getDefaultRowDimension()->setRowHeight(22);//设置默认行高
			$file='xinxizichan_'.date("Y-m-d").'.xlsx';
			$objWrite = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');  
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器数据excel07文件
			header("Content-Type: application/force-download"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Type: application/download"); 
			header("Content-Disposition:attachment;filename=$file");  
			header("Pragma: no-cache"); 
			header('Cache-Control: max-age=0');//禁止缓存
			$objWrite->save('php://output');
			exit;
	}
	if($mode == 'plxg'){//批量修改状态、分组
		$cz = $_POST['cz'];
		$id = $_POST['id'];
		$zhi = $_POST['v'];
		if($cz == 'zt'){
			$cz = 'zczt';
		}else{
			$cz = 'bm';
		}
		$id = explode(",", $id);
		foreach($id as $v){
			$sjk = 'xinxizichan';
			$sql = "select ll from $sjk where id=$v";
			$r = mysqli_query($con,$sql);
			$rs = mysqli_fetch_array($r);
			$ll = $rs['ll'];//该资产原履历
			$ll_json = json_decode($ll,true);
			$new_ll = end($ll_json);
			$new_ll['user'] = $_SESSION['admin'];
			$new_ll['time'] = date('Y-m-d H:i:s');
			$new_ll['act'] = '修改';
			$new_ll['new'][$cz] = $zhi;
			array_push($ll_json,$new_ll);
			$ll = json_encode($ll_json,JSON_UNESCAPED_UNICODE);
			$sql = "update $sjk set $cz=$zhi,ll='$ll' where id=$v";
			mysqli_query($con,$sql);
			if(mysqli_affected_rows($con)){
				rlog("修改 $sjk 资产 $v 成功 ");
			}else{
				rlog("修改 $sjk 资产 $v  no ");
			}
		}
		$result='{"status":"1"}';
	}
	if($mode == 'haocairuku'){//耗材入库
		$d = $_POST['data'];
		$d = json_decode($d);
		$name = $d->name;
		$pp = $d->pp;
		$gg = $d->gg;
		$dj = $d->dj;
		$num = $d->num;
		$sql = "insert into haocai (name,pp,gg,dj,num,zs) values ('$name','$pp','$gg',$dj,$num,$num)";
		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
			$result='{"status":"1","msg":"ok"}';
			rlog("$name 耗材入库成功 ");
		}else{
			$result='{"status":"0","msg":"入库失败，请重试"}';
			rlog("$name 耗材入库失败");
		}
	}
	if($mode == 'gethaocailist'){//获取耗材信息
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$fun = $_GET['fun'];
		if($fun == 'chuku'){
			$fun = " and num>0";
		}else{
			$fun='';
		}
		if(isset($_GET['keyword'])){
			$kw = $_GET['keyword'];
			$kw = " and (name like '%$kw%' or pp like '%$kw%' or gg like '%$kw%')";
		}else{
			$kw = "";
		}
		$sqls="select * from haocai where 1=1 $fun $kw order by num desc,id desc";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"name":"'.$rs['name'].'","zs":"'.$rs['zs'].'",
			"gg":"'.$rs['gg'].'","dj":"'.$rs['dj'].'","num":"'.$rs['num'].'",
			"rksj":"'.$rs['rksj'].'","pp":"'.$rs['pp'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog("获取耗材列表");
	}
	if($mode == 'mhsearchhaocai'){//耗材模糊搜索(废弃)
		$s = $_REQUEST['s'];
		$sql = "select id from haocai where name like '%$s%' or pp like '%$s%' or gg like '%$s%'";
		$requ=mysqli_query($con,$sql);
		$rs=mysqli_fetch_array($requ);
		if($rs){
			$result = $rs['id'];;
		}else{
			$result = 0;
		}
	}
	if($mode == 'haocaichuku'){//耗材出库
		$d = $_REQUEST['data'];
		$d = json_decode($d);
		$hc = $d->hc;
		$dw = $d->dw;
		$lyr = $d->lyr;
		$num = $d->num;
		$sql = "update haocai set num=num-$num where id=$hc";
		mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)){
			$sql = "select name,pp,gg from haocai where id=$hc";
			$requ = mysqli_query($con,$sql);
			$rs = mysqli_fetch_array($requ);
			$name= $rs['name'];
			$pp= $rs['pp'];
			$gg= $rs['gg'];
			$sql = "insert into chuku (lyr,dw,hcid,name,pp,gg,num) values 
					('$lyr','$dw',$hc,'$name','$pp','$gg',$num)";
			mysqli_query($con,$sql);
			if(mysqli_insert_id($con)){
				$result='{"status":"1","msg":"ok"}';
				rlog("$hc 耗材出库成功");
			}else{
				$result='{"status":"0","msg":"出库失败，请重试"}';
				$sql = "update haocai set num=num+$num where id=$hc";
				mysqli_query($con,$sql);
				rlog("$hc 耗材出库失败,数据库写出库记录失败");
			}
		}else{
			$result='{"status":"0","msg":"出库失败"}';
			rlog("$hc 耗材出库失败,改数据库数量失败");
		}
	}
	if($mode == 'gethcckjl'){//耗材出库记录
		$id = $_REQUEST['id'];
		$p=$_GET['page'];
		$l=$_GET['limit'];
		$p=($p-1)*$l;
		$sqls="select * from chuku where hcid=$id order by id desc";
		$sql=$sqls." limit $p,$l";
		$requ=mysqli_query($con,$sqls);
		$num=mysqli_num_rows($requ);
		$requ=mysqli_query($con,$sql);
		$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
		while($rs=mysqli_fetch_array($requ)){
			$result.='{"id":'.$rs['id'].',"lyr":"'.$rs['lyr'].'","dw":"'.$rs['dw'].'",
			"lysj":"'.$rs['lysj'].'","num":"'.$rs['num'].'"},';
		}
		$result=rtrim($result,',');
		$result.=']}';
		rlog('获取耗材出库记录');
	}
	if($mode=='wxscancha'){//微信扫码查询
	    $code=$_POST['code'];
		$sqls="select count(*) as num from information_schema.columns where table_name = 'xinxizichan' and column_name = 'wlbs'";
		$sql = "select id from `xinxizichan` where zcbh='$code' or xlh='$code'";
		$resul=mysqli_query($con,$sql);
		$v=mysqli_num_rows($resul);
		if($v==1){
		    $rs=mysqli_fetch_array($resul);
		    $id = $rs['id'];
		}else{
		    $id=0;
		}
		$result='{"id":"'.$id.'"}';
	}
	if($mode=='wxmhscancha'){//微信模糊查询
	    $mhss=$_POST['code'];
		$sqls="select count(*) as num from information_schema.columns where table_name = 'xinxizichan' and column_name = 'wlbs'";
		$sql = "select id from `xinxizichan` as a where 
		                           a.zcbh like '%$mhss%' 
								or a.xlh like '%$mhss%' 
								or a.bgr like '%$mhss%'
								or a.dz like '%$mhss%'
								or a.pp like '%$mhss%'
								or a.xh like '%$mhss%'
								or a.gg like '%$mhss%'
								or a.bz like '%$mhss%'
								or a.ip like '%$mhss%'
								or a.zcly like '%$mhss%'";
		$resul=mysqli_query($con,$sql);
		$rs=mysqli_fetch_array($resul);
		if($rs){
		    $id = $rs['id'];
		}else{
		    $id=0;
		}
		$result='{"id":"'.$id.'"}';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($mode=='searchzichandemo'){//资产查询---测试
		$dw=$_GET['dw'];//部门代码
		$sjk=array('','xinxizichan','bgszichan','wgbzichan');
		$sjk=$sjk[$dw];//要操作的数据库
		$bmz = $_SESSION['bm'];//角色权限部门ID 
		if($bmz == 0){
			$dwa='';
			$dwz = '';
		}else{
			$dwa=" and id in ($bmz)";
			$dwz = " and a.bm in ($bmz)";
		}
		if(isset($_POST['columns'])){//获取筛选数据
			$res='{';
			$col = urldecode($_POST['columns']);
			header('content-type:application/json,charset=UTF-8');
			$sql = "select name from zclx where status=1 and zcfl=$dw";//资产类型
			$requ=mysqli_query($con,$sql);
			$res.='"zclx":[';
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"zczt":[';
			$sql = "select name from zhuangtai where status=1";//状态
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"bm":[';
			$sql = "select name from danwei where status=1 $dwa";//单位
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['name'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"pp":[';
			$sql = "select DISTINCT pp from $sjk where 1=1";//品牌
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['pp'].'",';
			}
			$res=rtrim($res,',');
			$res.='],"cw":[';
			$sql = "select DISTINCT cw from $sjk where 1=1";//财务名
			$requ=mysqli_query($con,$sql);
			while($rs=mysqli_fetch_array($requ)){
				$res.='"'.$rs['cw'].'",';
			}
			$res=rtrim($res,',');
			$res.=']}';
			die($res);
		}else{
			//获取资产数据
			if(isset($_REQUEST['filterSos'])){//筛选
				$shaixuan = json_decode(urldecode($_REQUEST['filterSos']));
				$xx='';
				foreach ($shaixuan as $item) {
					$v=$item->values;
					if(empty($v)){
						continue;
					}
					$sj = $item->field;
					if($sj == 'pp'){//筛选品牌
						$str = '';
						foreach($v as $a){
							$str .= "'$a',";
						}
						$str = rtrim($str,',');
						$xx.=" and a.pp in ($str)";
					}elseif($sj == 'cw'){//筛选财务名
						$str = '';
						foreach($v as $a){
							$str .= "'$a',";
						}
						$str = rtrim($str,',');
						$xx.=" and a.cw in ($str)";
					}else{
						if($sj=='zclx'){$sjkk='zclx';}//筛选类型
						if($sj=='zczt'){$sjkk='zhuangtai';}//筛选状态
						if($sj=='bm'){$sjkk='danwei';}//筛选部门
						$d = '';
						foreach($v as $a){
							$sql = "select id from $sjkk where name='$a'";
							//echo 'sql:'.$sql.'<br>';
							$requ = mysqli_query($con,$sql);
							$rs = mysqli_fetch_array($requ);
							$d.=$rs['id'].',';
						}
						$d=rtrim($d,',');
						$xx.=" and a.$sj in ($d)";
					}
				}
				//echo $xx.'<br>';
			}else{
				$xx = '';
			}


			$p=$_GET['page'];
			$l=$_GET['limit'];
			$p=($p-1)*$l;
			
			if(isset($_GET['rzqi'])){
				$rzqi = $_GET['rzqi'];
				$rzzhi = $_GET['rzzhi'];
				$mhss = $_GET['mhss'];
				if($rzqi == ''){
					$qi = 0;
				}else{
					$qi = strtotime($rzqi);
				}
				if($rzzhi == ''){
					$zi = 9999999999;
				}else{
					$zi = strtotime($rzzhi);
				}
				$shijian = " and a.rzsj between $qi and $zi";
				if(empty($mhss)){
					$mhss='';
				}else{
					$mhss = " and (a.zcbh like '%$mhss%' 
								or a.cw like '%$mhss%'
								or a.xlh like '%$mhss%' 
								or a.bgr like '%$mhss%'
								or a.dz like '%$mhss%'
								or a.pp like '%$mhss%'
								or a.xh like '%$mhss%'
								or a.gg like '%$mhss%'
								or a.bz like '%$mhss%'
								or a.ip like '%$mhss%'
								or a.zcly like '%$mhss%')";
				}
			}else{
				$shijian = '';
				$mhss = '';
			}
			
			if($dw == 1){
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.wlbs as wlbs,a.ip as ip,a.yp as yp,
							  a.xsq as xsq,a.nc as nc,a.img as img,
							  a.zclx as zclxid,a.zczt as zcztid,
							  a.dotime as dotime,a.cw as cw,
							  zhuangtai.icon as icon,zhuangtai.color as color,
							  zhuangtai.name as zczt,a.bm as bmid,
							  danwei.name as bm,zclx.name as zclx 
							  from $sjk as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $shijian $mhss $dwz";
			}else{
				$sqls="select a.id as id,a.zcbh as zcbh,a.xlh as xlh,
							  a.bgr as bgr,a.dz as dz,a.cgsj as cgsj,
							  a.rzsj as rzsj,a.zbsc as zbsc,a.sysc as sysc,
							  a.pp as pp,a.xh as xh,a.zcly as zcly,
							  a.zcjz as zcjz,a.gg as gg,a.bz as bz,
							  a.img as img,danwei.name as bm,
							  a.bm as bmid,a.zclx as zclxid,a.zczt as zcztid,
							  zclx.name as zclx,zhuangtai.name as zczt 
							  from `$sjk` as a 
							  left join zclx on a.zclx=zclx.id 
							  left join zhuangtai on a.zczt=zhuangtai.id 
							  left join danwei on a.bm=danwei.id 
							  where 1=1 $xx $dwz";
			}		  
			$sql=$sqls." limit $p,$l";
			$_SESSION['sql']=$sqls;
			$requ=mysqli_query($con,$sqls);
			if (!$requ) {
				echo $sqls.'<br>';
				printf("Error: %s\n", mysqli_error($con));
				exit();
			}
			$num=mysqli_num_rows($requ);
			$requ=mysqli_query($con,$sql);
			$result='{"code": 0,"msg": "","count": '.$num.',"data": [';
			while($rs=mysqli_fetch_array($requ)){
				$cgsj = date("Y-m-d",$rs['cgsj']);
				$rzsj = date("Y-m-d",$rs['rzsj']);
				$result.='{"id":"'.$rs['id'].'","zcbh":"'.$rs['zcbh'].'","xlh":"'.$rs['xlh'].'",
						   "zclx":{"name":'.$rs['zclxid'].',"value":"'.$rs['zclx'].'"},
						   "zczt":{"name":'.$rs['zcztid'].',"value":"'.$rs['zczt'].'","icon":"'.$rs['icon'].'","color":"'.$rs['color'].'"},
						   "bm":{"name":'.$rs['bmid'].',"value":"'.$rs['bm'].'"},
						   "bgr":"'.$rs['bgr'].'","dz":"'.$rs['dz'].'",
						   "cgsj":"'.$cgsj.'","rzsj":"'.$rzsj.'","dotime":"'.$rs['dotime'].'",
						   "zbsc":"'.$rs['zbsc'].'","sysc":"'.$rs['sysc'].'",
						   "pp":"'.$rs['pp'].'","xh":"'.$rs['xh'].'","zcly":"'.$rs['zcly'].'",
						   "zcjz":"'.$rs['zcjz'].'","gg":"'.$rs['gg'].'",
						   "bz":"'.$rs['bz'].'","img":"'.$rs['img'].'"';
				if($dw == 1){
					$w=array("未指定","内网","外网");
					$result.=',"wlbs":{"name":'.$rs['wlbs'].',"value":"'.$w[$rs['wlbs']].'"},
							   "ip":"'.$rs['ip'].'","xsq":"'.$rs['xsq'].'","cw":"'.$rs['cw'].'",
							   "yp":"'.$rs['yp'].'","nc":"'.$rs['nc'].'"},';
				}else{
					$result.='},';
				}
			}
			$result=rtrim($result,',');
			$result.=']}';
		}
		rlog("获取 $sjk  资产列表");
	}
	
	

	echo $result;
}	

function rlog($a){//记录日志
	//session_start();
	global $con;
	if(isset($_SESSION['user'])){
		$u=$_SESSION['user'];
	}else{
		$u='未登录';
	}
	$ip = getCilentIP();
	$sql = "insert into log (user,action,ip) values ('$u','$a','$ip')";
	mysqli_query($con,$sql);
}	

function getCilentIP(){//获取客户端真实IP
	$ip = '';
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_FROM', 'REMOTE_ADDR') as $v) {
		if (isset($_SERVER[$v])) {
			if (! preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $_SERVER[$v])) {
				continue;
			} 
			 $ip = $_SERVER[$v];
		}
	}
	return $ip;
}	

// 获取菜单列表
function getMenuList(){
	global $con;
	$sql = "select id,pid,title,icon,href,sort,status from system_menu where 1=1 order by id";
	$requ = mysqli_query($con,$sql);
	$menuList = array();
	$zt = array("禁用","启用");
	while($rs = mysqli_fetch_array($requ)){
		$a=array("id"=>$rs['id'],"pid"=>$rs['pid'],"treeName"=>$rs['title'],
				 "icon"=>$rs['icon'],"url"=>$rs['href'],"sort"=>$rs['sort'],
				 "szt"=>array("name"=>$rs['status'],"value"=>$zt[$rs['status']]));
		array_push($menuList,$a);
	}
	$menuList = buildMenuChild(0, $menuList);
	return $menuList;
}

//递归获取子菜单
function buildMenuChild($pid, $menuList){
	$treeList = [];
	foreach ($menuList as $v) {
		if ($pid == $v['pid']) {
			$node = $v;
			$child = buildMenuChild($v['id'], $menuList);
			if (!empty($child)) {
				$node['treeList'] = $child;
			}
			$treeList[] = $node;
		}
	}
	return $treeList;
}
function changeMenu($d){
	global $con;
	global $err;
	foreach($d as $v){
		$id = $v["id"];
		if(array_key_exists('pid',$v)){
			$pid = $v["pid"];
		}else{
			$pid = 0;
		}
		$title = $v["treeName"];
		$icon = $v["icon"];
		$href = $v["url"];
		$sort = $v["sort"];
		$status = $v["szt"]["name"];
		$sql = "insert into system_menu (id,pid,title,icon,href,sort,status) values 
										($id,$pid,'$title','$icon','$href',$sort,$status)";
		mysqli_query($con,$sql);
		if(mysqli_insert_id($con)){
		}else{
			$err++;
		}
		if(array_key_exists('treeList',$v)){
			changeMenu($v['treeList']);
		}
	}
	return $err;
}
function downloadFile($filePath,$showName) {//下载文件
    if (is_file($filePath)) {
        //打开文件
        $file = fopen($filePath,"rb");
        //返回的文件类型
        Header("Content-type: application/octet-stream");
        //按照字节大小返回
        Header("Accept-Ranges: bytes");
        //返回文件的大小b
        Header("Accept-Length: ".filesize($filePath));
        //这里设置客户端的弹出对话框显示的文件名
        Header("Content-Disposition: attachment; filename=".$showName);
        //一次性将数据传输给客户端
        //echo fread($file, filesize($filePath));
        //一次只传输1024个字节的数据给客户端
        //向客户端回送数据
        $buffer=1024;//
        //判断文件是否读完
        while (!feof($file)) {
            //将文件读入内存
            $file_data = fread($file, $buffer);
            //每次向客户端回送1024个字节的数据
            echo $file_data;
        }
        return true;
    }else {
        return false;
    }
}
function unzip($filePath, $path) {//解压缩
    if (!file_exists($path)) {
        mkdir($path,0777,true);
    }
    if (empty($filePath)) {
        return false;
    }
    $zip = new ZipArchive();
    if ($zip->open($filePath) === true) {
        $zip->extractTo($path);
        $zip->close();
        return true;
    } else {
        return false;
    }
}
function ZipFolder($path,&$zip){//压缩文件夹
    if(!is_dir($path)){
        return false;
    }
    if($dh = opendir($path)){
        while (($file = readdir($dh)) !== false){
            if(in_array($file,['.','..','backup',null])) continue;
            if(is_dir($path.DIRECTORY_SEPARATOR.$file)){
                $zip->addEmptyDir($path.DIRECTORY_SEPARATOR.$file);
                ZipFolder($path.DIRECTORY_SEPARATOR.$file,$zip);
            }else{
				$zip->addFile($path.DIRECTORY_SEPARATOR.$file,$path.DIRECTORY_SEPARATOR.$file);
            }
        }
    }
}