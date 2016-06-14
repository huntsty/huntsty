<?php
/**
loading existing forms
**/

if(isset($_REQUEST['fid']))
{
	if(nmMailChimp::deleteForm($_REQUEST['fid']))
		echo '<div class="updated">Form deleted</div>';
}



$arrForms = nmMailChimp::getForms();
?>
<br />
<div class="postbox">
<h3>Existing forms</h3>
<div class="inside">
<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th width="6%">Sr #</th>
    <th width="30%">Form name</th>
    <th width="26%">Detail</th>
    <th width="25%">Shortcode</th>    
    <th width="13%">Delete</th>    
  </tr>
</thead>

<tbody>
<?php 
$c = 0;
foreach($arrForms as $form):
$fid = $form -> form_id;
$fname = $form -> form_name;
$urlDel = nmMailChimp::fixRequestURI(array('fid' => $fid));
//$meta = json_decode($form -> form_meta);
?>
	<tr>
		<td><?php echo ++$c?></td>
		<td><?php echo $fname?></td>
		<td><?php echo $form -> form_detail?></td>
		<td align="left"><code>[nm-mc-form fid="<?php echo $fid?>"]</code></td>
       
        <td align="left"><a href="javascript:confirmDel('<?php echo $urlDel?>')">
        	<img src="<?php echo plugins_url('images/delete_16.png', __FILE__)?>" border="0" />
        </a></td>
	</tr>
    
<?php endforeach;?>
</tbody>
</table>
</div>
</div>

<script type="text/javascript">
function confirmDel(url)
{
	var a = confirm("Do you want to remove this variable?");
	
	if(a)
	{
		window.location = url;
	}
}
</script>