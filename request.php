<?php
	
	if(isset($_POST['submit'])){
		$reqData = $_POST['submit'];
		include "compiler/cpp.php";
		$data = array();
		$data['sourceCode']=$reqData['sourceCode'];
		$data['input']=$reqData['input'];
		$data['expectedOutput']=$reqData['expectedOutput'];
		$data['time']=1;
		$compiler = new cpp($data);
		$output = $compiler->execute();
		$output = json_encode($output,true);
		echo "$output";
	}

	
?>