(function(){

	
	function loadModal( post_id ){
		var modal_post;
		opts.posts.map(function(post,index){
			if( post.id === post_id ){
				opts.curr = index;
				modal_post = post;				
			}
		});	

		var post_info = js_info['posts'][post_id];

		$(modal.views).text( post_info.views );
		$(modal.comments).text( post_info.comments );
		$(modal.title).html( post_info.title );
		$(modal.caption).html( post_info.caption );
		post_content_modal = $(modal_post.content).clone(false);
		$(post_content_modal).addClass('publication-standalone');
		$(modal.container).html('').append(post_content_modal);

		if( post_info.is_video ){
			publicationVideoHandler(post_content_modal);
		}

		if( js_info.comments_open ){
			var load_comments = setInterval(function(){
				if( HC ){
					setTimeout(function(){
						var _hcp = {};
						_hcp.widget_id = js_info['hc_wid'];
						_hcp.xid   = post_id; 
						HC.widget("Stream", _hcp);
					}, 100);
					clearInterval(load_comments);			
				}
			}, 100);	

			clearInterval(opts.upd_comments);
			opts.upd_comments = setInterval(function(){
				var hc_count = $('.hc__menu__count').text();
				if( parseInt(hc_count) !== js_info['posts'][post_id].comments ){
					$(modal.comments).text(hc_count);
					$(modal_post.comments).text(hc_count);
					js_info['posts'][post_id].comments = hc_count;
				}
			}, 500);			
		}

		$.post( myajax.url, {
			action: 'incAttachViews',
			attach_id: post_id
		});			

		$(modal.elem).show();		
		document.location.hash = '#'+post_id;
		$('body').addClass('body-scroll-off');		
	}

	function publicationVideoHandler(content){
		var video = $(content).find('video').get(0);
		var btn = 	$(content).find('.publication-controls>.fa');
		var thumbnail = $(content).find('.publication-thumbnail');
		video.onplay = function(){
			$(btn).removeClass('fa-play').addClass('fa-pause');				
			opts.runing_video = video;
			$(thumbnail).hide();
			$(content).addClass('video-running');
		};
		video.onpause = function(){
			$(btn).removeClass('fa-pause').addClass('fa-play');					
			opts.runing_video = false;
			$(content).removeClass('video-running');
		};
		$(btn).click(function(){
			if( video.paused ){
				video.play();
			}else{
				video.pause();
			}
		});		
	}

	function showPublication(direction){
		opts.curr+=direction;
		if( opts.curr >= opts.posts.length ){
			opts.curr = 0;
		}
		if( opts.curr < 0 ){
			opts.curr = opts.posts.length-1;
		}
		loadModal( opts.posts[opts.curr].id );
	}	





	HC = false;

	var opts = {
		posts: [],
		curr: 0,
		upd_comments: false,
		running_video: false,
	}

	var modal = {
		elem: $('#publication-modal'),
		views: $('#publication-modal .publication-views'),
		comments: $('#publication-modal .publication-comments'),
		title: $('#publication-modal .publication-title'),
		caption: $('#publication-modal .publication-caption'),
		close: $('#publication-modal .modal-close'),
		prev: $('#publication-modal .modal-prev'),
		next: $('#publication-modal .modal-next'),
		container: $('#publication-modal .publication-container'),
	}

	$('.publication-container:not(.publication-standalone)').each(function(){
		var post_id = parseInt($(this).attr('data-post-id'));
		opts.posts.push({
			content: $(this).find('.publication-content'), 
			id: post_id,
			comments: $(this).find('.publication-comments'),  
			views: $(this).find('.publication-views'),  
		});
		$(this).click(function(e){
			e.preventDefault();
			loadModal(post_id);
		});
	});


	function stopVideo(){
		if( opts.running_video ){ 
			opts.running_video.pause(); 
			opts.running_video = false; 
		}		
	}

	$(modal.close).click(function(){
		$(modal.elem).hide();
		clearInterval(opts.upd_comments_interval);
		document.location.hash = '';
		$('body').removeClass('body-scroll-off');
		stopVideo();
	});

	$(modal.prev).click(function(){
		stopVideo();
		showPublication(-1);
	});

	$(modal.next).click(function(){
		stopVideo();
		showPublication(1);
	});


	var hash = document.location.hash;
	var hash_id = parseInt(hash.substr(1,hash.length));
	
	if( !js_info['single-attachment'] ){
		if( js_info['posts'][hash_id] ){
			loadModal(hash_id);	
		}					
	}else{
		$('.publication-standalone .publication-video').each(function(){
			var content = $(this).closest('.publication-content');
			publicationVideoHandler( content );
		});
		$.post( myajax.url, {
			action: 'incAttachViews',
			attach_id: js_info.post_id
		});		
	}
	
	//console.log(js_info);


})();