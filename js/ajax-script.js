jQuery(function($){
 
	/*
	 * Load More
	 */
	$('#misha_loadmore').click(function(){
 
		$.ajax({
			url : misha_loadmore_params.ajaxurl, // AJAX handler
			data : {
				'action': 'loadmorebutton', // the parameter for admin-ajax.php
				'query': misha_loadmore_params.posts, // loop parameters passed by wp_localize_script()
				'page' : misha_loadmore_params.current_page // current page
			},
			type : 'POST',
			beforeSend : function ( xhr ) {
				$('#misha_loadmore').text('Loading...'); // some type of preloader
			},
			success : function( posts ){
				if( posts ) {

					var $grid = jQuery('#misha_posts_wrap');
					//var $remove =  jQuery('#misha_posts_wrap').html();
					var $moreContent = $(posts)
					$grid.append( $moreContent ).isotope( 'appended', $moreContent )
					// Force grid to relayout
					$grid.isotope('layout');

					$('#misha_loadmore').text( 'Load More' );

					misha_loadmore_params.current_page++;
 
					if ( misha_loadmore_params.current_page == misha_loadmore_params.max_page ) 
						$('#misha_loadmore').hide(); // if last page, HIDE the button
 
				} else {
					$('#misha_loadmore').hide(); // if no data, HIDE the button as well
				}
			}
		});
		return false;
	});
 
	/*
	 * Filter
	 */
	$('#misha_filters').submit(function(){
		$.ajax({
			url : misha_loadmore_params.ajaxurl,
			data : $('#misha_filters').serialize(), // form data
			dataType : 'json', // this data type allows us to receive objects from the server
			type : 'POST',
			beforeSend : function(xhr){
				$('#misha_filters').find('#submitBtn').text('Filtering...');
			},
			success : function( data ){
 
				// when filter applied:
				// set the current page to 1
				misha_loadmore_params.current_page = 1;
 
				// set the new query parameters
				misha_loadmore_params.posts = data.posts;
 
				// set the new max page parameter
				misha_loadmore_params.max_page = data.max_page;
 
				// change the button label back
				$('#misha_filters').find('#submitBtn').text('Apply filter');
 
				// insert the posts to the container
				$('#misha_posts_wrap').empty();
				//var items = data.content;
				var $grid = jQuery('#misha_posts_wrap');
				//var $remove =  jQuery('#misha_posts_wrap').html();
				var elems = $grid.isotope('getItemElements')
				//$grid.isotope('layout');
				$grid.isotope( 'remove', elems )
				var $content = $(data.content)
				$grid.append( $content ).isotope( 'prepended', $content )
				// Force grid to relayout
				$grid.isotope('layout');
				// hide load more button, if there are not enough posts for the second page
				if ( data.max_page < 2 ) {
					$('#misha_loadmore').hide();
				} else {
					$('#misha_loadmore').show();
				}
			}
		});
 
		// do not submit the form
		return false;
 
	});
 
});