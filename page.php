<?php
/*
Template Name: Page template
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
				 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				   	<?php the_content(); ?>

				 <?php endwhile; else: ?>

				 <?php endif; ?>
			</div>
		</div>
		<?php get_template_part('footer_tile'); ?>
	</main>
<?php get_footer(); ?>
