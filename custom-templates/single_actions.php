<?php
/*
	single news
*/
?>
<div class="pagename_block">
	<div class="wrapper">
		<div class="block_title striped">
			Акции
		</div>
	</div>
	<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
</div>
<main>
	<div class="wrapper">
		<div class="page_content_block">
			<h2>
				<?php the_title(); ?>
			</h2>
			<br>
		   	<?php the_content(); ?>
			<?php
				$query = new WP_Query(array(
				'cat' => $curr_cat->term_id,
				"orderby" => "date",
				'posts_per_page' => 5,
				"order" => 'ASC',
				"post__not_in" => array(get_the_ID())
				));
				if( $query->have_posts() ){ ?>
					<br>
					<br>
					<h2 class="post-title">
						Другие акции:
					</h2>
					<?php
						while( $query->have_posts() ){ $query->the_post();
					?>  
						<p>
							<a href="<?php the_permalink(); ?>" class="online_link">
								<?php echo strip_tags( get_the_title() ); ?>
							</a>							
						</p>
					<?php
						}
						wp_reset_postdata();
					?>
				<?php
				} 
			?>  

		</div>
	</div>
</main>
