<?php
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
	function int_delete($DBH, $table, $where, $condit){

		$sql="DELETE FROM `".$table."` WHERE ".$where." = ".$condit;

		$i=0;

		//echo $sql;
		$query = $DBH->prepare($sql);
		$query->execute();
		//print_r($query->errorInfo());
		//		return $query;
		return $DBH->lastInsertId();
	 }
	function string_delete($DBH, $table, $where, $condit){

		$sql="DELETE FROM `".$table."` WHERE ".$where." = '".$condit."'";

		$i=0;

		//echo $sql;
		$query = $DBH->prepare($sql);
		$query->execute();
		//print_r($query->errorInfo());
		//		return $query;
		return $DBH->lastInsertId();
	 }


	function readPass(){
		$myfile = fopen("database.config", "r") or die("Unable to open file!");
		$p = fread($myfile,filesize("database.config"));
		fclose($myfile);
		return $p;
	}

	if($_GET['frm'] == 'password'){
		$myfile = fopen("database.config", "w+") or die("Unable to open file!");
		if(empty($_POST['pass']))
			$txt = $_POST['host'].'::'.$_POST['database_name'].'::'.$_POST['prifix'].'::'.$_POST['user'].'::';
		else
			$txt = $_POST['host'].'::'.$_POST['database_name'].'::'.$_POST['prifix'].'::'.$_POST['user'].'::'.$_POST['pass'];
		fwrite($myfile, $txt);
		fclose($myfile);
		header('Location: '.explode('?', $_SERVER['HTTP_REFERER'])[0].'?page=spa_settings');
	}
	if($_GET['frm'] == 'read_password'){
		echo readPass();
	}
	

	if($_GET['frm'] == 'settings'){
		$cred = explode('::', readPass());
//		print_r($cred);

		$host = $cred[0];
		$dbname = $_GET['database'];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$data = array(
			'id'=>'0',
			'title'=>$_POST['top_line'],
			'total_pages'=>$_POST['number_of_ques']
		);
		int_delete($DBH, $_GET['prefix'].'spa_setting', 'id', '0');
		basic_insert($DBH, $_GET['prefix'].'spa_setting', $data);
		header('Location: '.explode('?', $_SERVER['HTTP_REFERER'])[0].'?page=spa_settings');
	}
	if($_GET['frm'] == 'add_question'){
		if(empty($_POST['tree'])){
			header('Location: '.explode('?', $_SERVER['HTTP_REFERER'])[0].'?page=spa_settings');
		}
//		print_r($_POST);
		$i = 0;
		$option_list = array();
		while(true){
			if(isset($_POST['ans_datatype_'.$i])){
				if (!empty($_POST['answers_'.$i])) {
					array_push($option_list, array('datatype' => $_POST['ans_datatype_'.$i], 'value' => $_POST['answers_'.$i], 'validrule' => $_POST['validrule_'.$i]));
				}
			}else{break;}
			$i++;
		}
		$option_list = json_encode($option_list);
		
		$cred = explode('::', readPass());
//		print_r($cred);

		$host = $cred[0];
		$dbname = $_GET['database'];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$data = array(
			'tree'=>$_POST['tree'],
			'page_location' => $_POST['page_location'],
			'question' => $_POST['question'],
			'option_list' => $option_list,
			'last' => $_POST['last_page']=='true'?1:0
		);
		string_delete($DBH, $_GET['prefix'].'spa_question_answer', 'tree', $_POST['tree']);
		basic_insert($DBH, $_GET['prefix'].'spa_question_answer', $data);
		header('Location: '.explode('?', $_SERVER['HTTP_REFERER'])[0].'?page=spa_settings');
		
	}
	if($_GET['frm'] == 'add_location'){
		$cred = explode('::', readPass());
//		print_r($cred);

		$host = $cred[0];
		$dbname = $_GET['database'];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$data = array(
			'location'=>$_POST['location']
		);
		string_delete($DBH, $_GET['prefix'].'spa_location', 'location', $_POST['location']);
		basic_insert($DBH, $_GET['prefix'].'spa_location', $data);
		header('Location: '.explode('?', $_SERVER['HTTP_REFERER'])[0].'?page=spa_settings');
	}
?>