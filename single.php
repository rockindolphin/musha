<?php
/*
Template Name: Single template
*/
?>

<?php get_header(); ?>
		<?php

		$news_cat = intval( get_field('news_cat_id', $opID) );
		$actions_cat = intval( get_field('actions_cat_id', $opID) );
		$curr_cat = get_the_category();
		$curr_cat = $curr_cat[0];
		while ( have_posts() ) : the_post();
			switch ($curr_cat->term_id) {
				case $news_cat:
					require_once('single_news.php'); 
				break;
				case $actions_cat:
					require_once('single_actions.php'); 
				break;
				default:
					?>
					<main>
						<div class="wrapper">
							<div class="page_content_block">					
								 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
									<time class="post-date">
										<?php the_date('d.m.Y'); ?>
									</time>
									<h1 class="post-title">
										<?php the_title(); ?>
									</h1>

								   	<?php the_content(); ?>

								 <?php endwhile; else: ?>

								 <?php endif; ?>
							</div>
						</div>
					</main>
					 <?php
				break;
			}

		endwhile;
		?>

<?php get_footer(); ?>

