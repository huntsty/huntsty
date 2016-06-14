<?php 
/*
** this file is generting shortcode
*/

$mailchimp = dirname(__FILE__) . '/class.mailchimp.php';
include ( $mailchimp );

$mc = new clsMailchimp();
$arrList 	= $mc -> getAccountLists();

if(!is_array($arrList))
{
	echo '<div class="error">'.__("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area").'</div>';
}


/*saving form */
if(isset($_POST['btn-form']))
{
	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';*/
	
	if(nmMailChimp::saveForm($_POST['form-name'], 
							$_POST['form-detail'],
							$_POST['lid'],
							$_POST['chk-groups'], 
							$_POST['chk-vars'])
							);
							
							
	{
		echo '<div class="updated">Form saved</div>';
	}
	
}
?>

<h2><?php _e('Form shortcode Wizard', 'nm_mailchimp_plugin')?></h2>
<p><?php _e('This wizard will create shortcode based on your selected List, Groups and Vars, unlimited forms shortcodes can be genegrated. Once form shortcode is created you can simply put it in your Post/Page and even can put into widgets.', 'nm_mailchimp_plugin')?>
</p>

<div id="shortcode-container">

<div id="shortcode-left">
<h3>Select List(s)</h3>
<ul>
<?php
foreach($arrList as $list):
$urlLoadDetail = get_admin_url('', 'admin.php?page=mailchimp-shortcodes').'&lid='.$list['id'].'&lname='.$list['name'];	

$current = (@$_REQUEST['lid'] == $list['id']) ? 'current-list' : '';
?>
	<li class="good-links <?php echo $current?>">
    <a href="<?php echo $urlLoadDetail?>">
	<?php echo $list['name']?>
    </a></li>
<?php 
endforeach;
?>
</ul>
</div>

<!-- loading list Groups and Vars -->

<?php
if(isset($_GET['lid'])):

$arrVars	= $mc -> getMergeVars($_GET['lid']);
$arrGroups	= $mc -> getListGroups($_GET['lid']);

/*echo '<pre>';
print_r($arrGroups);
echo '</pre>';*/
?>

<div id="shortcode-right">
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])?>" method="post">


<div class="detail" id="l-d-aaa">
<input type="hidden" name="lid" value="<?php echo $_REQUEST['lid']?>" />
<h3>Select Group(s)</h3>
<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th width="13%"></th>
    <th width="39%">Interests</th>
    <th width="22%">Groups</th>
  </tr>
</thead>

<tbody>
<?php
$c=0;
if($arrGroups):
foreach($arrGroups as $group):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&gid='.$group['id'].'&group='.$group['name'].'&act=del-group&lid='.$_GET['lid'];

$urlGroups = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&gid='.$group['id'];
?>

  <tr>
    <td><input type="checkbox" name="chk-groups[gid-<?php echo $group['id']?>]" value="<?php echo $group['id'].','.$group['name']?>" id="chk-group-<?php echo $group['id']?>" onclick="checkSub('<?php echo $group['id']?>', this.checked)" /></td>
    <td><a href=""><?php echo $group['name']?></a></li></td>
    <td>
    <ul>
    	<?php 
		$i = $group['id'];
		foreach($group['groups'] as $sc):
		?>
    	<li><label for="chk-sub-<?php echo $sc['name']?>">
        	<input type="checkbox" name="chk-groups[groups-<?php echo $i?>][]" class="chk-sub-<?php echo $group['id']?>" value="<?php echo $sc['name']?>" alt="<?php echo $group['id']?>" id="chk-sub-<?php echo $sc['name']?>" />
            <?php echo $sc['name']?>
            </label>
        </li>
        <?php endforeach;
		?>
    </ul>
    </td>
  </tr>
   
<?php
endforeach;
endif;	//if($arrGroups):
?>

</tbody>
</table>


<h3>Select Fields</h3>
<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th><input type="checkbox" value="" onclick="toggleCheckedVars(this.checked)" /></th>
    <th width="26%">Tag</th>
    <th width="39%">Detail</th>
    <th width="22%">Required?</th>
  </tr>
</thead>

<tbody>
<?php
$c=0;
foreach($arrVars as $var):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&tag='.$var['tag'].'&act=del-var';	
?>
<input type="hidden" name="chk-vars[<?php echo $c?>][label]" value="<?php echo $var['name']?>" />
  <tr>
    <td><input type="checkbox" name="chk-vars[<?php echo $c?>][tag]" value="<?php echo $var['tag']?>" /></td>
    <td><?php echo $var['tag']?></td>
    <td><?php echo $var['name']?></li></td>
    <td><input type="checkbox" name="chk-vars[<?php echo $c?>][req]" value="1" /></td>
  </tr>
   
<?php
$c++;
endforeach;
?>

</tbody>
</table>

</div>
<!-- save this form -->
<div class="postbox">
<h3>Save this form</h3>
<div class="inside">
<p>
<label for="form-name">Form name</label>
<input type="text" name="form-name" class="regular-text" id="form-name" placeholder="Form name" />
</p>
<p>
<label for="form-detail">Form detail</label>
<input type="text" name="form-detail" class="regular-text" id="form-detail" placeholder="Form detail if required" />
</p>
<input type="submit" value="Save form" class="button" name="btn-form" />
</div>
</div>
</form>
</div> <!-- end shortcode-container -->
<?php endif;?>

<div style="clear:both"></div>

<script type="text/javascript">

check_email();

function check_email()
{
	jQuery('input[value="EMAIL,Email Address"], input[value="EMAIL"]').attr('checked', 'checked');
}

jQuery('input[value="EMAIL,Email Address"], input[value="EMAIL"]').click(function(){
	check_email();	
});

jQuery("input[name^=chk-groups]").click(function(){
	//alert(jQuery(this).attr('alt'));
	var gid = jQuery(this).attr('alt');
	if(jQuery(this).is(":checked"))
	{
		jQuery("#chk-group-"+gid).attr('checked', 'checked');
	}
	else
	{
		jQuery("#chk-group-"+gid).attr('checked', '');
	}
	//jQuery(this).parents().find('checkbox').attr('checked', 'checked');
});


function checkSub(group, status) {
	//jQuery("input[name^=chk-group]").each( function() {
		jQuery(".chk-sub-"+group).each( function() {
	jQuery(this).attr("checked",status);
	});
}

function toggleCheckedVars(status) {
	jQuery("input[name^=chk-vars]").each( function() {
	jQuery(this).attr("checked",status);
	});
	
	check_email();
}
</script>

<?php
/* load existing forms */
$froms = dirname(__FILE__).'/forms-list.php';
include ($froms);
?>