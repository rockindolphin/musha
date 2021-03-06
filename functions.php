<?php

/**

 * Musha theme functions and definitions

 */

function wpdocs_custom_excerpt_length( $length ) {
		return 20;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

function wpdocs_excerpt_more( $more ) {
		return '...';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

//add_theme_support( 'title-tag' );
add_theme_support( 'menus' );



/*remove standart widgets*/

function true_remove_default_widget() {

	unregister_widget('WP_Widget_Archives'); 

	unregister_widget('WP_Widget_Calendar'); 

	unregister_widget('WP_Widget_Categories'); 

	unregister_widget('WP_Widget_Meta'); 

	unregister_widget('WP_Widget_Pages'); 

	unregister_widget('WP_Widget_Recent_Comments'); 

	unregister_widget('WP_Widget_Recent_Posts'); 

	unregister_widget('WP_Widget_RSS'); 

	unregister_widget('WP_Widget_Search'); 

	unregister_widget('WP_Widget_Tag_Cloud'); 

}

add_action( 'widgets_init', 'true_remove_default_widget', 20 );



/*do not crop thumbnails images*/

function wplift_remove_image_sizes( $sizes) {

				unset( $sizes['thumbnail']);

				unset( $sizes['medium']);

				unset( $sizes['large']);

				return $sizes;

}

add_filter('intermediate_image_sizes_advanced', 'wplift_remove_image_sizes');



/*add post thumbnails support*/

add_theme_support( 'post-thumbnails' );



//add stylesheet

function my_theme_load_resources() {

		wp_enqueue_style('my_theme_style', get_template_directory_uri() . '/css/style.css', array(), '1.0.17');

}

add_action('wp_enqueue_scripts', 'my_theme_load_resources');







//add scripts

if (!is_admin()) {
		wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://code.jquery.com/jquery-2.2.4.min.js"), false, '2.2.4');
		wp_enqueue_script('jquery');
		wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper.min.js');
	wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', false, '0.0.6');
	wp_enqueue_script('script_cookie', ("https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"), false, '1.4.1');
}




//logo  в админке

function loginLogo() {

		echo '<style type="text/css">

				h1 a { 

						background-image:url('.get_bloginfo('template_directory').'/images/musha_logo.svg) !important;

						width: 238px !important;

						height: 105px !important;

						background-size: contain !important;

				}

		</style>';

}

add_action('login_head', 'loginLogo');



function page_link_by_slug($slug){

	$pages = get_pages();

	$href = '#';

	foreach ($pages as $page) {

		if($page -> post_name === $slug){

			$href = get_permalink($page -> ID);

		}

	}

	return $href;

}



function page_id_by_slug($slug){

	$pages = get_pages();

	$id = '#';

	foreach ($pages as $page) {

		if($page -> post_name === $slug){

			return $page -> ID;

		}

	}

}



function bookingServices(){

				global $wpdb;

				// $ret = array(0 => array('term' => false, 'services' => array()));

				$categories = array();

				$order = get_option(SLN_Plugin::CATEGORY_ORDER, '""');

				$sql = "SELECT * FROM {$wpdb->term_taxonomy} tt, {$wpdb->terms} t WHERE tt.term_id = t.term_id AND tt.taxonomy = '" . SLN_Plugin::TAXONOMY_SERVICE_CATEGORY . "' ORDER BY FIELD(t.term_id, $order)";

				$cat = $wpdb->get_results($sql);

				//echo $wpdb->term_taxonomy."/".$wpdb->terms."/". SLN_Plugin::TAXONOMY_SERVICE_CATEGORY."/".$order;



				foreach ($cat as $key => $value) {

			$categories[$key]['ID'] = $value -> term_taxonomy_id;

			$categories[$key]['NAME'] = $value -> name;

			$categories[$key]['SLUG'] = $value -> slug;

				}



				//echo "<pre>"; print_r($cat); echo "</pre>";



				$services = array();



				foreach ($categories as $key => $value) {

					$sql2 = "SELECT * FROM wp_term_relationships tt WHERE tt.term_taxonomy_id = ".$value['ID'];

					$answer = $wpdb->get_results($sql2);

					$services[$key]['CAT'] = $value['NAME'];

					$services[$key]['ID'] = $value['ID'];

					$services[$key]['SLUG'] = $value['SLUG'];

					$services[$key]['SRV'] = array();

		//echo "<pre>"; print_r($answer); echo "</pre>"; 



					foreach ($answer as $key2 => $value2) {

						$services[$key]['SRV'][$key2]['ID'] = $value2 -> object_id;

						

						$sql3 = "SELECT * FROM wp_posts pp WHERE pp.post_parent = ".$value2 -> object_id;

						$answer2 = $wpdb->get_results($sql3);

						$services[$key]['SRV'][$key2]['NAME'] = $answer2[0] -> post_title;
						$services[$key]['SRV'][$key2]['DESC'] = $answer2[0] -> post_excerpt;

			//echo "<pre>"; print_r($answer2); echo "</pre>"; 



						$sql4 = "SELECT * FROM wp_postmeta pp WHERE pp.post_id = ".$value2 -> object_id." AND pp.meta_key = '_sln_service_price'";

						$answer3 = $wpdb->get_results($sql4);

						$services[$key]['SRV'][$key2]['PRICE'] = $answer3[0] -> meta_value;

						//echo "<pre>"; print_r($answer2); echo "</pre>";        	

					}        	

				}





		//echo "<pre>"; print_r($services); echo "</pre>";

	



		return $services;

}



/*simple contact info shortcodes*/

$sci_defaults = array( 'twitter', 'facebook', 'youtube', 'google', 'linkedin', 'mob_phone', 'phone', 'fax', 'email', 'country', 'state', 'city', 'street', 'zip' );



function getSciSocialValue($attr = null){

	global $sci_defaults;

	if( $attr && $attr['name']){
		$value = in_array( $attr['name'], $sci_defaults ) ? get_option('qs_contact_'.$attr['name']) : get_option('qs_contact_custom_'.$attr['name']);  

		return $value;

	}

}



add_shortcode( 'sci_social_value', 'getSciSocialValue' );

add_filter('acf/settings/enqueue_select2', '__return_false');/*конфликтует с select2 в saloon booking */

$opID = get_page_by_title( 'THEME_OPTIONS_PAGE' )->ID;



function get_sms_code_callback(){
		if(!$_POST){
				wp_die();
		 }

		 $phone_number_temp = $_POST['phone_number'];

		 $temp = preg_replace('/[^0-9]/', '', $phone_number_temp);

		 if(strlen($temp) == 10){
			$phone_number = '7' . $temp;
		 }
		 else{
			$phone_number = preg_replace('#^.{1}#i', '7', $temp );
		 }

		 // $login = 'dteplitskiy@mushagroup.ru';
		 // $pass = 'wFdnddcxzDVS0acP2GocX13pHj5y';

		 $text = rand(1000, 9999);
	 $_SESSION['SMS_CODE'] = $text;

		 //$string = file_get_contents('https://gate.smsaero.ru/send/?user='.$login.'&password='.$pass.'&to='.$phone_number.'&text='.$text.'&from=Musha');

		 send_sms($phone_number, $text);

		 wp_die(); 
}
add_action( 'wp_ajax_get_sms_code', 'get_sms_code_callback' );
add_action( 'wp_ajax_nopriv_get_sms_code', 'get_sms_code_callback' );

function send_sms_text_callback(){
		if(!$_POST){
				wp_die();
		 }

		 $phone_number_temp = $_POST['phone_number'];
		 $text = $_POST['text'];

		 $temp = preg_replace('/[^0-9]/', '', $phone_number_temp);

		 if(strlen($temp) == 10){
				$phone_number = '7' . $temp;
		 }
		 else{
				$phone_number = preg_replace('#^.{1}#i', '7', $temp );
		 }

		 // $login = 'dteplitskiy@mushagroup.ru';
		 // $pass = 'wFdnddcxzDVS0acP2GocX13pHj5y';

		 //$string = file_get_contents('https://gate.smsaero.ru/send/?user='.$login.'&password='.$pass.'&to='.$phone_number.'&text='.$text.'&from=Musha');

		 send_sms($phone_number, $text);

		 wp_die(); 
}
add_action( 'wp_ajax_send_sms_text', 'send_sms_text_callback' );
add_action( 'wp_ajax_nopriv_send_sms_text', 'send_sms_text_callback' );


function send_sms($phone_number, $text){

		$phone_number_temp = $phone_number;
		$temp = preg_replace('/[^0-9]/', '', $phone_number_temp);
		if(strlen($temp) == 10){
			 $phone = '7' . $temp;
		}
		else{
			 $phone = preg_replace('#^.{1}#i', '7', $temp );
		}
		$login = 'dteplitskiy@mushagroup.ru';
		$pass = 'wFdnddcxzDVS0acP2GocX13pHj5y';

		$string = file_get_contents('https://gate.smsaero.ru/send/?user='.$login.'&password='.$pass.'&to='.$phone.'&text='.$text.'&from=Musha');

		//return $string;
}

/*walker menu*/
$serv_and_prices_ID = intval( get_field('serv_and_prices_ID', $opID) );
$actions_ID = intval( get_field('actions_ID', $opID) );

class mainMenuWalker extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth, $args) {
		global $serv_and_prices_ID;
		global $actions_ID;

		// назначаем классы li-элементу и выводим его
		$class_names = join( ' ', $item->classes );
		$class_names = ' class="' .esc_attr( $class_names ). '"';
		$output.= '<li id="menu-item-' . $item->ID . '"' .$class_names. '>';

		// назначаем атрибуты a-элементу
		if( $item->post_parent === $serv_and_prices_ID ){
				$attributes.= !empty( $item->url ) ? ' href="' .esc_attr($item->url). '#list"' : '';//добавляем якоря к подстарницам "Услуги и цены"
		}else{
				$attributes.= !empty( $item->url ) ? ' href="' .esc_attr($item->url). '"' : '';
		}
		$item_output = $args->before;

		// проверяем, на какой странице мы находимся
		$current_url = (is_ssl()?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$item_url = esc_attr( $item->url );
		//if ( $item_url != $current_url ) $item_output.= '<a'. $attributes .'>'.$item->title.'</a>';
		//else $item_output.= $item->title;
		if( intval($item->object_id) === $actions_ID ){ 
				$item_output.= '<a'. $attributes .'>'.$item->title.'<span class="menu-item-icon actions-icon">%</span></a>'; 
		}else{
				$item_output.= '<a'. $attributes .'>'.$item->title.'</a>';    
		}

		foreach ($item->classes as $key => $value) {
			if( $value === 'menu-item-has-children' ){
				$item_output.= '<span class="menu-open-btn"><i class="fa fa-angle-down" aria-hidden="true"></i></span>';
			}      
		}

		// заканчиваем вывод элемента
		$item_output.= $args->after;
		$output.= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}



function addMushaGallery($attr = null){
		global $envira_gallery;
		if( $attr && $attr['id']){
				$gallery = $envira_gallery->get_gallery( intval($attr['id']) ); 
				if( $gallery ){
						$resp = 
						'<div class="musha-gallery">'.
								'<div class="musha-gallery-bg">'.
										'<div class="wrapper">'.
												'<div class="swiper-container top-container">'.
														'<div class="swiper-wrapper">';           
																foreach ($gallery['gallery'] as $key => $value) {
																		$resp .=
																		'<div class="swiper-slide">'.
																				'<img src="'. $value['src']. '" alt="'. $value['alt']. '" data-fit="true">'.
																				'<meta itemprop="description" content="'. $value['title']. '">'.
																		'</div>';
																}
														$resp .=
														'</div>'.
														'<div class="swiper-button swiper-button-prev">'.
																'<i class="fa fa-angle-left" aria-hidden="true"></i>'.
														'</div>'.
														'<div class="swiper-button swiper-button-next">'.
																'<i class="fa fa-angle-right" aria-hidden="true"></i>'.
														'</div>'.
												'</div>'.
										'</div>'.            
								'</div>'.
								'<div class="wrapper">'.
										'<div class="gallery-desc">'.
												'<div class="slide-desc"></div>'.
												'<div class="swiper-pagination"></div>'.                    
										'</div>'.
										'<div class="swiper-container thumbs-container">'.
												'<div class="swiper-wrapper">';
														foreach ($gallery['gallery'] as $key => $value) {
																$resp .=
																		'<div class="swiper-slide">'.
																				'<img src="'. $value['src']. '" alt="'. $value['alt']. '" data-fit="true">'.
																		'</div>';
														}
														$resp.=
												'</div>'.
										'</div>'.                                       
								'</div>'.
						'</div>';          
				}
		}
		return $resp;
}

add_shortcode( 'musha_gallery', 'addMushaGallery' );


//auto formating
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );


/*Публикации*/
function get_file_id($file_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $file_url )); 
        return $attachment[0]; 
}

