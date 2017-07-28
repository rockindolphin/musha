<?php
/*
Template Name: All services
*/
?>

<?php get_header(); ?>
<?php
	$services = bookingServices();
	$service = $services[1];

	$serv_and_prices_ID = intval( get_field('serv_and_prices_ID', $opID) );
	$hidden_cats_repeater = get_field('hidden_cats_repeater',$serv_and_prices_ID);
	$discount_repeater = get_field('discount_repeater',$serv_and_prices_ID);


	$serv_pages = get_posts( array(
		'post_type' => 'page',
		'post_parent' => page_id_by_slug('services_and_prices'),
		'posts_per_page' => -1
	));		

	function service_page_link( $slug ){
		global $serv_pages;
		foreach ($serv_pages as $key => $page) {
			$page_content = $page ->  post_content;
			preg_match('/\[show_cat name="(.+?)"\]/', $page_content, $shortcode_content);
			if( $shortcode_content[1] === $slug ){
				return get_permalink( $page -> ID);
			}
		}
	}

	function print_service_name($serv){
		global $hidden_cats_repeater;
		foreach ($hidden_cats_repeater as $key => $value) {
			if( $serv['ID'] === $value['hidden_cat'] ){ return; }
		}
		?>
		<a href="<?php echo service_page_link( $serv['SLUG'] ).'#list'; ?>">
			<div class="service">
				<div class="service_image">			
					<img src="<?php bloginfo('template_url'); ?>/images/service_<?php echo ($serv['SLUG']); ?>.jpg" alt="<?php echo $serv['CAT']; ?>">
				</div>
				<div class="service_name">
					<?php echo $serv['CAT']; ?>
				</div>
			</div>
		</a>
		<?php						
	}

	function showBookingCat( $atts, $content ){
		global $services, $service;
		foreach ($services as $key => $value) {
			if( $value['SLUG'] === $atts['name'] ){
				$service = $value;
			}
		}
	}
	add_shortcode( 'show_cat', 'showBookingCat' );

?>

<!-- вывод шорткода -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
   	<div class="service_desc hidden">
   		<?php 
   			the_content();
	 	?>
   	</div>
<?php endwhile; else: ?>
<?php endif; ?>		

	<main>
		<div class="pagename_block">
			<div class="wrapper">
				<div class="block_title striped">
					Услуги и цены
				</div>			
				<div class="services">

					<?php array_map(print_service_name, $services); ?>		

				</div>
			</div>
			<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
		</div>
		<a name="list">&nbsp;</a>
		<div class="all_services_block">
			<div class="wrapper">
				<?php
					$discount_class = '';
					if( $discount_repeater !== null ){
						foreach ($discount_repeater as $key => $value) {
							if( $service['ID'] === $value['discount_cat'] ){ $discount_class = 'discount'.$value['discount_val'][0]; }
						}
					}
				?>
				<div class="block_title <?php echo $discount_class; ?>">

					<?php echo $service['CAT']; ?>

				</div>				
				<div class="price_list with_bullits">
					<div class="price_head">
						<span>Услуга</span>
						<span>цена, руб.</span>
					</div>
					<?php 
						$servSort =  array();
						foreach ($service['SRV'] as $key => $value) {
							$servSort[$value['ID']] = $value;
						}
						array_multisort ($servSort);					
						foreach ($servSort as $key => $value) {
							$format = '%s';
							?>
							<div class="price_row sln_services_<?php echo $value['ID']?>">
								<a href="<?=site_url();?>/booking?cat=<?=$service['ID']?>&service=<?=$value['ID']?>" class="s_name">
									<?php echo $value['NAME']; ?>
								</a>
							<?php
								if( mb_strtoupper($service['CAT']) === 'СТРИЖКА И УКЛАДКА' ){
									if( strpos( mb_strtoupper($value['NAME']), 'БОТОКС' ) !== false ){
										$format = 'от %s р.';
									}
								}							
								if( $value['PRICE'] ){
							?>
									<span class="s_cost"><?php echo sprintf($format, $value['PRICE']); ?></span>
							<?php
								}
							?>
								<div class="s_desc"><?php echo $value['DESC']; ?></div>
							</div>
							<?php								
						}
					?>	
					<div class="divider"></div>			
				</div>
			</div>
		</div>
	</main>
	<script>
		/*(function(){
			var desc = $('.service_desc.hidden');
			$('.price_list').after(desc);
			$(desc).removeClass('hidden');

			//replacements
			var target = $('.sln_services_1235');
			var place = $('.sln_services_1067');
			place.before(target);

			target = $('.sln_services_1247');
			place = $('.sln_services_939');
			place.after(target);

			var target1 = $('.sln_services_982');
			var target2 = $('.sln_services_984');
			var target3 = $('.sln_services_986');
			place = $('.sln_services_931');
			place.after(target1);
			target1.after(target2);
			target2.after(target3);

			target = $('.sln_services_1245');
			place = $('.sln_services_905');
			place.before(target);

		}());*/
	</script>
<?php get_template_part('footer_tile'); ?>
<?php get_footer(); ?>
