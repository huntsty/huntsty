<?php

$term = get_field('vid_category');

if ( $term && is_array($term) )
{
	$term = get_term($term[0], 'video-category');
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('media-block'); ?>>
	<a href="<?php the_permalink(); ?>">
		<div class="holder">
			<div class="image rel">
				<div class="play-button"><i class="fa fa-play-circle"></i></div>
				<?php the_post_thumbnail('large'); ?>
			</div>
			<div class="text-box">
				<h2><?php the_title(); ?></h2>
			</div>
		</div>
	</a>
</article>