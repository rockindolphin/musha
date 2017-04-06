$(document).ready(function(){

	$('img[data-bg="image"]').each(function(){
		var src = $(this).attr('src');
		var parent = $(this).parent();
		$(parent).css('background-image', 'url('+src+')').addClass('bg_image');
		$(this).remove();
		var device = navigator.userAgent.toLowerCase();
		var mob = device.match(/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/);
		if (mob) {
		    $(parent).addClass("bg_image_normal");
		}	
	});

	function toggleSearch(){
		$('body').toggleClass('search_box-visible');
	}

	$('#search-btn').click(function(){
		toggleSearch();
	});

	$('.wpdreams_asl_container').append( '<div class="search-close" id="search-close-btn"></div>' );
	$('div[id*="ajaxsearchliteres"]').prepend( '<div class="search_rezults-title">Результаты поиска</div>' );

	$('#search-close-btn').click(function(){
		toggleSearch();
		$('div[id*="ajaxsearchliteres"]').hide();
	});

	$('#header-menu-toggle').removeAttr('checked');
	$('#header-menu-toggle').change(function(){
		$('header .mobile-menu').toggleClass('visible');
	});

});