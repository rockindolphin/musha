<?php

function publicationHtml($attach){
	global $opID;
	$schema = '';
	$schema = $attach->is_video ? 'VideoObject"' : 'ImageObject"';	
	$html = '';
	$html.= '<div class="publication-container '.$attach->container_class.'" data-post-id="'.$attach->id.'" itemscope itemtype="http://schema.org/'.$schema.'">';
	$html .=	'<div class="publication-content">';
				if( $attach->is_video ){
	$html .=		'<video class="publication-video">'.
						'<source src="'.$attach->link.'" itemprop="url"></source>'.
					'</video>'.
					'<meta itemprop="isFamilyFriendly" content="true">';
					if( $attach->thumbnail ){
	$html .=		'<img src="'.$attach->thumbnail.'" alt="'.$attach->alt.'" class="publication-pic publication-thumbnail" itemprop="thumbnail">'.
					'<div class="publication-pic-fallback publication-thumbnail" style="background-image: url('.$attach->thumbnail.')"></div>'.
					'<div class="publication-icon">'.
						'<i class="fa fa-video-camera" aria-hidden="true"></i>'.
					'</div>'.					
					'<div class="publication-controls">'.
						'<i class="fa fa-play" aria-hidden="true"></i>'.
					'</div>';					
					}
				}else{
	$html .=		'<img src="'.$attach->url.'" alt="'.$attach->alt.'" class="publication-pic" itemprop="contentUrl">'.
					'<div class="publication-pic-fallback" style="background-image: url('.$attach->url.')"></div>';									
				}
					
	$html .=		'<div class="publication-info">'.
						'<ul>'.
							'<li>'.
								'<i class="fa fa-eye" aria-hidden="true"></i>'.
								'<span class="publication-views">'.$attach->views.'</span>'.
							'</li>';
							if( comments_open( intval(get_field('publications_id',$opID)) ) ){
	$html .=				'<li>'.
								'<i class="fa fa-comment" aria-hidden="true"></i>'.
								'<span class="publication-comments">'.$attach->comments.'</span>'.										
							'</li>';
							}							
	$html .=			'</ul>'.
					'</div>'.
					'<meta itemprop="description" content="'.$attach->caption.'">'.
					'<meta itemprop="name" content="'.$attach->title.'">';
					if( $attach->is_video ){
	$html .=		'<meta itemprop="uploadDate" content="'.$attach->date.'">'.									
					'<meta itemprop="duration" content="'.$attach->duration.'">';															
					}									
	$html .=	'</div>'.
			'</div>';
	return $html;
}

function publicationWrapperHtml($attach){
	global $opID;
	$html = '';
	$html.= '<div class="publication-wrapper">';
	$html.= publicationHtml($attach);
	$html.=	'<div class="publication-sidebar-wrapper">'.
				'<div class="publication-sidebar">'.
					'<div class="publication-info">'.
						'<ul>'.
							'<li>'.
								'<i class="fa fa-eye" aria-hidden="true"></i>'.
								'<span class="publication-views">'.$attach->views.'</span>'.
							'</li>';
							if( comments_open( intval(get_field('publications_id',$opID)) ) ){
	$html.=					'<li>'.
								'<i class="fa fa-comment" aria-hidden="true"></i>'.
								'<span class="publication-comments">'.$attach->comments.'</span>'.											
							'</li>';
							}
	$html.=				'</ul>'.
					'</div>'.
					'<div class="publication-desc-wrapper">'.
						'<div class="publication-desc">'.
							'<div class="publication-title">'.$attach->title.'</div>'.
							'<div class="publication-caption">'.$attach->caption.'</div>'.
							'<div class="publication-comments-widget">'.
								'<div id="hypercomments_widget"></div>'.
							'</div>'.
						'</div>'.
					'</div>'.
				'</div>'.
			'</div>'.
		'</div>';
	return $html;		
}


function publicationModalHtml($attach){
	$html = '';
	$html .= '<div class="musha-modal" id="publication-modal">'.
				'<div class="modal-bg">'.
					'<div class="modal-wrapper">'.
						'<div class="modal-close">'.
							'<i class="fa fa-times" aria-hidden="true"></i>'.
						'</div>'.
						'<div class="modal-btn modal-prev">'.
							'<i class="fa fa-angle-left" aria-hidden="true"></i>'.
						'</div>';
	$html .= publicationWrapperHtml($attach);
	$html .=			'<div class="modal-btn modal-next">'.
							'<i class="fa fa-angle-right" aria-hidden="true"></i>'.
						'</div>'.
					'</div>'.
				'</div>'.
			'</div>';
	return $html;
}


?>