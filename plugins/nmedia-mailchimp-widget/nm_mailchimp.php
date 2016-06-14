<?php
/*
 Plugin Name: N-Media MailChimp List subscriber form
Plugin URI: http://www.najeebmedia.com/wordpress-mailchimp-plugin-2-0-released/
Description: This is simple MailChimp List Manager and Subscriber form/widget plugin
Version: 3.2.4
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/


/*ini_set('display_errors',1);
 error_reporting(E_ALL);*/
error_reporting(E_ERROR | E_COMPILE_ERROR);

class nmMailChimp extends WP_Widget {

	var $nmmc_db_version = "1.0";
	/*
	 ** plugin table name
	*/
	static $tblName = 'nm_mc_forms';

	/*
	 ** constructor
	*/
	function nmMailChimp() {
		parent::WP_Widget(  'nmedia_mail_chimp',
				'N-Media MailChimp',
				array('description' => 'MailChimp Widget by najeebmedia.com.'));

	}

	/*
	 ** Installing database table for this plugin: nm_convo
	*/
	public function nmmc_install() {
		global $wpdb;
		global $nmmc_db_version;

		$table_name = $wpdb->prefix . nmMailChimp::$tblName;

		$sql = "CREATE TABLE `$table_name` (
		`form_id` INT( 7 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`form_name` VARCHAR( 150 ) NOT NULL,
		`form_detail` VARCHAR( 250 ) NOT NULL,
		`form_meta` MEDIUMTEXT NOT NULL ,
		`form_created` DATETIME NOT NULL
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option("nmmc_db_version", $nmmc_db_version);
	}

	/*
	 ** loading js/jquery stuff
	*/
	public function load_js()
	{
		wp_enqueue_script("jquery");


		wp_enqueue_script( 'mailchimp_ajax', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
		wp_localize_script( 'mailchimp_ajax', 'mailchimp_vars', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'mailchimp_plugin_url' => plugin_dir_url( __FILE__ ),
				'redirect_to'			=> get_option('nm_mc_redirect_to'),
		) );

	}

	/*
	 ** setting option page in wp admin
	*/
	public function set_up_admin_page () {

		add_submenu_page(   'options-general.php',
				'MailChimp Widget Options',
				'MailChimp Widget',
				'activate_plugins',
				__FILE__,
				array('nmMailChimp', 'admin_page'));
	}


	public function admin_page()
	{
		$file = dirname(__FILE__).'/options.php';
		include($file);
	}


	/*
	 ** display widget
	*/
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['nm_mc_title']) ? '' : apply_filters('widget_title', $instance['nm_mc_title']);
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		};

		/* print_r($args); */
		nmMailChimp::nm_load_form(	$instance['nm_mc_form_id'],
				$instance['nm_mc_box_title'],
				$instance['nm_mc_detail'],
				$instance['nm_mc_button_text'],
				$args['widget_id']);

		echo $after_widget;
	}


	/*
	 ** update/save function
	*/
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['nm_mc_title'] = strip_tags($new_instance['nm_mc_title']);
		$instance['nm_mc_detail'] = $new_instance['nm_mc_detail'];
		$instance['nm_mc_form_id'] = strip_tags($new_instance['nm_mc_form_id']);
		$instance['nm_mc_button_text'] = strip_tags($new_instance['nm_mc_button_text']);
		return $instance;
	}

	/*
	 ** admin control form
	*/
	function form($instance) {
		$default = 	array( 'nm_mc_title' 		=> __('MailChimp Widget'),
				'nm_mc_form_id' 		=> 0,
				'nm_mc_button_text'	=> 'Subscribe'
		);
			
		$instance = wp_parse_args( (array) $instance, $default );

		$field_id_title = $this->get_field_id('nm_mc_title');
		$field_name_title = $this->get_field_name('nm_mc_title');
		
		$field_id_detail = $this->get_field_id('nm_mc_detail');
		$field_name_detail = $this->get_field_name('nm_mc_detail');

		$field_id_form = $this->get_field_id('nm_mc_form_id');
		$field_name_form = $this->get_field_name('nm_mc_form_id');

		$field_id_button = $this->get_field_id('nm_mc_button_text');
		$field_name_button = $this->get_field_name('nm_mc_button_text');


		/*$api_dir = dirname(__FILE__).'/api_mailchimp/mcapi_lists.php';
		 include($api_dir);*/
		$arrList = nmMailChimp::getAccountLists();


		$file = dirname(__FILE__).'/mc-widget-options.php';
		include($file);

	}


	/*
	 ** Getting Mailchimp account list
	*/
	function getAccountLists()
	{
		$api_dir = dirname(__FILE__).'/api_mailchimp/inc/MCAPI.class.php';

		require_once ($api_dir);

		$api = new nm_MCAPI(get_option('nm_mc_api_key'));

		$retval = $api->lists();

		if ($api->errorCode){
			return false;
		}
		else
		{
			return $retval['data'];
		}
			
	}


	/*
	 ** Getting Merge vars attached to a list
	*/
	function getMergeVars($list_id)
	{
		$api_dir = dirname(__FILE__).'/api_mailchimp/inc/MCAPI.class.php';

		require_once ($api_dir);

		$api = new nm_MCAPI(get_option('nm_mc_api_key'));

		$retval = $api->listMergeVars($list_id);

		if ($api->errorCode){
			_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
		}
		else
		{
			return $retval;
		}
			
	}


	/*
	 ** this function rendering shortcodes in admin
	*/

	function renderShortcodes()
	{
		$file = dirname(__FILE__).'/gen-shortcode.php';
		include($file);
	}


	/*
	 ** this function rendering List Manager page
	*/

	function renderListManager()
	{
		$file = dirname(__FILE__).'/list-manager.php';
		include($file);
	}

	/*
	 ** this is rendering form in widget area
	*/
	function nm_load_form($fid, $boxTitle, $detail, $buttonText, $widget_id)
	{
		$file = dirname(__FILE__).'/nm-mc-form-widget.php';
		include($file);
	}

	/*
	 ** this is rendering form in page/post using shortcode
	*/
	function renderForm($atts)
	{
		extract(shortcode_atts(array(
		  'fid'				=> '',
		), $atts));
		 

		$button_text = get_option('nm_mc_button_title');
		$widget_id = time();
		$fid = "{$fid}";

		ob_start();
		$file = dirname(__FILE__).'/nm-mc-form.php';
		include($file);
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

	}


	/*
	 ** Unistalling the plugin
	*/

	function nm_mc_unistall()
	{
		global $nm_mc_options;

		foreach ($nm_mc_options as $value) {
			delete_option( $value['id'] );
		}
	}

	/*
	 ** getting the form
	*/

	function getForms()
	{
		global $wpdb;
		 
		$res = $wpdb->get_results("SELECT * FROM ".
				$wpdb->prefix . nmMailChimp::$tblName."
				ORDER BY form_created DESC");

		return $res;
	}

	/*
	 ** getting single entry for rendering
	*/

	function getForm($fid)
	{
		global $wpdb;
		 
		$res = $wpdb->get_row("SELECT * FROM ".
				$wpdb->prefix . nmMailChimp::$tblName."
				WHERE form_id = $fid");

		return $res;
	}


	/*
	 ** delete form entry
	*/

	function deleteForm($fid)
	{
		global $wpdb;
		 
		$res = $wpdb->query("DELETE FROM ".$wpdb->prefix . nmMailChimp::$tblName."
				WHERE form_id = $fid"
		);
		if($res){
			return true;
		}
	}
	/*
	 ** saving the form
	*/

	function saveForm($formName, $formDetail, $lid, $groups, $vars)
	{
		global $wpdb;
		 
		// making group array in desired format
		$interest = array();
		$temp = array();
		foreach($groups as $g)
		{

			if(!is_array($g))
			{
				$breakG = explode(',', $g);
				$temp['id'] = $breakG[0];
				$temp['name'] = $breakG[1];
			}else
			{
				$temp['groups'] = implode(',',$g);
				$interest[] = $temp;
				unset($temp);
			}
		}
		 
		$refine_vars = array();
		foreach($vars as $key => $val)
		{
			if($val['tag'] != '')
				$refine_vars[] = $val;
		}
		 
		/*echo '<pre>';
		 print_r($refine_vars);
		echo '</pre>';
		exit;*/

		$form_meta = array('list_id'		=> $lid,
				'interest'	=> $interest,
				'vars'		=> $refine_vars
		);
			
		$dt = array('form_name'		=> $formName,
				'form_detail'	=> $formDetail,
				'form_meta'		=> json_encode($form_meta),
				'form_created'	=> current_time('mysql')
		);
			
			
		$wpdb -> insert($wpdb->prefix . nmMailChimp::$tblName,
				$dt
		);

		/*$wpdb->show_errors();
		 $wpdb->print_error();*/
		if($wpdb->insert_id)
		{
			return true;
		}


	}
	/*
	 ** listing all countries with ISO standard required by Mailchimp
	*/

	function listCountries($w_id)
	{
		global $country_list;
		$cmb_name = "country-$w_id";
		echo '<select id="'.$cmb_name.'" class="nm_mc_text">';
		foreach($country_list as $country => $code)
		{
			echo '<option value="'.$code.'">'.$country.'</option>';
		}
		echo '</select>';
	}



	/*
	 ** to fix url re-occuring, written by Naseer sb
	*/

	function fixRequestURI($vars){
		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		$parts = explode("?", $uri);

		$qsArr = array();
		if(isset($parts[1])){	////// query string present explode it
			$qsStr = explode("&", $parts[1]);
			foreach($qsStr as $qv){
				$p = explode("=",$qv);
				$qsArr[$p[0]] = $p[1];
			}
		}

		//////// updatig query string
		foreach($vars as $key=>$val){
			if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;
		}

		////// rejoin query string
		$qsStr="";
		foreach($qsArr as $key=>$val){
			$qsStr.=$key."=".$val."&";
		}
		if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);
		$uri = $parts[0];
		if($qsStr!="") $uri.="?".$qsStr;
		return $uri;
	}

	/*
	 ** Enqueue style-file, if it exists.
	*/
	function add_nm_stylesheet() {
		$myStyleFile = dirname(__FILE__).'/nm_mc_style.css';
		if ( file_exists($myStyleFile) ) {
			wp_register_style('nm_mailchimp_stylesheet', plugins_url('nm_mc_style.css', __FILE__));
			wp_enqueue_style( 'nm_mailchimp_stylesheet');
	 }
	}

	// plugin localization
	function nm_mc_textdomain() {
		load_plugin_textdomain('nm_mailchimp_plugin', false, dirname(plugin_basename( __FILE__ )) . '/locale/');
	}


}


