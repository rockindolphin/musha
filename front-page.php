<?php
/*
Template Name: Главная страница
*/
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
					<?php
						$repeater = get_field('tiles_repeater');
						foreach ($repeater as $key => $value) {
							if ( $value['tile_type'] === 'text' ){
							?>
								<div class="tile <?php echo $value['tile_class']; ?>">
									<div class="tile_content">
										<div class="tile_title">
											<?php  echo $value['tile_title']; ?>
										</div>
										<a class="online_link" href="<?php echo $value['tile_link']; ?>">онлайн-запись</a>	
									</div>
									<div class="tile_expand"></div>
								</div>
							<?php
							}else{
								?>
									<div class="tile <?php echo $value['tile_class']; ?>">
										<div class="tile_content tile_content-pic">
											<img src="<?php echo $value['tile_img']; ?>" alt="<?php echo $value['tile_img_alt']; ?>">
										</div>
										<div class="tile_expand"></div>
									</div>
								<?php								
							}
						}							
					?>
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
					<div class="about_gallery">
						<?php 
							$repeater = get_field('about_images'); 
							foreach ($repeater as $key => $value) {
								echo '<img src="'.$value['about_img'].'" alt="'.$value['about_img_alt'].'">';
							}							
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="brands_block">
			<div class="wrapper">
				<?php 
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
	
<?php get_template_part('footer_tile'); ?>
<?php get_footer(); ?>
