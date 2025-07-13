<?php
/**
 * Plugin Name:       UFCLAS Additional Mercury Templates and Styles
 * Description:       Additional custom UFCLAS templates and styles for use with base Mercury theme.
 * Version:           1.0.0
 * Text Domain:       https://github.com/ufclas/ufclas-mercurytemplates
 * Author:            Suzie Israel
 */




// Enqueue plugin styles and add scss compilation function

function enqueue_plugin_styles() {
    $style_url = plugins_url( 'css/style.css', __FILE__ );
    wp_enqueue_style( 'plugin-style', $style_url );
}
add_action('wp_enqueue_scripts', 'enqueue_plugin_styles'); // Higher priority




//Register Custom Page Templates

function my_page_template_array() {
    return ['custom-post-archive.php' => 'Custom Post Archive'];
}

//Register Templates for Pages

function my_page_template_register($page_templates, $theme, $post) {
    $templates = my_page_template_array();
    foreach($templates as $tk => $tv) {
        $page_templates[$tk] = $tv;
    }
    return $page_templates;
}
add_filter('theme_page_templates', 'my_page_template_register', 10, 3);

//Load Custom Template for Pages

	function my_page_template_select($template) {
		global $post;
		if (is_page()) {
			$page_temp_slug = get_page_template_slug($post->ID);
			$templates = my_page_template_array();
	
			if (isset($templates[$page_temp_slug])) {
				$template = plugin_dir_path(__FILE__) . 'templates/' . $page_temp_slug;
			}
		}
		return $template;
	}
	add_filter('template_include', 'my_page_template_select');

	
//Add Custom Templates to Page Attributes Dropdown

function add_custom_template_to_pages($templates) {
    $templates = array_merge($templates, my_page_template_array());
    return $templates;
}
add_filter('theme_page_templates', 'add_custom_template_to_pages');


// Register Custom Post Templates
function my_post_template_array() {
    return [
        'custom-post-contained.php' => 'No Sidebar Inc. Breadcrumbs',
        'custom-post-fullwidth.php' => 'Default Inc. Breadcrumbs'
    ];
}

// Register Templates for Posts
function my_post_template_register($post_templates, $theme, $post) {
    $templates = my_post_template_array();
    foreach($templates as $tk => $tv) {
        $post_templates[$tk] = $tv;
    }
    return $post_templates;
}
add_filter('theme_post_templates', 'my_post_template_register', 10, 3);

// Load Custom Template for Posts
function my_post_template_select($template) {
    global $post;
    if (is_single() && $post->post_type == 'post') {
        $post_temp_slug = get_post_meta($post->ID, '_wp_page_template', true);
        $templates = my_post_template_array();

        if (isset($templates[$post_temp_slug])) {
            $template = plugin_dir_path(__FILE__) . 'templates/' . $post_temp_slug;
        }
    }
    return $template;
}
add_filter('template_include', 'my_post_template_select');

// Add Custom Templates to Post Attributes Dropdown
function add_custom_template_to_posts($templates) {
    $templates = array_merge($templates, my_post_template_array());
    return $templates;
}
add_filter('theme_post_templates', 'add_custom_template_to_posts');

// Hook to register the custom archive template
add_filter('archive_template', 'my_custom_archive_template');

function my_custom_archive_template($archive_template) {
    // Check if it's a category archive page
    if (is_category()) {
        // Path to your custom template file
        $archive_template = plugin_dir_path(__FILE__) . 'templates/archive.php';
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
				'posts_per_page' => 100,
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
				'posts_per_page' => 100,
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
				'posts_per_page' => 1005,
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
				'posts_per_page' => 100,
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
            'page',                          // Post type
            'side', 'low'                    //position on page
        );
    }
}

add_action('add_meta_boxes', 'custom_post_archive_add_meta_box');


