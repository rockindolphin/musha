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




	//публикации
	$('.publications-slider').each(function(){
		var publications = new Swiper(this, {
			direction: 'horizontal',
			loop: true,
			speed: 1000,
			autoplay: 1000,
			slidesPerView: 'auto',
			slideToClickedSlide: false,
			/*custom*/
			containerHover: false,
			containerMouseDown: false,
			moveDirection: 'freeze',
			moveBoost: 1,
			moveSpeed: 800,
			/******/			
			onInit: function(swiper){
				swiper.startAutoplay();
				$(swiper.container).on('mouseenter', function(e){
					swiper.params.containerHover = true;
					swiper.stopAutoplay();
				});
				$(swiper.container).on('mouseleave', function(e){
					swiper.params.containerHover = false;
					swiper.startAutoplay();
				});
				$(swiper.container).on('mousedown', function(e){
					swiper.params.containerMouseDown = true;
				});	
				$(swiper.container).on('mouseup', function(e){
					swiper.params.containerMouseDown = false;
				});								

				var box = $(swiper.container).offset().left + $(swiper.container).outerWidth();
				$(swiper.container).on('mousemove',function(e){
					if( !swiper.params.containerMouseDown ){
						pos_x_percent = (e.pageX*100)/box;
						var boost = swiper.params.moveBoost;
						if(pos_x_percent > 70){
							swiper.params.moveDirection = 'right';
							boost = 1 - (pos_x_percent-70)/30;
							swiper.params.moveBoost = boost < 0.4 ? 0.4 : boost; 
							swiper.slideNext(true, swiper.params.moveSpeed*boost);
						}else if(pos_x_percent < 30){
							swiper.params.moveDirection = 'left'; 
							boost = 1 - Math.abs(pos_x_percent-30)/30;
							swiper.params.moveBoost = boost < 0.4 ? 0.4 : boost; 
							swiper.slidePrev(true, swiper.params.moveSpeed*boost);
						}else{
							swiper.params.moveDirection = 'freeze';
						}	    
					}
				});			
			},
			onSlideChangeEnd: function(swiper){
				if(swiper.params.containerHover && !swiper.params.containerMouseDown){
					if( swiper.params.moveDirection === 'right' ){
						swiper.slideNext(true, swiper.params.moveSpeed*swiper.params.moveBoost);
					}else if(swiper.params.moveDirection === 'left'){
						swiper.slidePrev(true, swiper.params.moveSpeed*swiper.params.moveBoost);
					}
				}
			},
		});
	});



	$(function() {
		if(!$.cookie('hideModal')){
			$('.beutybutton').css('display', 'block');
			$('.fa-close-beutybutton').css('display', 'block');		            
		}
	});

	$('.fa-close-beutybutton').click(function(){
		$('.beutybutton').fadeOut('fast');
		$(this).fadeOut('fast');
		if(!$.cookie('hideModal')){
			$.cookie('hideModal', true, {
				expires: 7,
				path: '/'
			});
		}				
	});



});

