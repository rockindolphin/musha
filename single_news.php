<?php
/*
	single news
*/
?>
<div class="pagename_block">
	<div class="wrapper">
		<div class="block_title striped">
			Новости
		</div>
	</div>
	<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">			
</div>
<main>
	<div class="wrapper">
		<div class="page_content_block">
				<time class="post-date">
					<?php the_date('d.m.Y'); ?>
				</time>
				<h1 class="post-title">
					<?php the_title(); ?>
				</h1>
			   	<?php the_content(); ?>
		</div>
	</div>
</main>
