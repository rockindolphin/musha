<?php
/**
 * The template for displaying the footer tile
 */
?>
	<div class="footer_tile square_block">	
		<img src="<?php bloginfo('template_url'); ?>/images/bg3.jpg" data-bg="image">
		<div class="wrapper">
			<div class="square_block-wrapper">
					<div class="square_block-expand"></div>
					<div class="square_block-content">
						<div class="block_title striped white">
							Ждём вас!
						</div>
						<a class="info_phone simple_text " href="<?php echo ( 'tel:' . preg_replace("/[^0-9,.]/", "", get_option('qs_contact_phone')) ); ?>">
							<?php echo (get_option('qs_contact_mob_phone')); ?>						
						</a>
						<a class="info_phone simple_text " href="<?php echo ( 'tel:' . preg_replace("/[^0-9,.]/", "", get_option('qs_contact_phone')) ); ?>">
							<?php echo (get_option('qs_contact_phone')); ?>						
						</a>
						<a class="info_email" href="mailto:<?php echo (get_option('qs_contact_email')); ?>">
							<?php echo (get_option('qs_contact_email')); ?>						
						</a>
						<div class="info_adress">
							<?php echo (get_option('qs_contact_street')); ?>
						</div>
						<a class="online_link" href="<?php echo page_link_by_slug('contacts'); ?>#map">Карта проезда</a>	
						<a class="online_link" href="<?php echo page_link_by_slug('booking'); ?>">онлайн-запись</a>	
						<div class="social inverted">
							<a href="<?php echo (get_option('qs_contact_custom_vkontakte')); ?>">
								<i class="fa fa-vk" aria-hidden="true"></i>
							</a>
							<a href="<?php echo (get_option('qs_contact_custom_facebook')); ?>">
								<i class="fa fa-facebook" aria-hidden="true"></i>
							</a>
							<a href="<?php echo (get_option('qs_contact_custom_instagram')); ?>">
								<i class="fa fa-instagram" aria-hidden="true"></i>
							</a>
						</div>
					</div>			
			</div>
		</div>
	</div>

