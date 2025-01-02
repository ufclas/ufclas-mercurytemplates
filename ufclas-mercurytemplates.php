<?php
/**
 * Plugin Name:       UFCLAS Additional Mercury Templates
 * Description:       Additional custom UFCLAS templates for use with base Mercury theme.
 * Version:           1.0.0
 * Text Domain:       https://github.com/ufclas/ufclas-mercurytemplates
 * Author:            Suzie Israel
 */

  
 // Register custom page templates
 function my_template_array() {
	 $temps = [];
	 $temps['custom-post-archive.php'] = 'Custom Post Archive';
	 return $temps;
 }
 
 function my_template_register($page_templates, $theme, $post) {
	 $templates = my_template_array();
	 foreach($templates as $tk => $tv) {
		 $page_templates[$tk] = $tv;
	 }
	 return $page_templates;
 }
 add_filter('theme_page_templates', 'my_template_register', 10, 3);
 
 // Load custom template
 function my_template_select($template) {
	 global $post;
	 $page_temp_slug = get_page_template_slug($post->ID);
	 $templates = my_template_array();
 
	 if (isset($templates[$page_temp_slug])) {
		 $template = plugin_dir_path(__FILE__) . 'templates/' . $page_temp_slug;
	 }
	 return $template;
 }
 add_filter('template_include', 'my_template_select');
 
 // Add template to page attributes dropdown
 function add_custom_template_to_pages($templates) {
	 $templates = array_merge($templates, my_template_array());
	 return $templates;
 }
 add_filter('theme_page_templates', 'add_custom_template_to_pages');

 

// Hook to register the custom archive template
add_filter( 'archive_template', 'my_custom_archive_template' );

function my_custom_archive_template( $archive_template ) {
    // Check if it's an archive page
    if ( is_post_type_archive() || is_category() || is_tag() || is_tax() ) {
        // Path to your custom template file
        $archive_template = plugin_dir_path( __FILE__ ) . 'templates/archive.php';
    }
    return $archive_template;
}

// Single News Filtering from Mercury functions.php, updated to handle tags and custom post cards


add_action( 'wp_enqueue_scripts', 'misha_script_and_styles_custom');

function misha_script_and_styles_custom() {
	// absolutely need it, because we will get $wp_query->query_vars and $wp_query->max_num_pages from it.
	global $wp_query;
	// when you use wp_localize_script(), do not enqueue the target script immediately
	wp_register_script( 'misha_scripts', get_template_directory_uri() . '/js/ajax-script.js', array('jquery') );
	// passing parameters here
	// actually the <script> tag will be created and the object "misha_loadmore_params" will be inside it 
	wp_localize_script( 'misha_scripts', 'misha_loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => $wp_query->query_vars['paged'] ? $wp_query->query_vars['paged'] : 1,
		'max_page' => $wp_query->max_num_pages
	) );
 	wp_enqueue_script( 'misha_scripts' );
}

add_action('wp_ajax_loadmorebutton', 'misha_loadmore_ajax_handler_custom');
add_action('wp_ajax_nopriv_loadmorebutton', 'misha_loadmore_ajax_handler_custom');
 
