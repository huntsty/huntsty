<?php
//don't remove this
$selected = 'selected="selected"';
$arrForms = nmMailChimp::getForms();
?>
<p>
	<label><?php _e('Title', 'nm_mailchimp_plugin')?><br>
    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo attribute_escape($instance['nm_mc_title'])?>" />
    </label>   
</p>

<p>
	<label><?php _e('Detail', 'nm_mailchimp_plugin')?><br>
    <textarea rows="5" cols="20" class="widefat" id="<?php echo $field_id_detail?>" name="<?php echo $field_name_detail?>"><?php echo attribute_escape($instance['nm_mc_detail'])?></textarea>
    <em><?php _e('If needed provide more detail to show above form', 'nm_mailchimp_plugin')?></em>
    </label>
   
</p>


<p>These forms created <a href="link">here</a>
	<label><?php _e('Select form', 'nm_mailchimp_plugin')?><br>
    <select name="<?php echo $field_name_form?>" id="<?php echo $field_id_form?>">
    <option value=""><?php _e('Select', 'nm_mailchimp_plugin')?></option>
    <?php 
	foreach($arrForms as $form):
		$fid = $form -> form_id;
		$fname = $form -> form_name;
		$selected = ($fid == $instance['nm_mc_form_id']) ? 'selected = "selected"' : '';
	?>
    <option value="<?php echo $fid?>" <?php echo $selected?> ><?php echo $fname?></option>
    <?php endforeach;?>
    </select>
    </label>
</p>

<p>
	<label><?php _e('Button Text', 'nm_mailchimp_plugin')?><br>
    <input type="text" class="widefat" id="<?php echo $field_id_button?>" name="<?php echo $field_name_button?>" value="<?php echo attribute_escape($instance['nm_mc_button_text'])?>" />
    </label>
   
</p>