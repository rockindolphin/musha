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

    wp_enqueue_style('my_theme_style', get_template_directory_uri() . '/css/style.css', array(), '1.0.0');

}

add_action('wp_enqueue_scripts', 'my_theme_load_resources');







//add scripts

if (!is_admin()) {
    wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://code.jquery.com/jquery-2.2.4.min.js"), false, '2.2.4');
    wp_enqueue_script('jquery');
	wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js');
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

     $login = 'dteplitskiy@mushagroup.ru';
     $pass = 'wFdnddcxzDVS0acP2GocX13pHj5y';
     // $login="****";
     // $pass="***";

     $text = rand(1000, 9999);
	 $_SESSION['SMS_CODE'] = $text;

     $string = file_get_contents('https://gate.smsaero.ru/send/?user='.$login.'&password='.$pass.'&to='.$phone_number.'&text='.$text.'&from=Musha');

     //echo $phone_number;

     wp_die(); 
}
add_action( 'wp_ajax_get_sms_code', 'get_sms_code_callback' );
add_action( 'wp_ajax_nopriv_get_sms_code', 'get_sms_code_callback' );

?>