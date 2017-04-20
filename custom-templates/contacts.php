<?php
/*
Template Name: Contacts.php
*/
?>

<?php get_header(); ?>
	<main>	
		<div class="pagename_block">
			<div class="wrapper">
				<div class="block_title striped">
					<?php the_title(); ?>
				</div>
			</div>
			<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
		</div>
		<div class="contacts_block">
			<div class="wrapper">
				 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				   	<?php the_content(); ?>

				 <?php endwhile; else: ?>

				 <?php endif; ?>
			</div>
		</div>
		<div class="map_block">
			<a name="map"></a>
			<div class="block_title">
				Карта проезда
			</div>	
			<div class="map">
				<!-- <img src="<?php bloginfo('template_url'); ?>/images/map.jpg" alt=""> -->
				<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=-kOqVe5VAo7s_Av6QzN6IulOco8VyPOD&amp;width=1280&amp;height=595&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>		
			</div>			
		</div>		
		<div class="home_service_block">
			<div class="wrapper">
				<?php the_field('home_service'); ?>
			</div>
		</div>
	</main>

<?php get_footer(); ?>