// display meta box in admin
function custom_archive_display_meta_box($post)
{
    $value = get_post_meta($post->ID, 'custom_archive_meta_key', true);

    wp_nonce_field(basename(__FILE__), 'custom_archive_meta_box_nonce');

    echo "<div class='custom_archive_meta_box'>";

    $selected_category = get_post_meta($post->ID, 'selected-category', true);
    $categories = get_categories([
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
    ]);

    echo '<select name="selected-category">';
    foreach ($categories as $category) {
        echo '<option value="' . esc_attr($category->slug) . '"' . selected($selected_category, $category->slug, false) . '>' . esc_html($category->name) . '</option>';
        $subcategories = get_categories([
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'parent' => $category->term_id,
        ]);
        foreach ($subcategories as $subcategory) {
            echo '<option value="' . esc_attr($subcategory->slug) . '"' . selected($selected_category, $subcategory->slug, false) . '>&nbsp;&nbsp;&nbsp;' . esc_html($subcategory->name) . '</option>';
        }
    }
    echo '</select>';

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

//========> Custom Meta Box for hiding date and other elements
add_action('add_meta_boxes', 'elements_metaBox');
function elements_metaBox($post) {
    // Check if the post uses the 'custom-post-contained.php' or 'custom-post-fullwidth.php' template
    $template = get_page_template_slug($post->ID);
    if ($template == 'custom-post-contained.php' || $template == 'custom-post-fullwidth.php') {
        add_meta_box('date_id', 'Hide Elements', 'crt_metaBox_elements', 'post', 'side', 'low');
    }
}

function crt_metaBox_elements($post){
    $hide_date = get_post_meta($post->ID, 'hide_date', true);
    $hide_socials = get_post_meta($post->ID, 'hide_socials', true);
    $hide_author = get_post_meta($post->ID, 'hide_author', true);
    $hide_featured_image = get_post_meta($post->ID, 'hide_featured_image', true);
?>
    <p class="ufl_checkbox">
        <span>Hide the date</span>
        <input type="checkbox" name="hide_date" id="hide_date" value="1" <?php echo ($hide_date == 1) ? 'checked="checked"' : ''; ?> />
    </p>
    <p class="ufl_checkbox">
        <span>Hide socials</span>
        <input type="checkbox" name="hide_socials" id="hide_socials" value="1" <?php echo ($hide_socials == 1) ? 'checked="checked"' : ''; ?> />
    </p>
    <p class="ufl_checkbox">
        <span>Hide author</span>
        <input type="checkbox" name="hide_author" id="hide_author" value="1" <?php echo ($hide_author == 1) ? 'checked="checked"' : ''; ?> />
    </p>
    <p class="ufl_checkbox">
        <span>Hide featured image</span>
        <input type="checkbox" name="hide_featured_image" id="hide_featured_image" value="1" <?php echo ($hide_featured_image == 1) ? 'checked="checked"' : ''; ?> />
    </p>
<?php
}

add_action('save_post', 'save_elements_metaBox');
function save_elements_metaBox($post_id){
    // Verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        return $post_id;

    // Check permissions
    if (!current_user_can('edit_post', $post_id))
        return $post_id;

    // Sanitize user input.
    $hide_date = isset($_POST['hide_date']) ? 1 : 0;
    $hide_socials = isset($_POST['hide_socials']) ? 1 : 0;
    $hide_author = isset($_POST['hide_author']) ? 1 : 0;
    $hide_featured_image = isset($_POST['hide_featured_image']) ? 1 : 0;

    // Update the meta fields in the database.
    update_post_meta($post_id, 'hide_date', $hide_date);
    update_post_meta($post_id, 'hide_socials', $hide_socials);
    update_post_meta($post_id, 'hide_author', $hide_author);
    update_post_meta($post_id, 'hide_featured_image', $hide_featured_image);
}


//shortcode to dynamically update year in the footer
function year_shortcode () {
	$year = date_i18n ('Y');
	return $year;
}
add_shortcode ('year', 'year_shortcode');


//Disable core blocks.

add_action('init', function() {
	remove_theme_support('core-block-patterns');
});

//Make it so regular Adminstrators don't wipe out iframes when editing pages

function km_add_unfiltered_html_capability_to_editors( $caps, $cap, $user_id ) {

	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = [ 'unfiltered_html' ];
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'km_add_unfiltered_html_capability_to_editors', 1, 3 );

//Add Google Analytics and Google Tag Manager to the Customizer

function ufclas_mercury_customizer_settings($wp_customize) {
    // Add Custom Section
    $wp_customize->add_section('analytics_settings_section', array(
        'title'    => __('Analytics Settings', 'textdomain'),
        'priority' => 30,
    ));

    // Google Analytics Setting
    $wp_customize->add_setting('google_analytics', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    // Google Tag Manager Setting
    $wp_customize->add_setting('google_tag_manager', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    // Google Analytics Control
    $wp_customize->add_control('google_analytics_control', array(
        'label'    => __('Google Analytics', 'textdomain'),
        'section'  => 'analytics_settings_section',
        'settings' => 'google_analytics',
        'type'     => 'text',
    ));

    // Google Tag Manager Control
    $wp_customize->add_control('google_tag_manager_control', array(
        'label'    => __('Google Tag Manager', 'textdomain'),
        'section'  => 'analytics_settings_section',
        'settings' => 'google_tag_manager',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'ufclas_mercury_customizer_settings');

function add_custom_analytics_code() {
    // Google Analytics
    if ( !empty(get_theme_mod('google_analytics')) ) {
        $googleAnalytics = get_theme_mod('google_analytics');
        $link = get_site_url();
        if (!empty($link)) {
            $url_prefix = preg_match('/^https/', $link) ? 'https://' : 'http://';
        }
        $link = str_replace(array('http://', 'https://'), '', $link);
        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $googleAnalytics; ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo $googleAnalytics; ?>', {'cookie_domain': '<?php echo $link; ?>'});
        </script>
        <?php
    }

    // Google Tag Manager
    if ( !empty(get_theme_mod('google_tag_manager')) ) {
        $googleTagManager = get_theme_mod('google_tag_manager');
        ?>
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $googleTagManager; ?>');
        </script>
        <?php
    }
}
add_action('wp_head', 'add_custom_analytics_code');

function add_google_tag_manager_body() {
    if ( !empty(get_theme_mod('google_tag_manager')) ) {
        $googleTagManager = get_theme_mod('google_tag_manager');
        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $googleTagManager; ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
    }
}

function insert_gtm_code() {
    add_action('wp_body_open', 'add_google_tag_manager_body');
}
add_action('init', 'insert_gtm_code');

       
// Add custom class to style the Gravity forms button
add_filter( 'gform_submit_button', 'add_custom_submit_button_class', 10, 2 );

function add_custom_submit_button_class( $submit_button, $form ) {
  $submit_button = str_replace( '<input', '<input class="animated-border-button button-border-orange"', $submit_button );
  return $submit_button;
}


function my_custom_slider_script() {
    ?>
    <script type="text/javascript">
    jQuery(window).on('load', function () {
        const $slider = jQuery('.carousel-inner.content-carousel-slide');

        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }

        $slider.slick({
            autoplay: true,
            autoplaySpeed: 3000,
            rtl: false, // Change to true if you want to keep right-to-left
            arrows: true,
            dots: true
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'my_custom_slider_script', 100);

  ?>