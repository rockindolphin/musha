<?php
/*
Template Name: Главная страница
*/
?>

<?php

	function textTile($value){
		?>
			<div class="tile <?php echo $value['tile_class']; ?>">
				<div class="tile_content">
					<div class="tile_title">
						<?php  echo $value['tile_title']; ?>
					</div>
					<a class="online_link" href="<?php echo $value['tile_link']; ?>">
						<?php echo $value['tile_link_text']; ?>
					</a>	
				</div>
				<div class="tile_expand"></div>
			</div>
		<?php
	}

	function pictureTile($value){
		?>
			<div class="tile">
				<div class="tile_content tile_content-pic">
					<img src="<?php echo $value['tile_img']; ?>" alt="<?php echo $value['tile_img_alt']; ?>" data-fit="true">											
				</div>
				<div class="tile_expand"></div>
			</div>
		<?php
	}

	function text_pictureTile($value){
		?>
			<div class="tile tile-big">
				<div class="tile_content tile_content-pic">
					<img src="<?php echo $value['tile_img']; ?>" alt="<?php echo $value['tile_img_alt']; ?>" data-fit="true">
					<?php textTile($value); ?>										
				</div>
				<div class="tile_expand"></div>
			</div>
		<?php
	}

	function getTile( $repeaterName ){
		$repeater = get_field($repeaterName);
		foreach ($repeater as $key => $value) {
			switch ($value['tile_type']) {
				case 'text':
					textTile($value);
					break;
				case 'picture':
					pictureTile($value);
					break;
				case 'text_picture':
					text_pictureTile($value);
					break;
				
				default:
					return;
					break;
			}
		}
	}

?>	

<?php get_header(); ?>
	<main>
		<div class="bg_block-first">
			<img src="<?php the_field('bg_img_1'); ?>" data-bg="image">
		</div>
		<div class="tiles_block">
			<div class="wrapper">
				<div class="block_title tiles_title">
					<?php the_field('tiles_title'); ?>
				</div>
				<div class="tiles">
					<div class="tiles-side">
						<?php getTile('tiles_repeater-left')?>
					</div>
					<div class="tiles-side">
						<?php getTile('tiles_repeater-right')?>
					</div>
				</div>
			</div>
		</div>
		<div class="actions_block square_block">
			<img src="<?php the_field('bg_img_2'); ?>" data-bg="image">
			<div class="wrapper">
				<div class="square_block-wrapper">
					<div class="square_block-expand"></div>
					<div class="square_block-content">
						<div class="block_title striped white">
							<?php the_field('actions_title'); ?>
						</div>
						<?php the_field('actions_content'); ?>
					</div>									
				</div>
			</div>
		</div>
		<div class="about_block">
			<div class="wrapper">
				<div class="block_title">
					О салоне
				</div>
				<div class="about_content">
					<p>
						<?php the_field('about_text'); ?>
					</p>
				</div>
			</div>
		</div>
		<div class="publications_block">
			<div class="wrapper">
				<?php
					if ( function_exists( 'envira_gallery' ) ) { 
						$gallery_id = get_field('about_images');
						$gallery = $envira_gallery->get_gallery( $gallery_id );
						if( $gallery && get_post_status($gallery['id']) === 'publish' ){
						?>
						<div class="swiper-container publications-slider">
							<div class="swiper-wrapper">
							<?php
								$xids = array();
								foreach ($gallery['gallery'] as $post_id => $post) {
									array_push($xids, $post_id);
								}	
								$info = getAttachInfo($xids);
								$publications_link = page_link_by_slug('publications');
								foreach ($gallery['gallery'] as $post_id => $post) {
									?>
									<div class="swiper-slide">
										<a href="<?php echo $publications_link.'#'.$post_id; ?>">
										<?php
											$attach = getAttach($post_id,$post,$info);											
											require_once( 'custom-templates/publications.php' );
											echo publicationHtml($attach,false);										
										?>
										</a>
									</div>									
									<?php
								}																			
							?>
							</div>
						</div>
						<?php
						}
					}
				?>
			</div>
		</div>
		<div class="brands_block">
			<div class="wrapper">
				<?php 
					the_post();
					$repeater = get_field('brands_repeater');
					foreach ($repeater as $key => $value) {
						echo '<a href="'.$value['brand_link'].'" target="_blank" rel="nofollow">
								<img src="'.$value['brand_img'].'" alt="'.$value['brand_img_alt'].'">
							 </a>';
					}
				?>	
			</div>
		</div>
	</main>
<?php get_template_part('custom-templates/footer_tile'); ?>
<?php get_footer(); ?>