function addMushaPublications($attr = null){
	
		global $envira_gallery;
		global $opID;
		require_once( 'custom-templates/publications.php' );
		if( $attr && $attr['id']){
				$id = intval($attr['id']);
				$gallery = $envira_gallery->get_gallery( $id ); 
				if( $gallery && get_post_status($gallery['id']) === 'publish' ){
					$xids = array();
					foreach ($gallery['gallery'] as $post_id => $post) {
						array_push($xids, $post_id);
					}
					$js_info = array();								
					$info = getAttachInfo($xids);         
						$html = '<div class="musha-publications-header">';
						$html .=	'<div class="publication-header">';
						$count = 0;
								foreach ($gallery['gallery'] as $post_id => $post) {
									if( $count > 0 ){ continue; }
									$attach = getAttach($post_id,$post,$info);																		
						$html .= 	'<a href="'.get_attachment_link($attach->id).'">';
						$html .= 	publicationHtml($attach);
						$html .= 	'</a>';
									$js_info['posts'][$post_id] = $attach; 
									$count++;
								}
						$html .=	'</div>';
						$html .=	'<div class="publication-header-count">'.
										'<span>'.count($gallery['gallery']).'</span> Публикаций'.
									'</div>'.
								'</div>'.
								'<div class="musha-publications">'; 
								$count = 0;
									foreach ($gallery['gallery'] as $post_id => $post) {
										if( $count === 0 ){ $count++; continue; }
										$attach = getAttach($post_id,$post,$info);
						$html .= 	'<a href="'.get_attachment_link($attach->id).'">';																		
						$html .= 		publicationHtml($attach);
						$html .= 	'</a>';	
										$js_info['posts'][$post_id] = $attach;  									
										$count++;
									}
						$html .='</div>';
						$attach->container_class = 'publication-standalone';
						$html .= publicationModalHtml($attach);         
				}
		}
		$js_info['hc_wid'] = intval(get_option('hc_wid'));
		$js_info['comments_open'] = comments_open( intval(get_field('publications_id',$opID)) );
		$html .= '<script>'.
					'var js_info = '.json_encode($js_info).';'.
				 '</script>';
		wp_enqueue_script('publications', get_template_directory_uri() . '/js/publications.js', false, '0.0.9');		
		return $html;
		
}

