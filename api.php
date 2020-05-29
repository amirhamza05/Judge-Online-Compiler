<?php
	header('Access-Control-Allow-Origin: *');
	$ok=1;
	if(!isset($_POST['sourceCode']))$ok=0;
	if(!isset($_POST['language']))$ok=0;
	if(!isset($_POST['input']))$ok=0;
	if(!isset($_POST['expectedOutput']))$ok=0;
	if(!isset($_POST['time']))$ok=0;

	$response = array();

	if($ok==0){
		$response['error'] = 1;
		$response['errorMsg'] = "Some Field Missing";
		$response=json_encode($response,true);
		echo "$response";
	}

	else{

		$data = array();
		$data['sourceCode']=base64_decode($_POST['sourceCode']);
		$data['input']=base64_decode($_POST['input']);
		$data['expectedOutput']=base64_decode($_POST['expectedOutput']);
		$data['time']=$_POST['time'];
		
		include "compiler/cpp.php";
		$compiler = new cpp($data);
		$output = $compiler->execute();
		$output = json_encode($output,true);

		$response['error'] = 0;
		$response['status'] = $output;
	}

	$response = json_encode($response,true);
	echo "$response";

?>
