<?php
	//included in "sixpetal_survey_alertbox.php"
//print_r($_SERVER);
	$host = $cred[0];
	$dbname = $_GET['database'];
	$user = $cred[3];
	$pass = $cred[4];
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	
	if(isset($_GET['product_edit'])){
		$product_edit_data = fetch_by_id($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_question_answer', 'tree', $_GET['product_edit'])[0];
	}
	if(isset($_GET['product_delete'])){
		delete_by_id($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_question_answer', 'tree', $_GET['product_delete']);
		echo "<a href='".$_SERVER['SCRIPT_URL']."?page=spa_settings' style='text-decoration: none;'><div style='background:#dc3232; color:#fff;'>Last Delete Success...</div></a>";
	}
	if(isset($_GET['user_data_delete'])){
		delete_by_id($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_user_data', 'id', $_GET['user_data_delete']);
		echo "<a href='".$_SERVER['SCRIPT_URL']."?page=spa_settings' style='text-decoration: none;'><div style='background:#dc3232; color:#fff;'>Last Delete Success...</div></a>";
	}

	$ques_table = fetch_all($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_question_answer');
	$loc_table = fetch_all($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_location');
	$user_data = fetch_all($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_user_data');


	// No need for isset($_GET['location_edit'] becouse one row in table
	if(isset($_GET['location_delete'])){
		delete_by_id($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_location', 'location', $_GET['location_delete']);
		echo "<a href='".$_SERVER['SCRIPT_URL']."?page=spa_settings' style='text-decoration: none;'><div style='background:#dc3232; color:#fff;'>Last Delete Success...</div></a>";
	}


?>
<style type="text/css">
	/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons that are used to open the tab content */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
.tabcontent {
    animation: fadeEffect 1s; /* Fading effect takes 1 second */
}

/* Go from zero to full opacity */
@keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
}
#myInput {
    background-image: url('/css/searchicon.png'); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 18px; /* Increase font-size */
}

#myTable th, #myTable td {
	vertical-align: top;
    text-align: left; /* Left-align text */
    padding: 12px; /* Add padding */
}

#myTable tr {
    /* Add a bottom border to all table rows */
    border-bottom: 1px solid #ddd; 
}

#myTable tr.header, #myTable tr:hover {
    /* Add a grey background color to the table header and on hover */
    background-color: #f1f1f1;
}
</style>

<div class="wrap">
	<div class="tab">
	  <!-- <button class="tablinks" onclick="openTab(event, 'Genral_Settings')">Genral Settings</button> -->
	  <button class="tablinks" onclick="openTab(event, 'Database_Settings')" id="db_settings">Database Settings</button>
	  <button class="tablinks" onclick="openTab(event, 'Question')" id="product_settings">Question Settings</button>
	  <button class="tablinks" onclick="openTab(event, 'Location')" id="loc_settings">Location</button>
	  <button class="tablinks" onclick="openTab(event, 'Submission')" id="user_submission">User Submission</button>
	</div>

	<!-- Tab content -->
	<div id="Genral_Settings" class="tabcontent">
	  <h3>Genral Settings</h3>
	  <p>
	  	<form action="<?php echo SPA_URL; ?>inc/admin_submit.php?frm=settings&database=<?php echo $wpdb->dbname; ?>&prefix=<?php echo $wpdb->prefix; ?>" method="POST">
			<table>
				<tr>
					<td width="200px">
					Top Line
					</td>
					<td>
						<input type="text" name="top_line" placeholder="This is demo text">
					</td>
				</tr>
				<tr>
					<td>
						Total Number of Questions
					</td>
					<td>
						<input type="number" name="number_of_ques" placeholder="">
					</td>
				</tr>
			</table>
			<input type="submit" name="submit_settings" class="button button-primary" value="Save">
		</form>
	  </p>
	</div>


	<div id="Submission" class="tabcontent">
	  <h3>User Submission</h3>
	  <p>
	  	
		<table id="myTable">
		  <tr class="header">
		    <th style="width:35%;">Server Time Stamp</th>
		    <th style="width:30%;">Submission</th>
		    <th style="width:15%;">Operation</th>
		  </tr>
		  <?php
		  	foreach ($user_data as $ind_user_data) {
				echo "<tr>";
				echo '<td>'.$ind_user_data["time_now"].'</td>';

				echo '<td><dl style="padding: 0px;margin: 0px;">';
				$option_list = json_decode($ind_user_data["user_post"]);
				foreach ($option_list as $value) {
					echo '<dt><b>Q. '.$value[1].'</b></dt><dd> Ans. '.$value[2].'</dd>';
				}
				echo '</dl></td>';
				echo '<td>'.$tree_view.'</td>';

				echo '<td><a href="'.$_SERVER['REQUEST_URL'].'?page=spa_settings&user_data_delete='.$ind_user_data["id"].'"><span class="dashicons dashicons-editor-removeformatting" title="Delete"></span></td>';
				echo "</tr>";
			}
		  ?>
		</table>
	  </p>
	</div>


	<div id="Database_Settings" class="tabcontent">
	  <h3>Database Settings</h3>
	  <p>
		<h5>Note: Before doing anything with the plugin please set database credential in order to avoid crash.</h5>
		<form action="<?php echo SPA_URL; ?>inc/admin_submit.php?frm=password" method="POST">
			<table>
				<tr>
					<td width="200px">
					Database Host
					</td>
					<td>
						<input type="text" name="host" value="<?php echo $cred[0];?>" autocomplete="new-password">
					</td>
				</tr>
				<tr>
					<td width="200px">
					Database User
					</td>
					<td>
						<input type="text" name="user" value="<?php echo $cred[3];?>" autocomplete="new-password">
					</td>
				</tr>
				<tr>
					<td>
						Database Password
					</td>
					<td>
						<input type="password" name="pass" value="<?php echo $cred[4];?>" autocomplete="new-password">
					</td>
				</tr>
				<tr>
					<td>
						Database Name
					</td>
					<td>
						<input type="text" name="database_name"  value="<?php echo $wpdb->dbname; ?>" readonly>
					</td>
				</tr>
				<tr>
					<td>
						Database Prifix
					</td>
					<td>
						<input type="text" name="prifix"  value="<?php echo $wpdb->prefix; ?>" readonly>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit_db" class="button button-primary" value="Save">
		</form>
	  </p> 
	</div>

	<div id="Question" class="tabcontent">
	  <p>
		<h3>Add New Question</h3>
		<h5>Note:<br/> 1. Write Tree 0(zero) for home screen product.<br/>2. Write Comma(,) at the end of tree.</h5>
		<form action="<?php echo SPA_URL; ?>inc/admin_submit.php?frm=add_question&database=<?php echo $wpdb->dbname; ?>&prefix=<?php echo $wpdb->prefix; ?>" method="POST">
			<table>
				<tr>
					<td width="200px">
					Question Tree
					</td>
					<td>
						<input type="text" name="tree" value="<?php if(isset($product_edit_data)) echo $product_edit_data['tree'] ?>" placeholder="ex 1-2-3-4-5-">
					</td>
				</tr>
				<tr>
					<td width="200px">
					Page Location (Optinal)
					</td>
					<td>
						<input type="text" name="page_location" value="<?php if(isset($product_edit_data)) echo $product_edit_data['page_location'] ?>" placeholder="Ex : /loan/home">
					</td>
				</tr>
				<tr>
					<td>
						Question
					</td>
					<td>
						<input type="text" name="question" value="<?php if(isset($product_edit_data)) echo $product_edit_data['question'] ?>" placeholder="">
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">
						Available Options
					</td>
					<td>
						<a target="self" href="https://github.com/Krprashant94/WordPress-Popup/blob/master/doc/reglur_exp.md">How to write Data Validation Rule ? </a>
						<div class="answer_option">
							<div class="option_0">
								<select name="ans_datatype_0">
									<option>Radio</option>
									<option>Text</option>
									<option>Number</option>
									<option>Date</option>
									<option>Email</option>
									<!-- <option>Dropdown</option> -->
								</select>
								<input type="text" name="answers_0" placeholder="Option">
								<input type="text" name="validrule_0" placeholder="Data Validation Rule" value="[0-9a-zA-Z]">
							</div>
						</div>
						<button onClick="addNewAnswer(event);">+</button>
					</td>
				</tr>
				<tr>
					<td width="200px">
					Last Page
					</td>
					<td>
						<select name="last_page">
							<option>false</option>
							<option>true</option>
						</select>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit_question" class="button button-primary" value="Save"><br/>
			<?php if(isset($_GET['product_edit'])) echo '<a href="?page=spa_settings">Cancle</a>'; ?>
		</form>
	  </p>


		<input type="text" id="myInput" onkeyup="tableFilter()" placeholder="Search for question..">

		<table id="myTable">
		  <tr class="header">
		    <th style="width:35%;">Question</th>
		    <th style="width:30%;">Option</th>
		    <th style="width:15%;">Tree</th>
		    <th style="width:10%;">Location in Page</th>
		    <th style="width:10%;">Operation</th>
		  </tr>
		  <?php
		  	foreach ($ques_table as $question_data) {				
				$tree_view = substr($question_data["tree"], 0, -1);
				$tree_view = str_replace(',', '->', $tree_view);
				echo "<tr>";
				echo '<td>'.$question_data["question"].'</td>';
				echo '<td><ol style="padding: 0px;margin: 0px;">';
				$option_list = json_decode($question_data["option_list"]);
				foreach ($option_list as $value) {
					echo '<li>['.$value->datatype.'] '.$value->value.'</li>';
				}
				echo '</ol></td>';
				echo '<td>'.$tree_view.'</td>';
				echo '<td>'.$question_data["page_location"].'</td>';
				echo '<td><a href="'.$_SERVER['REQUEST_URL'].'?page=spa_settings&product_edit='.$question_data["tree"].'"><span class="dashicons dashicons-admin-customizer" title="Edit"></span></a> <a href="'.$_SERVER['REQUEST_URL'].'?page=spa_settings&product_delete='.$question_data["tree"].'"><span class="dashicons dashicons-editor-removeformatting" title="Delete"></span></td>';
				echo "</tr>";
			}
		  ?>
		</table>


	</div>

	<div id="Location" class="tabcontent">
	  <h3>Location</h3>
	  <p>
	  	<h3>Add New Location</h3>
		<form action="<?php echo SPA_URL; ?>inc/admin_submit.php?frm=add_location&database=<?php echo $wpdb->dbname; ?>&prefix=<?php echo $wpdb->prefix; ?>" method="POST">
			<table>
				<tr>
					<td width="200px">
					Location Name
					</td>
					<td>
						<input type="text" name="location" value="<?php if(isset($_GET['location_edit'])) echo $_GET['location_edit']; ?>" placeholder="">
					</td>
				</tr>
			</table>
			<input type="submit" name="submit_loc" class="button button-primary" value="Save">
			<?php if(isset($_GET['location_edit'])) echo '<a href="?page=spa_settings">Cancle</a>'; ?>
		</form>
	  </p>


	  <table id="myTable">
		  <tr class="header">
		    <th style="width:90%;">Location</th>
		    <th style="width:10%;">Operation</th>
		  </tr>
		  <?php
		  	foreach ($loc_table as $loc_data) {
				echo '<td>'.$loc_data["location"].'</td>';
				echo '<td><a href="'.$_SERVER['REQUEST_URL'].'?page=spa_settings&location_edit='.$loc_data["location"].'"><span class="dashicons dashicons-admin-customizer" title="Edit"></span></a> <a href="'.$_SERVER['REQUEST_URL'].'?page=spa_settings&location_delete='.$loc_data["location"].'"><span class="dashicons dashicons-editor-removeformatting" title="Delete"></span></td>';
				echo "</tr>";
			}
		  ?>
		</table>



	</div>
</div>

<script type="text/javascript">
	function openTab(evt, tab_name) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tab_name).style.display = "block";
    evt.currentTarget.className += " active";
}
function tableFilter() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
<?php
	if(isset($_GET['product_edit']) || isset($_GET['product_delete'])){
		echo 'document.getElementById("product_settings").click();';
	}elseif(isset($_GET['location_edit']) || isset($_GET['location_delete'])){
		echo 'document.getElementById("loc_settings").click();';
	}elseif(isset($_GET['user_data_delete'])){
		echo 'document.getElementById("user_submission").click();';
	}else{
		echo 'document.getElementById("db_settings").click();';
	}

?>
// alert(getCookie('spa_current_tab'));
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
var answer_option_count = 0;
function addNewAnswer(e){
	e.preventDefault();
	console.log(e);
	answer_option_count ++;
	text = '<div class="option_'+answer_option_count+'">\
								<select name="ans_datatype_'+answer_option_count+'"> \
									<option>Radio</option> \
									<option>Text</option> \
									<option>Number</option> \
									<option>Date</option> \
								</select> \
								<input type="text" name="answers_'+answer_option_count+'" placeholder="Option"> \
								<input type="text" name="validrule_'+answer_option_count+'" placeholder="Data Validation Rule" value="[0-9a-zA-Z]">\
							</div>';
	jQuery('.answer_option').append(text);
}
</script>
<!-- Tab links -->