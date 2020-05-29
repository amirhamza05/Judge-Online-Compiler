<?php
	class cpp{
		
		var $compilerName = "g++";
		var $sourceCodeFileName = "main.cpp";
		var $inputFileName = "input.txt";
		var $expectedOutputFileName = "expected_output.txt";
		var $outputFileName = "output.txt";
		var $errorFileName = "error.txt";

		var $executableFile;
		var $compileCommand;	
		var $commandError;

		var $sourceCode;
		var $input;
		var $expectedOutput;
		var $output;
		var $timeLimit;
		var $out;

		
		var $executionStartTime = 0;
		var $executionEndTime = 0;
		var $executionTotalTime = 0;

		var $compailerError = 0;
		var $runTimeError = 0;

		var $processResultData = array();

		function __construct($data){
			$this->sourceCode = $data['sourceCode'];
			$this->input = $data['input'];
			$this->expectedOutput = $data['expectedOutput'];
			$this->timeLimit = $data['time'];
			$exeTimeLimit = $this->timeLimit +1;
			$this->out = "timeout ".$exeTimeLimit."s ./a.out";

			$this->executableFile = "a.out";
			$this->compileCommand = $this->compilerName." -lm ".$this->sourceCodeFileName;//g++ -lm main.cpp	
			$this->commandError=$this->compileCommand." 2>".$this->errorFileName;//g++ -lm main.cpp 2> error.txt

		}

		public function execute(){
			$this->prepareFile();
			$this->compileCode();

			$this->checkCompailerError();
			$this->runCode();
			$this->processResult();

			$this->setPermissionFile();
			$this->removeAllProcessFile();

			return $this->processResultData;
		}

		public function prepareFile(){
			$this->makeFile($this->sourceCodeFileName,$this->sourceCode);
			$this->makeFile($this->inputFileName,$this->input);
			$this->makeFile($this->expectedOutputFileName,$this->expectedOutput);
			$this->makeFile($this->errorFileName);
		}

		

		public function compileCode(){
			shell_exec($this->commandError);
		}

		public function checkCompailerError(){
			$error=file_get_contents($this->errorFileName);
			if(trim($error)!=""){
				if(strpos($error,"error"))$this->compailerError = 1;
				else $this->runTimeError = 1;
			}
		}

		public function runCode(){
			if($this->compailerError==1)return;
			$out=trim($this->input)==""?$this->out:$this->out." < ".$this->inputFileName;
			$this->executionStartTime = microtime(true);
			$this->output=shell_exec($out);
			$this->makeFile($this->outputFileName,$this->output);
			$this->executionEndTime = microtime(true);
			$this->executionTotalTime = $this->executionEndTime - $this->executionStartTime;
			$this->executionTotalTime = sprintf('%0.3f', $this->executionTotalTime);
		}

		public function processResult(){
			$data = array();
			$data['time'] = $this->executionTotalTime;
			$data['output'] = $this->output;
			if($this->compailerError == 1)$data['description'] = "Compilation Error";
			else if($this->executionTotalTime >= $this->timeLimit)$data['description']="Time Limit Exceeded";
			else if(trim($this->output) == "")$data['description']="Runtime Error";
			else {
				$ok = exec("python3 compare_output.py");
				$data['description'] = trim($ok)=="OK"?"Accepted":"Wrong answer";
			}
			$this->processResultData = $data;
		}


		public function makeFile($fileName,$fileVal=""){
			$file=fopen($fileName,"w+");
			fwrite($file,$fileVal);
			fclose($file);
		}

		public function setPermissionFile(){
			exec("chmod -R 777 ".$this->inputFileName);
			exec("chmod -R 777 ".$this->sourceCodeFileName);  
			exec("chmod 777 ".$this->errorFileName);
			exec("chmod -R 777 ".$this->executableFile);
		}

		public function removeAllProcessFile(){
			exec("rm ".$this->sourceCodeFileName);
			exec("rm *.o");
			exec("rm *.txt");
			exec("rm ".$this->executableFile);
		}

	}


?>