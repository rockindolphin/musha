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

				$args = array(
					'post_type'	 => 'al_product',				
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
			</div>
		</div>


	</main>
<?php get_footer(); ?>
