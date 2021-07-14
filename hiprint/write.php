<?php
include_once("../config.php");
$ka = $_POST['ka'];
$kk = $_POST['kk'];
$html = $_POST['html'];
$sql = "update config set kapian='$ka',kpyl='$html' where title='$kk'";
mysqli_query($con,$sql);
if(mysqli_affected_rows($con)){
    echo '1';
}else{
    echo '0';
}
exit;