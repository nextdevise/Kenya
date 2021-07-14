<?php
if($_POST['mode'] == 'deleteimage'){
	$file = $_POST['file'];
	if(unlink($file)){echo 1;}else{echo 0;}
	
}
?>