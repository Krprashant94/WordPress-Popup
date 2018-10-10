<?php
	function basic_fetch_by_id($DBH, $table,$where,$id)
	{
		$query="SELECT 
				*
		   FROM 
			  `".$table."`
		   WHERE 
			 (".$where."='".$id."')" ; 
					
		$result = $DBH->prepare($query);
		$result->execute();
		$data=$result->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		
	}


	function readPass(){
		$myfile = fopen("database.config", "r") or die("Unable to open file!");
		$p = fread($myfile,filesize("database.config"));
		fclose($myfile);
		return $p;
	}

	if(isset($_GET['head'])){
		$cred = explode('::', readPass());
//		print_r($cred);

		$host = $cred[0];
		$dbname = $cred[1];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

		
		echo json_encode(basic_fetch_by_id($DBH, $cred[2].'spa_question_answer', 'page_location', $_GET['head']));
	}
	if(isset($_GET['tree'])){
		$cred = explode('::', readPass());
//		print_r($cred);

		$host = $cred[0];
		$dbname = $cred[1];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$data = basic_fetch_by_id($DBH, $cred[2].'spa_question_answer', 'tree', $_GET['tree']);
		// $data['option_list'] = json_decode($data['option_list']);
		echo json_encode($data);
	}
?>