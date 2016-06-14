<?php

function iron_shortcode_audioplayer( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'albums' => '',
	  'autoplay' => '',
	  'show_playlist' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Radio', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_radio '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_audioplayer', 'iron_shortcode_audioplayer' );

function iron_shortcode_discography( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'albums' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Discography', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_discography '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_discography', 'iron_shortcode_discography' );


function iron_shortcode_twitter( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'screen_name' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Twitter', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_twitter '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_twitter', 'iron_shortcode_twitter' );


function iron_shortcode_posts( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'number' => '',
	  'show_date' => 1,
	  'enable_excerpts' => 0,
	  'category' => '',
	  'view' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Posts', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_posts '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_posts', 'iron_shortcode_posts' );


function iron_shortcode_videos( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'number' => '',
	  'category' => '',
	  'view' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Videos', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_videos '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_recentvideos', 'iron_shortcode_videos' );


function iron_shortcode_photos( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'albums' => '',
	  'gallery_layout' => '',
	  'gallery_height' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Photos', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_photos '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_featuredphotos', 'iron_shortcode_photos' );


function iron_shortcode_iosslider( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'id' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Ios_Slider', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_iosslider '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_iosslider', 'iron_shortcode_iosslider' );


function iron_shortcode_events( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'number' => '',
	  'show_date' => null,
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Events', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_events '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_events', 'iron_shortcode_events' );


function iron_shortcode_event( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'event' => '',
	  'action_title' => '',
	  'action_obj_id' => '',
	  'action_ext_link' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
    the_widget('Iron_Widget_Event', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<div class="widget iron_widget_event '.$css_animation.'">', 'after_widget'=>'</div>'));
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_event', 'iron_shortcode_event' );


function iron_shortcode_divider( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'heading' => '',
	  'css_animation' => '',
	), $atts ) );

   	ob_start();
   	
   	$heading_num = $heading;
   	if($heading_num == 1) {
	   	$heading_num = "";
   	}
    ?>

    <?php if(!empty($title)): ?>
    <div class="widget iron_widget_divider <?php echo $css_animation; ?>">
    	<span class="heading-t<?php echo $heading_num;?>"></span>
	    <h<?php echo $heading;?> class="widgettitle"><?php echo $title; ?></h<?php echo $heading;?>>
	    <span class="heading-b<?php echo $heading_num;?>"></span>
    </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'iron_divider', 'iron_shortcode_divider' );


function iron_shortcode_newsletter( $atts ) {

	extract( shortcode_atts( array(
	  'title' => '',
	  'description' => '',
	  'fid' => '',
	  'css_animation' => '',
	), $atts ) );

?>
		<div class="widget iron_widget_newsletter">
			<div class="control-append">
				<div class="newsletter-wrap">
				
					<?php if(!empty($description)): ?>
						<div class="control-description">
							<?php echo $description; ?>
						</div>
					<?php endif; ?>	
					
					<?php echo do_shortcode('[nm-mc-form fid="'.$fid.'"]'); ?>
				</div>
			</div>
		</div>				
<?php						

    return $output;
}
add_shortcode( 'iron_newsletter', 'iron_shortcode_newsletter' );


function iron_shortcode_button( $atts ) {

	extract( shortcode_atts( array(
	  'text' => '',
	  'link_page' => '',
	  'link_product' => '',
	  'link_external' => '',
	  'border_width' => '1',
	  'border_radius' => '0',
	  'border_color' => '',
	  'background_color' => '',
	  'text_color' => '',
	  'text_align' => '',
	  'hover_bg_color' => '',
	  'hover_border_color' => '',
	  'hover_text_color' => '',
	), $atts ) );

	$link = '';
	$target = "_self";
	if(!empty($link_page)) {
		$link = get_permalink($link_page);
	} else if(!empty($link_product)) {
		$link = get_permalink($link_product);
	} else {
		$link = $link_external;
		$target = "_blank";
	}
	$output = '
	<a target="'.$target.'" data-hoverbg="'.$hover_bg_color.'" data-hoverborder="'.$hover_border_color.'" data-hovertext="'.$hover_text_color.'"  class="button-widget '.$text_align.'" href="'.esc_url($link).'" style="border-width:'.$border_width.'px; border-radius:'.$border_radius.'px; border-color:'.$border_color.'; background-color:'.$background_color.'; color:'.$text_color.'">'.$text.'</a><div class="clear"></div>';
	
    return $output;
}
add_shortcode( 'iron_button', 'iron_shortcode_button' );