add_shortcode( 'musha_publications', 'addMushaPublications' );


function getAttach($post_id,$post,$info = null){
	$attach = new stdClass();
	$attach->id = $post_id;
	$attach->title = $post['title'];
	$attach->caption = $post['caption'];
	$attach->alt = $post['alt'];
	$attach->description = '';
	$attach->url = $post['src'];
	$attach->link = $post['link'];
	$attach->is_video = preg_match('/^.*\.(mp4|flv|ogv|webm)$/', $attach->link) === 1;
	if( $attach->is_video ){
		$attach->thumbnail = $attach->url;
		$video_id = get_file_id( $attach->link );
		$video_info = wp_prepare_attachment_for_js( $video_id );
		$attach->date = get_the_date( 'Y-m-d', $video_id );
		$attach->duration = $video_info['fileLength'];
	}
	if( $info ){
		foreach ($info as $key => $value) {
			if( $key === $attach->id ){
				$attach->views = $value->views;
				$attach->comments = $value->comments;				
			}
		}
	}else{
		$info = getAttachInfo( array( $attach->id ) );
		$attach->views = $info[$attach->id]->views;
		$attach->comments = $info[$attach->id]->comments;		
	}	
	return $attach;	
}


function getAttachInfo($xids){
	global $opID;

	$resp = array();
	foreach ($xids as $key => $attach_id) {
		$data = wp_get_attachment_metadata( $attach_id );
		if( !isset($data['post_views'])  ){
			$data['post_views'] = 0;
			wp_update_attachment_metadata( $attach_id, $data );
		}	
		$info = new stdClass();
		$info->comments = 0;
		$info->views = $data['post_views'];
		$resp[$attach_id] = $info;
	}

	if( comments_open(intval(get_field('publications_id',$opID))) ){
		$xidsToStr = array();
		foreach ($xids as $key => $value) {
			array_push($xidsToStr, strval($value));
		}
		$query = array(
			'widget_id' => intval(get_option('hc_wid')),
			'xids' => $xidsToStr
		);
		$query = json_encode($query);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://c1api.hypercomments.com/api/get_count');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'data='.$query);
		$hc = curl_exec($curl);
		curl_close($curl);
		$hc = json_decode($hc);
		
		foreach ($xids as $key => $attach_id) {
			$comments = 0;
			foreach ($hc->data as $key => $value) {
				if( $value->xid  === strval($attach_id)){
					$comments = $value->cm2; 
				}
			}
			$resp[$attach_id]->comments = $comments;
		}	
	}


	return	$resp;

}


function incAttachViews( $id ){
	$data = wp_get_attachment_metadata( $id );
	$data['post_views']++;
	wp_update_attachment_metadata( $id, $data );
}



add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );
function myajax_data(){

	wp_localize_script('script', 'myajax', 
		array(
			'url' => admin_url('admin-ajax.php')
		)
	);  

}


add_action('wp_ajax_incAttachViews', 'incAttachViewsAjax');
add_action('wp_ajax_nopriv_incAttachViews', 'incAttachViewsAjax');

function incAttachViewsAjax() {
	$id = intval( $_POST['attach_id'] );
	incAttachViews( $id );
	wp_die();
}

?>