function misha_loadmore_ajax_handler_custom(){
	// prepare our arguments for the query
	$params = json_decode( stripslashes( $_POST['query'] ), true ); // query_posts() takes care of the necessary sanitization 
	$params['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$params['post_status'] = 'publish';
 
	// it is always better to use WP_Query but not here
	query_posts( $params );
 
	if( have_posts() ) :
 
		// run the loop
		while( have_posts() ): the_post();
 
			$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$post_date = get_the_date('l F j, Y');
			$filter_date = get_the_date('Y');
			$categories = get_the_category();
			
			echo '<div class="col-sm-6 col-lg-4 mb-4 news-col ';
			if (!empty($categories)) {
				echo 'year-' . $filter_date;
			}
			echo '">';
			echo '<div class="card news-card">';
			echo '<a href="' . get_permalink() . '" class="news-card-link" alt="..">';
			if ($featured_img_url) {
				echo '<img src="' . esc_url($featured_img_url) . '" class="card-img-top" alt="...">';
			}
			echo '<div class="card-body">';
			echo '<h3 class="card-title">' . get_the_title() . '</h3>';            
			echo '<p class="card-date">' . $post_date . ' Hello world</p>';
			echo '<p class="card-text">' . get_the_excerpt() . '</p>';
			echo '</div>';  // .card-body
			echo '</a>';
			echo '</div>';  // .card news-card
			echo '</div>';  // .col

		endwhile;
	endif;
	die; // here we exit the script and even no wp_reset_query()
}
add_action('wp_ajax_mishafilter', 'misha_filter_function_custom'); 
add_action('wp_ajax_nopriv_mishafilter', 'misha_filter_function_custom');
 
function misha_filter_function_custom(){
	if(isset($_POST['datefilter']) && $_POST['datefilter'] != '') {
		$datefilter = $_POST['datefilter'];
	}
	if(isset($_POST['categoryfilter']) && $_POST['categoryfilter'] != '') {
		$catfilter = $_POST['categoryfilter'];
	}

	if( $datefilter && $catfilter ) {
		// if categoryfilter is set and not empty
		$params = array(
			'posts_per_page' => 15,
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $_POST['categoryfilter']
				)
			), 
			'date_query' => array(
				array(
					'year' => $_POST['datefilter']
				)
			)
		);
	  } 

	  if( $datefilter && !$catfilter ) {
		// if categoryfilter is set and not empty
		$params = array(
			'posts_per_page' => 15,
			'date_query' => array(
				array(
					'year' => $_POST['datefilter']
				)
			)
		);
	  } 
	  
	  if( !$datefilter && $catfilter ) {
		// if categoryfilter is set and not empty
		$params = array(
			'posts_per_page' => 15,
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $_POST['categoryfilter']
				)
			), 
		);
	  } 
	  if( !$datefilter && !$catfilter ) {
		// if categoryfilter is set and not empty
		// if both are not set or empty
		$params = array(
			'posts_per_page' => 15,
		);
	  } 
	query_posts( $params );
 
	global $wp_query;
 
	if( have_posts() ) :
 
 		ob_start(); // start buffering because we do not need to print the posts now
 
		while( have_posts() ): the_post();
		
			$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$post_date = get_the_date('l F j, Y');
			$filter_date = get_the_date('Y');
			$categories = get_the_category();
			
			echo '<div class="col-sm-6 col-lg-4 mb-4 news-col ';
			if (!empty($categories)) {
				echo 'year-' . $filter_date;
			}
			echo '">';
			echo '<div class="card news-card">';
			echo '<a href="' . get_permalink() . '" class="news-card-link" alt="..">';
			if ($featured_img_url) {
				echo '<img src="' . esc_url($featured_img_url) . '" class="card-img-top" alt="...">';
			}
			echo '<div class="card-body">';
			echo '<h3 class="card-title">' . get_the_title() . '</h3>';            
			echo '<p class="card-date">' . $post_date . ' Hello world</p>';
			echo '<p class="card-text">' . get_the_excerpt() . '</p>';
			echo '</div>';  // .card-body
			echo '</a>';
			echo '</div>';  // .card news-card
			echo '</div>';  // .col
	
		endwhile;
 
 		$posts_html = ob_get_contents(); // we pass the posts to variable
   		ob_end_clean(); // clear the buffer
	else:
		$posts_html = '<p>Nothing found for your criteria.</p>';
	endif;
	// no wp_reset_query() required
 	echo json_encode( array(
		'posts' => json_encode( $wp_query->query_vars ),
		'max_page' => $wp_query->max_num_pages,
		'found_posts' => $wp_query->found_posts,
		'content' => $posts_html
	) );
 
	die();
}

function prefix_category_title( $title ) {
	if ( is_category() ) {
	$title = single_cat_title( '', false );
	}
	return $title;
	}
	add_filter( 'get_the_archive_title', 'prefix_category_title' );
  ?>