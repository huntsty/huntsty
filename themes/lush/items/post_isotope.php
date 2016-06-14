<?php global $enable_excerpts, $show_post_date, $show_date, $isocol; ?>

<div class="news-grid-wrap <?php echo $isocol; ?>">
	<a href="<?php echo get_permalink();?>">
		<?php if(has_post_thumbnail()): ?>
			<?php the_post_thumbnail('medium'); ?>
		<?php endif; ?>
		<div class="news-grid-tab">
			<div class="tab-text">
				<?php if($show_post_date || $show_date): ?>
				<time class="datetime" datetime="<?php the_time('c'); ?>"><?php the_time( get_option('date_format') ); ?></time>
				<?php endif; ?>
				<div class="tab-title"><?php the_title(); ?></div>
				<?php if($enable_excerpts): ?>
				<div class="excerpt">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</a>
	<div class="clear"></div>
</div>