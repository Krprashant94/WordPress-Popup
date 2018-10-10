<?php

	setcookie("spacu_already_popup", "true", time() + (86400 * 30), "/"); 

	$TO = 'sales@mlenderz.com'; 
	$SUBJECT = 'Mlenderz Lead';

	$HEADERS = 'From: Mlenderz Support Team < support@mlenderz.com >' . PHP_EOL .
	'Reply-To:  Mlenderz Support Team < support@mlenderz.com >' . PHP_EOL .
	'MIME-Version: 1.0'. PHP_EOL.
	'Content-type: text/html; charset=iso-8859-1' . PHP_EOL.
	'X-Mailer: PHP/' . phpversion()."Return-Path:  Mlenderz Support Team < support@mlenderz.com >".PHP_EOL;



	function basic_insert($DBH, $table, $data){
		$array_size=sizeof($data);

		$sql="INSERT INTO `".$table."` SET ";

		$i=0;
		foreach($data as $key => $value) {
			++$i;	

			if($i==$array_size)
			$sql.="`".$key."`='".$value."' ; ";
			else
			$sql.="`".$key."`='".$value."' , ";
		}
		
		//echo $sql;
		$query = $DBH->prepare($sql);
		$query->execute();
		//print_r($query->errorInfo());
		//		return $query;
		return $DBH->lastInsertId();
	}
	function fetch_by_id($DBH, $table, $where, $val)
	{
		$query="SELECT 
					*
			   FROM 
				  ".$table."
			   WHERE ".$where." = '".$val."'" ; 
					
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
	$cred = explode('::', readPass());

	$host = $cred[0];
	$dbname = $cred[1];
	$user = $cred[3];
	$pass = $cred[4];
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

	$data_array = array();

	$re = '/^spa_/';
	$mail_text = '';
	$mail_text .= "<table><tr><td>Page Tree</td><td>Question</td><td>Answer</td></tr>";
	foreach ($_COOKIE as $key => $value) {
		if (preg_match($re, $key)) {
			$question_info = explode('_', $key);
			$mail_text .=  '<tr><td>'.$question_info[1].'</td>';
			$q = fetch_by_id($DBH, $cred[2].'spa_question_answer', 'tree', $question_info[1])[0];
			
			$mail_text .=  "<div>";
			$option_list = json_decode($q['option_list']);
			if (isset($question_info[2])) {
				$tmp_array = array($question_info[1], $option_list[$question_info[2]-1]->value, $value);
				$mail_text .=  '<td>'.$option_list[$question_info[2]-1]->value.'</td>';
				$mail_text .=  '<td>'.$value.'</td></tr>';
			}else{
				$tmp_array = array($question_info[1], $q['question'], $option_list[0]->value);
				$mail_text .=  '<td>'.$q['question'].'</td>';
				$mail_text .=  '<td>'.$option_list[0]->value.'</td></tr>';
			}
			array_push($data_array, $tmp_array);
		}
	}
	$mail_text .=  "</table>";
	echo $mail_text;
	
	echo $databse_text =  json_encode($data_array);
	
	$data = array(
				'time_now' => time(),
				'user_post' => $databse_text
			);
	basic_insert($DBH,$cred[2].'spa_user_data', $data);



	mail($TO, $SUBJECT, $mail_text, $HEADERS);

	if(isset($_SERVER['HTTP_REFERER'])){
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}else{
		header("Location: https://mlenderz.com");
	}
?>