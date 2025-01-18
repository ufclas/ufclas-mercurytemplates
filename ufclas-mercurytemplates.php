<?php
/**
 * Plugin Name:       UFCLAS Additional Mercury Templates
 * Description:       Additional custom UFCLAS templates for use with base Mercury theme.
 * Version:           1.0.0
 * Text Domain:       https://github.com/ufclas/ufclas-mercurytemplates
 * Author:            Suzie Israel
 */

  


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

// Single News Filtering from Mercury functions.php, updated to handle custom post cards

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
	
			include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/ufclas-mercurytemplates/template-parts/content-post.php");

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
	
			include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/ufclas-mercurytemplates/template-parts/content-post.php");
	
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



//remove "Category" from before the category Title in archives

function prefix_category_title( $title ) {
	if ( is_category() ) {
	$title = single_cat_title( '', false );
	}
	return $title;
	}
	add_filter( 'get_the_archive_title', 'prefix_category_title' );




// register "Category" meta box for Custom Post archive
function custom_post_archive_add_meta_box()
{
    $page_template = 'custom-post-archive.php';
    $current_page_template = get_page_template_slug(get_the_ID());

    if ($current_page_template === $page_template) {
        add_meta_box(
            'custom_archive_meta_box',         // Unique ID of meta box
            'Select the post category to display on this page',      // Title of meta box
            'custom_archive_display_meta_box', // Callback function
            ['page', 'post'],                          // Post type
			'side', 'low'					//position on page
        );
    }
}

add_action('add_meta_boxes', 'custom_post_archive_add_meta_box');


// display meta box in admin
function custom_archive_display_meta_box($post)
{

	$value = get_post_meta($post->ID, 'custom_archive_meta_key', true);

	wp_nonce_field(basename(__FILE__), 'custom_archive_meta_box_nonce');





	echo "<div class='custom_archive_meta_box' style='display: flex; flex-wrap: wrap; border: 1px #ccc solid;border-radius: 6px;'>";

	$selected_category = get_post_meta($post->ID, 'selected-category', true);
	$categories = get_categories([
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => false,
		'parent' => 0, // Only include top-level categories
	]);
	
	echo '<div style="margin: 20px 10px; width: 45%;">';
	echo '<label for="selected-category">Category&nbsp;&nbsp;</label>';
	echo '<select name="selected-category">';
	foreach ($categories as $category) {
		echo '<option value="' . esc_attr($category->slug) . '"' . selected($selected_category, $category->slug, false) . '>' . esc_html($category->name) . '</option>';
	}
	echo '</select>';
	echo '</div>';
	
	echo "</div>";

}



// save meta box
function thisplugin_save_meta_box($post_id)
{

	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);

	$is_valid_nonce = false;

	if (isset($_POST['custom_archive_meta_box_nonce'])) {

		if (wp_verify_nonce($_POST['custom_archive_meta_box_nonce'], basename(__FILE__))) {

			$is_valid_nonce = true;
		}
	}

	if ($is_autosave || $is_revision || !$is_valid_nonce) return;

	$member_meta['selected-category'] = esc_textarea($_POST['selected-category']);

	if (is_array($member_meta)) {
		foreach ($member_meta as $key => $value) :
			// Don't store custom data twice
			if ('revision' === $post->post_type) {
				return;
			}
			if (get_post_meta($post_id, $key, false)) {
				// If the custom field already has a value, update it.
				update_post_meta($post_id, $key, $value);
			} else {
				// If the custom field doesn't have a value, add it.
				add_post_meta($post_id, $key, $value);
			}
			if (!$value) {
				// Delete the meta key if there's no value
				delete_post_meta($post_id, $key);
			}
		endforeach;
	}
}
add_action('save_post', 'thisplugin_save_meta_box');




  ?>