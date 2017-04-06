<?php
/*
Template Name: Booking
*/
?>

<?php get_header(); ?>


 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<main>
		<div class="pagename_block">
			<div class="wrapper">
				<div class="block_title striped">
					Онлайн-запись
				</div>
			</div>
			<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
		</div>		
		<div class="service_select_block">
			<div class="wrapper">
   				<?php the_content(); ?>
			</div>
		</div>
	</main>	

 <?php endwhile; else: ?>

 <?php endif; ?>

<?php get_template_part('footer_tile'); ?>
<?php get_footer(); ?>
