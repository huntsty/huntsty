<?php
//don't remove this
$selected = 'selected="selected"';
?>
<p>
	<label><?php _e('Title', 'nm_mailchimp_plugin')?><br>
    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo attribute_escape($instance['nm_mc_title'])?>" />
    </label>
   
</p>


<p>
	<label><?php _e('Box Title', 'nm_mailchimp_plugin')?><br>
    <input type="text" class="widefat" id="<?php echo $field_id_box_title?>" name="<?php echo $field_name_box_title?>" value="<?php echo attribute_escape($instance['nm_mc_box_title'])?>" />
    </label>
   
</p>

<p>
	<label><?php _e('Select List', 'nm_mailchimp_plugin')?><br>
    <select name="<?php echo $field_name_list?>" id="<?php echo $field_id_list?>">
    <option value=""><?php _e('Select List', 'nm_mailchimp_plugin')?></option>
    <?php foreach($arrList as $list):
			$selected = ($list['id'] == $instance['nm_mc_list_id']) ? 'selected = "selected"' : '';
	?>
    <option value="<?php echo $list['id']?>" <?php echo $selected?> ><?php echo $list['name']?></option>
    <?php endforeach;?>
    </select>
    </label>
</p>

<p>
	<label><?php _e('Show Names [Optional]', 'nm_mailchimp_plugin')?><br>
     <select name="<?php echo $field_name_names?>" id="<?php echo $field_id_names?>">
    <option value=""><?php _e('Select option', 'nm_mailchimp_plugin')?></option>
    <option value="1" <?php echo ($instance['nm_mc_show_names'] == 1) ? $selected : ''?>>
    	<?php _e('Yes', 'nm_mailchimp_plugin')?>
    </option>
    <option value="0" <?php echo ($instance['nm_mc_show_names'] == 0) ? $selected : ''?> >
    	<?php _e('No', 'nm_mailchimp_plugin')?>
    </option>
    </select>
    </label>
</p>