/*
 * ajax action callback to upload file
* defined in js/ajax.js
*/
add_action( 'wp_ajax_nopriv_nm_mailchimp_subscribe', 'nm_mailchimp_subscribe');
add_action( 'wp_ajax_nm_mailchimp_subscribe', 'nm_mailchimp_subscribe');
function nm_mailchimp_subscribe(){
	
	//print_r(json_encode($_REQUEST)); exit;

	$posted_data = $_REQUEST;
	
	unset($posted_data['nm_mc_list_id']);
	unset($posted_data['nm_mc_form_id']);
	unset($posted_data['action']);
	unset($posted_data['group']);

	//print_r($posted_data); exit;
	
	$postToMailChimp = dirname(__FILE__).'/api_mailchimp/postToMailChimp.php';

	if(file_exists($postToMailChimp)){
		include ($postToMailChimp);
	}else
	{
		echo 'file not found '.$postToMailChimp;
	}
	
	

	die(0);
}

/* hook activating plugin */
register_activation_hook(__FILE__, array('nmMailChimp','nmmc_install'));


// activate textdomain for translations
add_action('init', array('nmMailChimp', 'nm_mc_textdomain'));


/* book: register widget when loading the WP core */
add_action('widgets_init', 'nm_mc_register_widgets');

/* hook: loading js stuff */
add_action('wp_enqueue_scripts', array('nmMailChimp', 'load_js'));

/* hook: styilng */
add_action('wp_print_styles', array('nmMailChimp', 'add_nm_stylesheet'));


/* hook deactivating plugin */
register_deactivation_hook(__FILE__, array('nmMailChimp', 'nm_mc_unistall'));

/*shortcode introduced in version 3.0*/
add_shortcode( 'nm-mc-form', array('nmMailChimp', 'renderForm'));

$options_file = dirname(__FILE__).'/plugin-options.php';
include ($options_file);

function nm_mc_register_widgets(){
	// curl need to be installed
	register_widget('nmMailChimp');
}

?>