<?php

$term = get_field('vid_category');

if ( $term && is_array($term) )
{
	$term = get_term($term[0], 'video-category');
}

?>
<div class="videogrid">
	<a href="<?php the_permalink(); ?>">
		<div class="holder">
			<div class="image">
				<div class="play-button">
					<i class="fa fa-play-circle"></i>
				</div>
				<?php the_post_thumbnail('large'); ?>
			</div>
			<div class="text-box">
				<h2><?php the_title(); ?></h2>
<?php if ( ! empty($term->name) ) { ?>
				<span class="category"><?php echo $term->name; ?></span>
<?php } ?>
			</div>
		</div>
	</a>
</div>