<?php

$servername='localhost';
$username = 'root';
$password='';
$dbname = 'employee';
//$table = 'emp_details';

$link = mysqli_connect($servername,$username,$password,$dbname);



$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
	case 'GET':
		$sql = "select* from emp_detail ORDER by emp_id DESC ";
		break;

	case 'POST':
		$data = file_get_contents("php://input");

		echo $data;
		$data=json_decode($data);
		/*$emp_id = mysqli_real_escape_string($link,$_POST['empID']);
		$fname = mysqli_real_escape_string($link,$_POST['FName']);
		$mname = mysqli_real_escape_string($link,$_POST['MName']);
		$lname = mysqli_real_escape_string($link,$_POST['LName']);
		$city = mysqli_real_escape_string($link,$_POST['City']);
		$salary = mysqli_real_escape_string($link,$_POST['Salary']);
		*/
		/*$input=explode('&',$data);
		print_r($input);
		$i=0;
		$value=[];
		foreach ($input as $v) {
			$value[$i++]=explode('=',$v);
		}
		print_r($value);
		$sql = "INSERT into emp_detail (emp_id,fname,mname,lname,city,salary) VALUES (".$value[0][1].",'".$value[1][1]."','".$value[2][1]."','".$value[3][1]."','".$value[4][1]."',".$value[5][1].")";
		echo $sql;*/

			$sql = "INSERT into emp_detail (emp_id,fname,mname,lname,city,salary) VALUES (".$data->emp_id.",'".$data->fname."','".$data->mname."','".$data->lname."','".$data->city."',".$data->salary.")";
		break;

	case 'DELETE':
		echo 'In delete case';
		$data=$_GET['emp_id'];
		echo $data;
		//$data = json_decode(file_get_contents("php://input"));
		//$emp_id = mysqli_real_escape_string($link,$data.emp_id);
		//$value=explode('=',$data);
		$sql = "DELETE FROM emp_detail WHERE emp_id=".$data;
		//'".$emp_id."'"
		break;
	
	default:
		echo 'In default';
		break;
}

$result = mysqli_query($link,$sql);
if(!$result)
{
	http_response_code(404);
	
}

$records = array();
if($method == 'GET')
{
	if(mysqli_num_rows($result)!=0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$records[] = $row;
		}
	}

	echo $json_info = json_encode($records);
}

if($method == 'POST')
{
	echo true;
	//json_encode("");
	//header('Location:index.html');
}

if($method == 'DELETE')
{
	echo true;
	//header('Location:index.html');
}

?>