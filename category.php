<?php
/*
Template Name: Category template
*/
?>

<?php get_header(); ?>
		<?php

		$beauty_shop_cat = intval( get_field('beauty_shop_cat_id', $opID) );
		$curr_cat = get_the_category();
		$curr_cat = $curr_cat[0];
		if( $curr_cat->parent === $beauty_shop_cat ){
			require_once('custom-templates/beauty_shop.php');
		}else{
			?>
			<main>
				<div class="wrapper">
					<div class="page_content_block">
						<?php
						  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
						  $query_args = array(
						    'cat' => $curr_cat,
						    'posts_per_page' => 8,
						    'paged' => $paged,
						    'order' => 'DESC'
						  );
						  // create a new instance of WP_Query
						  $the_query = new WP_Query( $query_args );
						  $curr_page = $the_query->query['paged'];
						?>

						<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>
							<a href="<?php the_permalink(); ?>" class="news-post-item">
								<div class="post-pic">
									<?php the_post_thumbnail(); ?>
								</div>
								<div class="post-content">
									<div class="post-date">
										<?php echo get_the_date('d m Y'); ?>
									</div>
									<h2 class="post-title">
										<?php echo the_title(); ?>
									</h2>
									<div class="post-text">
										<?php the_excerpt(); ?>
									</div>
								</div>                
							</a>			  
						<?php endwhile; ?>
					<?php endif; ?>
					</div>
				</div>
			</main>
			<?php
		}
		?>

<?php get_footer(); ?>