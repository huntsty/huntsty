<?php
/**
rendering form for user
**/

$form = nmMailChimp::getForm($fid);
$meta = json_decode($form -> form_meta);

$arrVars = $meta -> vars;
/* echo '<pre>';
print_r($meta);
echo '</pre>'; */
?>

<!-- loading custom css here -->
<style>
<?php echo get_option('nm_mc_custom_css')?>
</style>


<div class="nm_mc_form">
<form id="nm_mc_form_<?php echo $widget_id?>" onsubmit="return postToMailChimp(this)">
  <input type="hidden" value="<?php echo $fid?>" name="nm_mc_form_id" />
  <ul>
    <?php
    if ($arrVars){
	foreach($arrVars as $key => $val):
  		$tag = $val -> tag;
		$label = $val -> label;
		$the_id = $tag.'-'.$widget_id;
	?>
    <li>
      <label for="<?php echo $the_id?>"><?php echo $label?></label>
      <input type="text" name="<?php echo $tag?>" id="<?php echo $the_id?>" class="nm_mc_input" data-required="<?php echo $val -> req?>" />
    </li>
    <?php 
    endforeach;
    }
    ?>
    
    
    <!-- show interest -->
    <?php if ($meta -> interest){		//if interests are selected
    
	foreach ($meta -> interest as $interest){
		
		$groups = explode(',', $interest->groups)
	?>
    <li>
    <strong><?php echo $interest->name?></strong>
    
    	<?php 
    	$g = 1;
    	foreach ($groups as $group){
    		
    	?>  
    			<div class="nm_mc_interests">
				<input type="checkbox" name="group[<?php echo $interest->id?>][<?php echo $g?>]" id="mc_<?php echo $interest->id?>_<?php echo $widget_id?>" class="nm_mc_interest" value="<?php echo $group?>">
				<label for="mc_<?php echo $interest->id?>_<?php echo $widget_id?>"	class="nm_mc_interest_label"><?php echo $group?></label>
		<?php
		$g++;
    	} 
		
		?>

	</div>
	</li>
	<?php
	}
	} 
	?>
	
    <li>
      <input type="submit" class="nm_mc_button" value="<?php echo $button_text?>" id="nm_mc_button-<?php echo $widget_id?>"  />
     <?php
		echo '<img style="display:none" id="nm-mc-loading" src="'.plugins_url( 'images/loading.gif' , __FILE__).'" />';
	?>
    <div id="mc-response-area">
	</div>
    </li>
  </ul>
  </form>
</div>
