<?php get_header(); ?>
		<!-- container -->
		<div class="container">
		<div class="boxed">
		
			<!-- single-post -->
			<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
				<span class="heading-t"></span>
				<?php the_title('<h1>','</h1>'); ?>
				<span class="heading-b"></span>
				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', IRON_TEXT_DOMAIN ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
				</div>
			</article>
		</div>
		</div>
<?php get_footer(); ?>