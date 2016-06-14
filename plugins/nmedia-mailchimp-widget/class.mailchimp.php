<?php
/*
** This class is connector b/w Nmedia mailchimp plugin and MCAPI
Author: ceo@najeebmedia.com (Najeeb Ahmad)
*/

$parent = dirname(__FILE__) . '/api_mailchimp/MCAPI.class.php';
include ($parent);

class clsMailchimp extends MCAPI
{
	var $mc;
	
	function __construct()
	{
		$this -> mc = new MCAPI(get_option('nm_mc_api_key'));
		
	}
	
	
	/*
	** Getting Mailchimp account list
	*/
	function getAccountLists()
	{
		$retval = $this -> mc -> lists();
		
		if ($this -> mc -> errorCode){
		  	_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
		 }
		 else
		 {
			 return $retval['data'];
		 }
		 
	}
	
	
	/*
	** Getting List vars
	*/
	function getMergeVars($list_id)
	{
		$retval = $this -> mc -> listMergeVars($list_id);
		
		if ($this -> mc -> errorCode){
		  	echo $this -> mc -> errorMessage;
		 }
		 else
		 {
			 return $retval;
		 }
		 
	}
	
	
	/*
	** Getting List ineterst gruops
	*/
	function getListGroups($list_id)
	{
		$retval = $this -> mc -> listInterestGroupings($list_id);
			return $retval;		
	}
	
	
	
	/*
	** Adding new Merge Var to a list
	*/
	function addMergeVar($list_id, $tag, $detail)
	{
		$retval = $this -> mc -> listMergeVarAdd($list_id, $tag, $detail);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	/*
	** Deleting Merge Var from a list
	*/
	function XMergeVar($list_id, $tag)
	{
		$retval = $this -> mc -> listMergeVarDel($list_id, $tag);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	/*
	** Adding new Group to a list
	*/
	function addInterestGroup($list_id, $name, $group_id)
	{
		$retval = $this -> mc -> listInterestGroupAdd($list_id, $name, $group_id);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	/*
	** Adding new Group to a Interest
	*/
	function addGroup($list_id, $name, $groups)
	{
		$groups = explode(",", $groups);
		
		$retval = $this -> mc -> listInterestGroupingAdd($list_id, $name, "hidden", $groups);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	
	
	/*
	** Deleting Interest Group from a list
	*/
	function XInterestGroup($group_id)
	{
		$retval = $this -> mc -> listInterestGroupingDel($group_id);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	/*
	** Deleting Group (sub group) from a list
	*/
	function XGroup($list_id, $group_name, $grouping_id)
	{
		$retval = $this -> mc -> listInterestGroupDel($list_id, $group_name, $grouping_id);
		
		if ($this -> mc -> errorCode){
		  	//echo $this -> mc -> errorMessage;
			return false;
		 }
		 else
		 {
			 return $retval;
		 }
	}
	
	
	/*
	** this function rendering list stats
	*/
	
	function renderListStats($arrStats, $lid)
	{	
		$html = '<ul style="display:none" id="list-detail-'.$lid.'">';
		foreach($arrStats as $key => $val)
		{
			$html .= '<li style="float:left; text-align:center; margin:5px; border:#ccc 1px dashed;padding:5px;width:30%"><strong>'.$key.'</strong>: '.$val.'</li>';
		}
		$html .='</ul>';
		
		$html .='<div style="clear:both"></div>';
		
		echo $html;
	}
}
?>