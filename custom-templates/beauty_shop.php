<?php
/*
Template Name: Beauty_shop.php
*/
?>

<?php get_header(); ?>
	<div class="pagename_block">
		<div class="wrapper">
			<div class="block_title striped">
				Beauty Shop
			</div>
			<?php			
				$categories = get_terms(array(
				    'taxonomy' => 'al_product-cat',
				    'hide_empty' => true,
				));				
			?>
				<div class="services bs-services">
				<?php
					foreach ($categories as $key => $value) {	
						$cat_pic_id = get_product_category_image_id($value->term_id);
						$cat_pic_url = wp_get_attachment_url($cat_pic_id);
						?>
						<a href="?shop_cat=<?php echo $value->term_id; ?>">
							<div class="service">
								<div class="service_image">			
									<img src="<?php  echo $cat_pic_url;?>" alt="<?php  echo $value->name;?>">
								</div>
								<div class="service_name">
									<?php  echo $value->name;?>
								</div>
							</div>
						</a>							
						<?php
					}
				?>			
				</div>
		</div>
		<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
	</div>
	<main>
		<div class="wrapper">
			<div class="page_content_block">
				<?php

				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$args = array(
					'post_type'	 => 'al_product',
					'paged' => $paged,
					'posts_per_page' => 15				
				);

				if( isset($_GET['shop_cat']) ){
					$shop_cat = intval($_GET['shop_cat']);
					if( $shop_cat > 0 ){
						$args['tax_query'] = array(
					        array(
					            'taxonomy'      => 'al_product-cat',
					            'terms'         => $shop_cat,
					        )
				        );						
					}
				}

				$the_query = new WP_Query( $args );
				$curr_page = $the_query->query['paged'];

				?>
				<div class="shop-items">
					<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>
							<div class="shop-item">
								<div class="shop-item-pic">
									<?php the_post_thumbnail( null, array('data-fit'=>'true') ); ?>
								</div>
								<div class="shop-item-content">
									<span class="shop-item-title">
										<?php echo the_title(); ?>
									</span>
									<div class="shop-item-cost">
										<?php 
											$price = get_post_meta(get_the_ID(),'_price');
											echo $price[0]; 
										?> р.
									</div>
								</div>                
							</div>			  
						<?php endwhile; ?>
					<?php endif; ?>
				</div>


				<?php if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
				  <nav class="pagination">
			      	<?php 
				      	echo get_previous_posts_link( '<span class="pagination-text-link"> < </span>' );
				    	for ($i=1; $i <= $the_query->max_num_pages; $i++) {
				    		if( $i < $curr_page-3 || $i > $curr_page+3 ){
				    			continue;
				    		}
				    		$item_class = '';
				    		if( $i === $curr_page ){ 
				    			$item_class='pagination-current'; 
				    		}
				    		$next_link = get_next_posts_link('<span class="'.$item_class.'">'.$i.'</span>' ,$the_query->max_num_pages);
				    		echo preg_replace( '/page\/(\d*)\//', 'page/'.$i, $next_link );
				    	}			    	
				      	echo get_next_posts_link( '<span class="fs-pagination-text-link"> > </span>', $the_query->max_num_pages ); 
			      	?>
				  </nav>
				<?php } ?>			

			</div>
		</div>


	</main>
<?php get_footer(); ?>
