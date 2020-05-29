<?php

	$res = shell_exec("python3 compare_output.py");
	echo "$res";
?>

<script type="text/javascript" src="http://coderoj.com/style/lib/jquery/jquery.min.js"></script>
<script type="text/javascript">
	function submitCode(){
		
		var data1 = {
			sourceCode: btoa($("#code").val()),
			input: btoa($("#input").val()),
			expectedOutput: btoa($("#expectedOutput").val()),
			language: 1,
			time : 2
		}

		var data = {};
		data['submitCode'] = data1;

		$("#outputResponse").html("Loading......");
		$.post("api.php",data1,function(response){
			$("#outputResponse").html(response);
			response = JSON.parse(response);
			
        	if(response.error == 0){
        		var status =response.status;
        		var status = JSON.parse(status);
        		$("#output").val(status.output);
        		$("#outputResponse").html(status.time + " " +status.description);
    		}
    		else $("#outputResponse").html(response.errorMsg);
    	});
	}
</script>
	<center><h1>Welcome Online Compiler</h1></center>
	<div id="outputResponse"></div>
	<textarea rows="10" cols="50" id="code" placeholder="code"></textarea>
	<textarea rows="10" cols="50" id="input" placeholder="input"></textarea>
	<textarea rows="10" cols="50" id="expectedOutput" placeholder="expectedOutput"></textarea><br/>
	<button onclick="submitCode()">Submit</button>
	<div style="margin-top: 5px;"></div>
	<textarea rows="10" cols="50" id="output" placeholder="output"></textarea><br/>
	
