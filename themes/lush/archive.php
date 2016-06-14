<?php

$is_ajax = !empty($_POST["ajax"]) ? true : false;

if($is_ajax) {

	include_once(locate_template('archive-ajax.php'));
	
}else{
	
	get_header();
	include_once(locate_template('archive-settings.php'));
		
	?>
	
			<!-- container -->
			<div class="container">
			<div class="boxed">
	
				<?php if(empty($hide_page_title)): ?>
				<span class="heading-t"></span>
				<h1>
					<?php
						echo $archive_title;
					?>
				</h1>
				<span class="heading-b"></span>
				<?php endif; ?>
				
				<?php echo $archive_content; ?>
	
	<?php
	if ( $has_sidebar ) :
	?>
				<div id="twocolumns" class="content__wrapper<?php if ( 'left' === $sidebar_position ) echo ' content--rev'; ?>">
					<div id="content" class="content__main">
	<?php
	endif;
	?>
	
				<!-- post-list -->
	<?php
	if ( $paginate_method == 'paginate_more' ) :
	?>
	
					<<?php echo $tag; ?> id="post-list" class="<?php echo $class; ?>"></<?php echo $tag; ?>>
	
	<?php
	else :
	?>
	
					<<?php echo $tag; ?> id="post-list" class="<?php echo $class; ?>">
	
	<?php
	
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
	
				get_template_part('items/' . $item_template);
	
			endwhile;
		else:
			echo __('Nothing Found!', IRON_TEXT_DOMAIN);	
		endif;
	
	?>
	
					</<?php echo $tag; ?>>
	
	<?php
	endif;
	
	if ( $paginate_method == 'paginate_more' ) :
	?>
			<?php 
				$next_link = '<a href="#" onclick="this.href = location.href" data-rel="post-list" '.implode(' ', $attr).' class="button-more">'.__('More', IRON_TEXT_DOMAIN).'</a>';
				echo $next_link;
			?>
	
	<?php
	elseif ( $paginate_method == 'paginate_links' ) :
	?>
	
					<div class="pages full clear">
						<?php iron_full_pagination(); ?>
					</div>
	
	<?php
	else :
	?>
	
					<div class="pages clear">
						<div class="alignleft"><?php previous_posts_link('&laquo; '.__($prev), ''); ?></div>
						<div class="alignright"><?php next_posts_link(__($next).' &raquo;',''); ?></div>
					</div>
	
	<?php
	endif;
	
	if ( $has_sidebar ) :
	?>
					</div>
	
					<aside id="sidebar" class="content__side widget-area widget-area--<?php echo esc_attr( $sidebar_area ); ?>">
	<?php
		do_action('before_ironband_sidebar_dynamic_sidebar', 'archive.php');
	
		dynamic_sidebar( $sidebar_area );
	
		do_action('after_ironband_sidebar_dynamic_sidebar', 'archive.php');
	?>
					</aside>
				</div>
	<?php
	endif;
	?>
			</div>
			</div>
	
	<?php
	get_footer();
}	