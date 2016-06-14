<?php

get_header();
?>

<?php
global $post;

$archive_page = get_option('page_for_posts');
$archive_page = ( empty($archive_page) ? false : post_permalink($archive_page) );

$single_post_featured_image = get_field('single_post_featured_image');
$show_post_date = (bool)get_iron_option('show_post_date');
$show_post_author = (bool)get_iron_option('show_post_author');
$show_post_categories = (bool)get_iron_option('show_post_categories');
$show_post_tags = (bool)get_iron_option('show_post_tags');


/**
 * Setup Dynamic Sidebar
 */

list( $has_sidebar, $sidebar_position, $sidebar_area ) = setup_dynamic_sidebar( $post->ID );

?>

		<!-- container -->
		<div class="container">
		<div class="boxed">
		
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
?>
		<?php
		$single_title = get_iron_option('single_post_page_title');
		if(!empty($single_title)): 
		?>
		
			<span class="heading-t"></span>
				<h1><?php echo $single_title; ?></h1>
			<span class="heading-b"></span>
		
		<?php else: ?>
			
			<div class="heading-space"></div>
			
		<?php endif; ?>

<?php
		if ( $has_sidebar ) :
?>
			<div id="twocolumns" class="content__wrapper<?php if ( 'left' === $sidebar_position ) echo ' content--rev'; ?>">
				<div id="content" class="content__main">
<?php
		endif;
?>
					<!-- single-post -->
					<div id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
						<?php the_title('<h2>','</h2>'); ?>
						<?php if($show_post_date): ?>
						<time class="datetime" datetime="<?php the_time('c'); ?>"><?php the_date(); ?></time>
						<?php endif ;?>
						<div class="split"></div>
						
						<?php 
						if($single_post_featured_image == 'fullwidth') {
							the_post_thumbnail( array(696, 353), array( 'class' => 'wp-featured-image fullwidth' ) ); 
						}else if($single_post_featured_image == 'original') {
							the_post_thumbnail( array(696, 353), array( 'class' => 'wp-featured-image original' ) );
						}	
						?>

						<!-- meta -->
						<div class="meta">
						<?php if($show_post_author): ?>
						<?php _e('Posted by', IRON_TEXT_DOMAIN);?> <a href="#"><?php the_author(); ?></a>
						<?php endif; ?>
						
<?php if($show_post_categories): ?>						
		
		<?php
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( __(', ', IRON_TEXT_DOMAIN) );
		if ( $categories_list ) {
			echo '<span class="links categories-links">' . sprintf(_x('%s: ', 'A term followed by a punctuation mark used to explain or start an enumeration.', IRON_TEXT_DOMAIN), __('Category', IRON_TEXT_DOMAIN)) . $categories_list . '</span>';
		}
		?>
		
<?php endif; ?>

<?php if($show_post_tags): ?>						

		<?php
		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', __(', ', IRON_TEXT_DOMAIN) );
		if ( $tag_list ) {
			echo '<span class="links tags-links">' . sprintf(_x('%s: ', 'A term followed by a punctuation mark used to explain or start an enumeration.', IRON_TEXT_DOMAIN), __('Tag', IRON_TEXT_DOMAIN)) . $tag_list . '</span>';
		}
		?>
		
<?php endif; ?>		
						</div>
						
						<div class="split"></div>
						<div class="entry">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', IRON_TEXT_DOMAIN ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
						</div>

<?php	get_template_part('parts/share'); ?>

<?php	comments_template(); ?>
					</div>
<?php
		if ( $has_sidebar ) :
?>
				</div>

				<aside id="sidebar" class="content__side widget-area widget-area--<?php echo esc_attr( $sidebar_area ); ?>">
<?php
	do_action('before_ironband_sidebar_dynamic_sidebar', 'single-post.php');

	dynamic_sidebar( $sidebar_area );

	do_action('after_ironband_sidebar_dynamic_sidebar', 'single-post.php');
?>
				</aside>
			</div>
<?php
		endif;

	endwhile;
endif;
?>
		</div>
		</div>

<?php

get_footer();