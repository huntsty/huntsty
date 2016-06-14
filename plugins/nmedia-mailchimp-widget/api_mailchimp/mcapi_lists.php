<?php
/**
This Example shows how to pull the Members of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
//require_once 'inc/config.inc.php'; //contains apikey

$api = new nm_MCAPI(get_option('nm_mc_api_key'));

$retval = $api->lists();

if ($api->errorCode){
	_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
} 

?>
