<?php
include "config.php";

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
	case 'GET' : $sql = "select * from employee";
	break;
}



$res = mysqli_query($con,$sql);


if(!$res){
http_response_code(404);
die(mysqli_error());
}
$arr = array();
if($method == 'GET'){

while ($row = mysqli_fetch_assoc($res)) {
	



$arr[] = $row;

}
echo json_encode($arr);
}

//print_r($row);
?>