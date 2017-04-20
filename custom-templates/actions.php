<?php
/*
Template Name: Actions.php
*/
?>

<?php get_header(); ?>
	<div class="pagename_block">
		<div class="wrapper">
			<div class="block_title striped">
				<?php the_title(); ?>
			</div>
		</div>
		<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
	</div>
	<main>
		<div class="wrapper">
			<div class="page_content_block">
				<?php
				  $actions_cat = intval( get_field('actions_cat_id', $opID) );
				  // set up or arguments for our custom query
				  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				  $query_args = array(
				    'cat' => $actions_cat,
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
							<?php the_post_thumbnail( null, array('data-fit'=>'true') ); ?>
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
<?php get_footer(); ?>
