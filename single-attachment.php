<?php
/*
Template Name: Single attachment template
*/
?>

<?php

	$attach = new stdClass();
	$attach->id = get_the_ID();
	$data  = wp_prepare_attachment_for_js( $attach->id );
	$attach->url = $data['url'];

	$galleries = $envira_gallery->get_galleries();
	$attach_in_gallery = false;
	foreach ($galleries as $gallery_id => $gallery) {
		foreach ($gallery['gallery'] as $post_id => $post) {
			if( $attach->id === $post_id ){
				$attach = getAttach($post_id,$post);
				$attach_in_gallery = true;										
			}
			if( $attach->url === $post['link'] ){
				$attach->thumbnail = $post['src'];
			}
		}
	}
	if( !$attach_in_gallery ){
		$attach->title = $data['title'];
		$attach->caption = $data['caption'];
		$attach->alt = $data['alt'];
		$attach->description = $data['description'];
		$attach->link = $data['url'];
		$attach->is_video = preg_match('/^.*\.(mp4|flv|ogv|webm)$/', $attach->link) === 1;
		$info = getAttachInfo( array( $attach->id ) );
		$attach->views = $info[$attach->id]->views;
		$attach->comments = $info[$attach->id]->comments;												
	}
	//var_dump( $attach );
	$title = $attach->title ? $attach->title : get_bloginfo('name');
	$description = $attach->caption ? $attach->caption : get_bloginfo('description');
	$keywords = $attach->alt ? $attach->alt : 'салон красоты Истра, семейный салон красоты, маникюр Истра, спа Истра, записаться онлайн';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<title><?php echo $title; ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="<?php echo $description; ?>">
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="yandex-verification" content="073582f85ad825b0" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i&amp;subset=cyrillic" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&amp;subset=cyrillic" rel="stylesheet">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header>
		<div class="wrapper">
			<a href="<?php echo get_home_url(); ?>" class="logo">
				<img src="<?php bloginfo('template_url'); ?>/images/musha_logo.svg" alt="musha logo">
			</a>
			<div class="menu-toggle">
				<input type="checkbox" id="header-menu-toggle">
				<label for="header-menu-toggle">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</label>
			</div>				
			<div class="mobile-menu">
				<?php 
					$walker = new mainMenuWalker ();
					wp_nav_menu(
						array(
							'container' => 'nav',
							'menu_class' => 'nav_menu',
							'depth'           => 2,
							'walker' => $walker,
							)
					);
				?>
				<div class="search" id="search-btn">
					<i class="fa fa-search" aria-hidden="true"></i>
				</div>			
				<div class="social">
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
				<table class="contacts">
					<tr>
						<td class="contacts-label">
							Тел.:  
						</td>
						<td class="contacts-item">
							<a href="<?php echo ( 'tel:' . preg_replace("/[^0-9,.]/", "", get_option('qs_contact_phone')) ); ?>">
								<?php echo (get_option('qs_contact_mob_phone')); ?>						
							</a>
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
							<a href="<?php echo ( 'tel:' . preg_replace("/[^0-9,.]/", "", get_option('qs_contact_phone')) ); ?>">
								<?php echo (get_option('qs_contact_phone')); ?>						
							</a>
						</td>
					</tr>
					<tr>
						<td class="contacts-label">
							E-mail: 
						</td>
						<td class="contacts-item">
							<a href="mailto:<?php echo (get_option('qs_contact_email')); ?>">
								<?php echo (get_option('qs_contact_email')); ?>						
							</a>
						</td>
					</tr>
				</table>
			</div>			
		</div>
		<div class="search_box">
			<div class="wrapper">
				<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
			</div>
		</div>
	</header>
	<!-- кнопка "Beauty клуб Муша"  -->
	<?php
		$opID = get_page_by_title( 'THEME_OPTIONS_PAGE' )->ID;
		$action_beauty_start_id = intval( get_field('action_beauty_start_id', $opID) );
	?>
	<a href="<?php the_permalink( $action_beauty_start_id ); ?>" class="button button-shadow" style="position: fixed;top: 140px;left: 15px;z-index:2;">
		<i>Beauty клуб <b>Муша</b></i>
	</a>

	<main>
		<div class="wrapper">
			<div class="page_content_block">
				<?php
					$js_info = array();
					$js_info['post_id'] = $attach->id; 
					$js_info['single-attachment'] = true; 
					require_once( 'custom-templates/publications.php' );
					$attach->container_class = 'publication-standalone';
					echo publicationWrapperHtml($attach);
					echo 	'<script>'.
								'var js_info = '.json_encode($js_info).';'.
							'</script>';					
					wp_enqueue_script('publications', get_template_directory_uri() . '/js/publications.js', false, '0.0.1');
					?>

			</div>
		</div>
	</main>

<?php get_footer(); ?>

