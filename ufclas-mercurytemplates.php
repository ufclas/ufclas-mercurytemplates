<?php
/**
 * Plugin Name:       UFCLAS Mercury Templates
 * Requires Plugins: advanced-custom-fields
 * Description:       Additional custom UFCLAS templates and styles for use with base Mercury theme.
 * Version:           1.0.0
 * Text Domain:       https://github.com/ufclas/ufclas-mercurytemplates/blob/main/README.md
 * Author:            Suzie Israel
 */




// Enqueue plugin styles and add scss compilation function

function enqueue_plugin_styles() {
    $style_url = plugins_url( 'css/style.css', __FILE__ );
    wp_enqueue_style( 'plugin-style', $style_url );
}
add_action('wp_enqueue_scripts', 'enqueue_plugin_styles'); // Higher priority





// Register Custom Post Templates
function my_post_template_array() {
    return [
        'custom-post-contained.php' => 'No Sidebar Inc. Breadcrumbs',
        'custom-post-fullwidth.php' => 'Default Inc. Breadcrumbs',
        'custom-post-fullwidth-article.php' => 'Full width article'
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



//remove "Category" and "Tag" from before the category Title in archives

function prefix_category_title( $title ) {
	if ( is_category() || is_tag() ) {
	$title = single_cat_title( '', false );
	}
	return $title;
	}
	add_filter( 'get_the_archive_title', 'prefix_category_title' );





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
// Add meta boxes to post editor
add_action('add_meta_boxes', function($post) {
    add_meta_box('date_id', 'Hide Elements', 'crt_metaBox_elements', 'post', 'side', 'low');
    add_meta_box('social_sharing_id', 'Social Sharing', 'crt_metaBox_social_sharing', 'post', 'side', 'low');
});

// Render the Hide Elements meta box
function crt_metaBox_elements($post){
    $fields = [
        'hide_date' => 0,
        'hide_author' => 1,
        'hide_featured_image' => 1
    ];

    foreach ($fields as $field => $default) {
        $value = get_post_meta($post->ID, $field, true);
        if ($value === '' && $post->post_status === 'auto-draft') {
            $value = $default;
        }
        echo '<p class="ufl_checkbox">';
        echo '<span>' . ucwords(str_replace('_', ' ', $field)) . '</span>';
        echo '<input type="checkbox" name="' . $field . '" id="' . $field . '" value="1" ' . checked($value, 1, false) . ' />';
        echo '</p>';
    }
}

// Render the Social Sharing meta box
function crt_metaBox_social_sharing($post){
    // Check for legacy hide_socials setting
    $hide_all_socials = get_post_meta($post->ID, 'hide_socials', true);

    $social_fields = [
        'show_facebook' => 1,
        'show_twitter' => 1,
        'show_email' => 1,
        'show_linkedin' => 1,
        'show_bluesky' => 1
    ];

    echo '<p style="margin-bottom: 10px; color: #666; font-size: 12px;">Select which social sharing buttons to display:</p>';

    foreach ($social_fields as $field => $default) {
        $value = get_post_meta($post->ID, $field, true);
        // Handle legacy hide_socials - if it's set to 1, default all to 0
        if ($value === '' && $hide_all_socials == '1') {
            $value = 0;
        } elseif ($value === '' && $post->post_status === 'auto-draft') {
            $value = $default;
        }
        $label = ucwords(str_replace(['show_', '_'], ['', ' '], $field));
        echo '<p class="ufl_checkbox">';
        echo '<span>' . $label . '</span>';
        echo '<input type="checkbox" name="' . $field . '" id="' . $field . '" value="1" ' . checked($value, 1, false) . ' />';
        echo '</p>';
    }
}

// Save meta box data
add_action('save_post', function($post_id){
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['hide_date', 'hide_author', 'hide_featured_image'];
    $social_fields = ['show_facebook', 'show_twitter', 'show_email', 'show_linkedin', 'show_bluesky'];

    // Save regular fields
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, 1);
        } elseif (isset($_POST['action']) && $_POST['action'] === 'editpost') {
            update_post_meta($post_id, $field, 0);
        }
    }

    // Save social fields
    foreach ($social_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, 1);
        } elseif (isset($_POST['action']) && $_POST['action'] === 'editpost') {
            update_post_meta($post_id, $field, 0);
        }
    }
});

// Add custom column to store data for JS (optional)
add_filter('manage_post_posts_columns', function($columns) {
    $columns['hide_elements'] = 'Hide Elements';
    return $columns;
});

add_action('manage_post_custom_column', function($column_name, $post_id) {
    if ($column_name == 'hide_elements') {
        $fields = ['hide_date', 'hide_socials', 'hide_author', 'hide_featured_image'];
        echo '<div class="hidden column-hide_elements"';
        foreach ($fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            echo " data-{$field}='{$value}'";
        }
        echo '></div>';
    }
}, 10, 2);

// Apply default meta values to imported posts
add_action('save_post', function($post_id) {
    // Avoid autosaves and revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Check if this is an import
    if (defined('WP_IMPORTING') && WP_IMPORTING) {
        $fields = [
            'hide_date' => 1,
            'hide_socials' => 0,
            'hide_author' => 1,
            'hide_featured_image' => 1
        ];

        foreach ($fields as $field => $default) {
            // Only set if not already set
            if (get_post_meta($post_id, $field, true) === '') {
                update_post_meta($post_id, $field, $default);
            }
        }
    }
}, 20); // Priority 20 to run after import sets post data

