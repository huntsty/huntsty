<?php

$banner_bg_style = '';
$banner_classes = array();
$banner_background_type = get_field('banner_background_type', $post->ID);
$banner_background_color = get_field('banner_background_color', $post->ID);
$banner_parallax = get_field('banner_parallax', $post->ID);
$banner_image = get_field('banner_image', $post->ID);
$banner_fullscreen = get_field('banner_fullscreen', $post->ID);
$banner_height = get_field('banner_height', $post->ID);
$banner_content_type = get_field('banner_content_type', $post->ID);
$banner_texteditor_content = get_field('banner_texteditor_content', $post->ID, false);
$banner_title = get_field('banner_title', $post->ID);
$banner_subtitle = get_field('banner_subtitle', $post->ID);
$banner_horizontal_content_alignment = get_field('banner_horizontal_content_alignment', $post->ID);
$banner_vertical_content_alignment = get_field('banner_vertical_content_alignment', $post->ID);
$banner_background_alignement = get_field('banner_background_alignement', $post->ID);
$banner_font_color = get_field('banner_font_color', $post->ID);

if(empty($banner_title))
	$banner_title = get_the_title();

if($banner_fullscreen) {
	$banner_height = 0;
	array_push($banner_classes, 'fullscreen-banner');
}
else if (intval($banner_height) == 0) {
	$banner_height = 350;
}

if($banner_parallax) array_push($banner_classes, 'parallax-banner');

if($banner_background_type === 'image-background') {
	$banner_bg_style = 'style="background:url('.wp_get_attachment_url( $banner_image ).');background-position:center '.$banner_background_alignement.'"';

} else if ($banner_background_type === 'color-background' ) {
	$banner_bg_style = 'style="background:'.$banner_background_color.'"';
}

?>


<div id="page-banner" class="<?php echo implode(' ',$banner_classes); ?>" <?php if(intval($banner_height) > 0) echo 'style="height:'.intval($banner_height).'px"'; ?>>
	<div class="page-banner-bg" <?php echo $banner_bg_style; ?>></div>
	<div class="page-banner-content">
		<div class="inner <?php echo $banner_vertical_content_alignment; ?>">
			<div class="page-banner-row">
			<?php if($banner_content_type === 'advanced-content') : ?>
				<?php  
					echo apply_filters( 'the_content', $banner_texteditor_content );
					$content = str_replace( ']]>', ']]&gt;', $content );
					echo $content;
				?>
			<?php else : ?>
				<h1 class="page-title <?php echo $banner_horizontal_content_alignment; ?>" <?php if(!empty($banner_font_color)) { echo 'style="color:'.$banner_font_color.'"';}?>>
					<?php echo $banner_title; ?>
				</h1>
				<span class="page-subtitle <?php echo $banner_horizontal_content_alignment; ?>" <?php if(!empty($banner_font_color)) { echo 'style="color:'.$banner_font_color.'"';}?>>
					<?php echo $banner_subtitle; ?>
				</span>
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>

