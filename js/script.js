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

	$('.menu-open-btn').click(function(){
		$(this).next().toggleClass('sub-menu-visible');
	});

	//замена картинок
	$('img[data-fit="true"]').each(function(){
		var src = $(this).attr('src');
		var replacer = document.createElement('div');
		$(replacer).css('background-image', 'url('+src+')').addClass('image_fit_replacer');
		$(this).after(replacer);
		$(this).hide();
	}); 

	//галерея
	$('.musha-gallery').each(function(){
		var top = $(this).find('.top-container')[0];
		var thumbs = $(this).find('.thumbs-container')[0];
		var pagination = $(this).find('.swiper-pagination')[0];
		var galleryTop = new Swiper(top, {
			direction: 'horizontal',
			speed: 400,
		    slideToClickedSlide: false,
		    pagination: pagination,
		    paginationType: 'fraction',
	        nextButton: '.swiper-button-next',
	        prevButton: '.swiper-button-prev',
	        slideDesc: $(this).find('.slide-desc')[0],
	        onInit: function(swiper){
	        	$.makeArray(swiper.slides).map(function(slide){
	        		slide.description = $(slide).find('meta[itemprop=description]').attr('content');
	        	});
	        	$(swiper.params.slideDesc).html( swiper.slides[swiper.activeIndex].description );
	        },
	        onSlideChangeEnd: function(swiper){
	        	$(swiper.params.slideDesc).html( swiper.slides[swiper.activeIndex].description );
	        }		    
		});
		var galleryThumbs = new Swiper(thumbs, {
			direction: 'horizontal',
			speed: 400,
			slidesPerView: 5,
			slideToClickedSlide: true,
			centeredSlides: true,
		});	
	    galleryTop.params.control = galleryThumbs;
	    galleryThumbs.params.control = galleryTop;			  
	});



});