// Check if ACF is active and show admin notice if not
function ufclas_mercury_check_acf_dependency() {
    if (!function_exists('acf_add_local_field_group')) {
        add_action('admin_notices', 'ufclas_mercury_acf_missing_notice');
    }
}
add_action('init', 'ufclas_mercury_check_acf_dependency');

function ufclas_mercury_acf_missing_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><strong>UFCLAS Mercury Templates:</strong> The "Full Width Article" template requires the Advanced Custom Fields (ACF) plugin to work properly. Please install and activate ACF to use all template features.</p>
    </div>
    <?php
}

// ACF Field Group for Post Header Fields (Full Width Article Template)
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_post_header_fields',
    'title' => 'Post Header Fields',
    'fields' => array(
        array(
            'key' => 'field_header_image',
            'label' => 'Header Image',
            'name' => 'header_image',
            'type' => 'image',
            'instructions' => 'Upload an image to use as the header background. Leave empty for a simple title header with light gray background.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => 'jpg,jpeg,png,webp',
        ),
        array(
            'key' => 'field_header_title',
            'label' => 'Header Title',
            'name' => 'header_title',
            'type' => 'text',
            'instructions' => 'Leave blank to use the post title. Enter custom text to override the post title in the header.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => 'Enter custom header title (optional)',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_header_subtitle',
            'label' => 'Header Subtitle',
            'name' => 'header_subtitle',
            'type' => 'text',
            'instructions' => 'Subtitle text to display below the main title. On hero images, appears as white text below the title. On simple headers, appears below the title.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => 'Enter subtitle text (optional)',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'custom-post-fullwidth-article.php',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => 'Fields for customizing the header content in the Full Width Article template.',
));

endif;

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

/**
 * Hide specific Customizer sections and widget areas from non-superadmins
 * @param WP_Customize_Manager $wp_customize
 */
function hide_customizer_sections_for_non_superadmins( $wp_customize ) {
    // Only hide sections if the user is NOT a superadmin
    if ( ! is_super_admin() ) {
        // Hide main customizer sections
        $wp_customize->remove_section( 'title_tagline' ); // Site Identity
        $wp_customize->remove_section( 'analytics_settings_section' ); // Analytics Settings
        $wp_customize->remove_section( 'mytheme_section' ); // Alternate Logo Section
        $wp_customize->remove_section( 'ufclas_different_footer_logo_section' ); // Different Footer Logo Section

        // Hide specific widget areas by removing their sidebar sections
        $wp_customize->remove_section( 'sidebar-widgets-top-nav' ); // Global Alert
        $wp_customize->remove_section( 'sidebar-widgets-footer-1' ); // Footer Link Column 1
        $wp_customize->remove_section( 'sidebar-widgets-footer-2' ); // Footer Link Column 2
        $wp_customize->remove_section( 'sidebar-widgets-footer-3' ); // Footer Link Column 3
        $wp_customize->remove_section( 'sidebar-widgets-footer-4' ); // Footer Link Column 4
        $wp_customize->remove_section( 'sidebar-widgets-footer-copyright' ); // Copyright
    }
}
// Hook with high priority to ensure it runs after all sections are registered
add_action( 'customize_register', 'hide_customizer_sections_for_non_superadmins', 999 );

/**
 * Hide Themes submenu from Appearance menu for non-superadmins
 */
function hide_themes_menu_for_non_superadmins() {
    if ( ! is_super_admin() ) {
        remove_submenu_page( 'themes.php', 'themes.php' );
    }
}
add_action( 'admin_menu', 'hide_themes_menu_for_non_superadmins', 999 );

/**
 * Hide specific widget areas from Widgets admin page for non-superadmins
 */
function hide_widget_areas_for_non_superadmins() {
    if ( ! is_super_admin() ) {
        // Unregister widget areas to hide them from Widgets page
        unregister_sidebar( 'top-nav' ); // Global Alert
        unregister_sidebar( 'footer-1' ); // Footer Link Column 1
        unregister_sidebar( 'footer-2' ); // Footer Link Column 2
        unregister_sidebar( 'footer-3' ); // Footer Link Column 3
        unregister_sidebar( 'footer-4' ); // Footer Link Column 4
        unregister_sidebar( 'footer-copyright' ); // Copyright
    }
}
add_action( 'widgets_init', 'hide_widget_areas_for_non_superadmins', 999 );

// Add body classes based on social sharing settings
add_filter('body_class', function($classes) {
    global $post;
    if (is_single() && $post) {
        // Add hide classes for ShareThis buttons based on post meta
        if (!get_post_meta($post->ID, 'show_facebook', true)) {
            $classes[] = 'hide-facebook-share';
        }
        if (!get_post_meta($post->ID, 'show_twitter', true)) {
            $classes[] = 'hide-twitter-share';
        }
        if (!get_post_meta($post->ID, 'show_email', true)) {
            $classes[] = 'hide-email-share';
        }
        if (!get_post_meta($post->ID, 'show_linkedin', true)) {
            $classes[] = 'hide-linkedin-share';
        }
    }
    return $classes;
});

  ?>