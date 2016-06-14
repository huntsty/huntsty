<?php get_header(); ?>

<?php 

$title = get_iron_option('404_page_title'); 
$content = get_iron_option('404_page_content'); 
	
	
?>
	<!-- container -->
	<div class="container">
	
		<div class="content__wrapper boxed">
			<!-- single-post -->
			<article class="single-post">
				<span class="heading-t"></span>
				<h1><?php _e($title, IRON_TEXT_DOMAIN); ?></h1>
				<span class="heading-b"></span>
				
				<?php _e($content, IRON_TEXT_DOMAIN); ?>
			</article>
		</div>
	
	</div>

<?php get_footer(); ?>