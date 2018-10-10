<?php
/*
* Plugin Name: Sixpetal Alertbox
* Author: Prashant Kumar
* Version: 1.0
* Plugin URI: http://sixpetal.com/spapopup
* Author URI: http://sixpetal.com/prashant
* Date: 30-09-2018, 4:53PM 
* Description: A WordPress plugin in PHP for creating alertbox based survay forms
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:  spapopup
*/

if (!defined('WPINC')) {
	//Direct access
	die();
}
if (!defined('SPA_VER')) {
	define('SPA_VER', '1.0');
}
if (!defined('SPA_DIR')) {
	define('SPA_DIR', plugin_dir_path(__FILE__));
}
if (!defined('SPA_URL')) {
	define('SPA_URL', plugin_dir_url(__FILE__));
}
if ( !function_exists( 'readPass' ) ) {
	function readPass(){
		// host::database_name::prifix::user::pass
		$myfile = fopen(SPA_DIR."/inc/database.config", "r") or die("Unable to open file!");
		$p = fread($myfile,filesize(SPA_DIR."/inc/database.config"));
		fclose($myfile);
		return $p;
	}
}
if ( !function_exists( 'fetch_all' ) ) {

	 // Fetch from table by id
	function fetch_all($DBH, $table)
	{
		$query="SELECT 
					*
			   FROM 
				  ".$table."
			   WHERE 1" ; 
					
		$result = $DBH->prepare($query);
		$result->execute();
		$data=$result->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		
	}
}
if ( !function_exists( 'fetch_by_id' ) ) {

	 // Fetch from table by id
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
}

if ( !function_exists( 'delete_by_id' ) ) {

	 // Fetch from table by id
	function delete_by_id($DBH, $table, $where, $val)
	{
		$query="DELETE
			   FROM 
				  ".$table."
			   WHERE ".$where." = '".$val."'" ; 
					
		$result = $DBH->prepare($query);
		$result->execute();
		
	}
}

if ( !function_exists( 'sixpetal_survey_alertbox_state_install' ) ) {
	// Install event function
	function sixpetal_survey_alertbox_state_install(){
		
		global $wpdb;
		global $SPA_VER;

		$spa_question_answer = $wpdb->prefix . 'spa_question_answer';
		$spa_location = $wpdb->prefix . 'spa_location';
		$spa_setting = $wpdb->prefix . 'spa_setting';
		$spa_setting = $wpdb->prefix . 'spa_user_data';
		
		$charset_collate = $wpdb->get_charset_collate();
		$sql1 = "CREATE TABLE IF NOT EXISTS $spa_question_answer ( tree VARCHAR(200) NOT NULL , page_location VARCHAR(200) NOT NULL, question TEXT NOT NULL , option_list TEXT NOT NULL , last INT NOT NULL , PRIMARY KEY (tree))";
		$sql2 = "CREATE TABLE IF NOT EXISTS $spa_location (location VARCHAR(100) NOT NULL , PRIMARY KEY (location))";
		$sql3 = "CREATE TABLE $spa_setting ( id TINYINT NOT NULL , title VARCHAR(200) NOT NULL , total_pages INT NOT NULL , PRIMARY KEY (id))";
		
		$sql4 = "CREATE TABLE $spa_user_data ( id INT NOT NULL AUTO_INCREMENT , time_now BIGINT NOT NULL , user_post TEXT NOT NULL , PRIMARY KEY (id))";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql1 );
		dbDelta( $sql2 );
		dbDelta( $sql3 );
		dbDelta( $sql4 );

		add_option( 'SPA_VER', $SPA_VER );
	}
}
if ( !function_exists( 'spa_options_page' ) ) {
	// Add new menu item when called
	function spa_options_page()
	{
		add_menu_page(
		'Six Petel Softwere',
		'SPA Settings',
		'manage_options',
		'spa_settings',
		'spa_settings_page_html',
		'dashicons-admin-tools',
		20
    	);
	}
}
if (!function_exists('spa_settings_page_html')) {
	//Admin Setting page
	function spa_settings_page_html()
	{
		if (!is_admin()) {
			return;
		}
		
		global $wpdb;
		$cred = explode('::', readPass());
		
		include SPA_DIR.'/inc/admin_page.php';
	}
}
if ( !function_exists( 'spa_user_ui_embade' ) ) {
	// front end side HTML and other
	function spa_user_ui_embade($content)
	{
		global $wpdb;
		$read_file = '';
		$cred = explode('::', readPass());

		$host = $cred[0];
		$dbname = $_GET['database'];
		$user = $cred[3];
		$pass = $cred[4];
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$loc = fetch_all($DBH, $wpdb->dbname.'.'.$wpdb->prefix.'spa_location');
		
		$read_file .= '<div class="spa_cotainer">
		<div class="banner" style="z-index:-1;"></div>
		<!-- <img onclick="popup_close()" src="https://mlenderz.com/wp-content/plugins/sixpetal_survey_alertbox/assert/image/close.png" style="width: 32px;position: absolute;top: 10px;right: 10px;border-radius: 50px;cursor: pointer;"> -->
	<center>
		<br/>
		<div class="spa_top_line">
			18230+ Loans in 
			<select style="background: rgba(0,0,0,0); ">';
		foreach($loc as $l){
			if (!empty($l['location'])) {
				$read_file .= '<option style="color: #e53935; ">'.$l['location'].'</option>';
			}
		}
		$read_file .= '</select>
		</div>
		<div class="spa_slogen">
			Tell us more about your requirements so that we can connect you to the right Loans
		</div>
	</center>
	<form>
		<div class="spa_answer_box">
		</div>
	</form>
</div>';
		
		$content = $read_file . $content ;
		return $content;
	}
}
if ( !function_exists( 'spa_call_js_and_css' ) ) {
	// include JS and CSS file
	function spa_call_js_and_css()
	{
		wp_enqueue_script( 'spa-js', SPA_URL.'assert/js/spa.js', array('jquery'), null, true);
		
		wp_enqueue_style('spa-css', SPA_URL.'assert/css/spa.css');
		wp_enqueue_style('fonts-css', 'https://fonts.googleapis.com/css?family=Roboto');
		wp_enqueue_style('cloudflare-css', 'https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css');
	}
}

// Hook for registering install event
register_activation_hook( __FILE__, 'sixpetal_survey_alertbox_state_install' );

// Hook for main menu setting option "SPA Settings"
add_action('admin_menu', 'spa_options_page');

// Hook for embading alertbox on user side
add_filter('the_content', 'spa_user_ui_embade');

// Hook for embade CSS and JS scripts
add_action('wp_enqueue_scripts', 'spa_call_js_and_css');

?>