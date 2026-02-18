<?php
/**
 * Plugin Name:       UFCLAS Mercury Templates
 * Requires Plugins: advanced-custom-fields
 * Description:       Additional custom UFCLAS templates and styles for use with base Mercury theme.
 * Version:           1.0.1
 * Author:            Ronit Singh
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}




/**
 * Enqueue plugin styles
 *
 * @return void
 */
function enqueue_plugin_styles() {
    $style_url = plugins_url( 'css/style.css', __FILE__ );
    wp_enqueue_style( 'plugin-style', $style_url );
}
add_action('wp_enqueue_scripts', 'enqueue_plugin_styles');





/**
 * Get array of custom post templates
 *
 * @return array Template slug => Template name
 */
function my_post_template_array() {
    return [
        'custom-post-contained.php' => 'No Sidebar Inc. Breadcrumbs',
        'custom-post-fullwidth.php' => 'Default Inc. Breadcrumbs',
        'custom-post-fullwidth-article.php' => 'Full width article'
    ];
}

/**
 * Register custom templates for posts
 *
 * @param array $post_templates Existing post templates
 * @param WP_Theme $theme Current theme object
 * @param WP_Post $post Current post object
 * @return array Modified post templates array
 */
function my_post_template_register($post_templates, $theme, $post) {
    $templates = my_post_template_array();
    foreach($templates as $tk => $tv) {
        $post_templates[$tk] = $tv;
    }
    return $post_templates;
}
add_filter('theme_post_templates', 'my_post_template_register', 10, 3);

/**
 * Load custom template file for posts
 *
 * @param string $template Path to template file
 * @return string Modified template path
 */
function my_post_template_select($template) {
    global $post;
    if (is_single() && $post->post_type === 'post') {
        $post_temp_slug = get_post_meta($post->ID, '_wp_page_template', true);
        $templates = my_post_template_array();

        if (isset($templates[$post_temp_slug])) {
            $template = plugin_dir_path(__FILE__) . 'templates/' . $post_temp_slug;
        }
    }
    return $template;
}
add_filter('template_include', 'my_post_template_select');

// Hook to register the custom archive template (removed - empty function)

// Single News Filtering handlers removed - using theme's handlers from functions.php instead
// This prevents conflicts with the News Template (home.php)



/**
 * Remove "Category" and "Tag" prefix from archive titles
 *
 * @param string $title The archive title
 * @return string Modified title without prefix
 */
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

    $member_meta['selected-category'] = sanitize_text_field($_POST['selected-category']);

    if (is_array($member_meta)) {
        foreach ($member_meta as $key => $value) :
            // Don't store custom data twice
            if ('revision' === get_post_type($post_id)) {
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
});

/**
 * Render the Hide Elements meta box
 * Displays checkboxes for hiding date, author, and featured image
 * Values come from post meta (set by ufclas_apply_new_post_defaults on creation)
 *
 * @param WP_Post $post Current post object
 * @return void
 */
function crt_metaBox_elements($post){
    $fields = ['hide_date', 'hide_author', 'hide_featured_image'];

    foreach ($fields as $field) {
        // Get value from post meta
        // For new posts, this will be set by ufclas_apply_new_post_defaults()
        $value = get_post_meta($post->ID, $field, true);

        echo '<p class="ufl_checkbox">';
        echo '<span>' . ucwords(str_replace('_', ' ', $field)) . '</span>';
        echo '<input type="checkbox" name="' . $field . '" id="' . $field . '" value="1" ' . checked($value, 1, false) . ' />';
        echo '</p>';
    }
}


// Save meta box data
add_action('save_post', function($post_id){
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['hide_date', 'hide_author', 'hide_featured_image'];

    // Save regular fields
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, 1);
        } elseif (isset($_POST['action']) && $_POST['action'] === 'editpost') {
            update_post_meta($post_id, $field, 0);
        }
    }
});

/**
 * Apply Customizer defaults to new posts automatically
 * Copies global Customizer settings to individual post meta on creation
 * Only runs for new posts - existing posts are never modified
 *
 * @param int $post_id Post ID
 * @param WP_Post $post Post object
 * @param bool $update Whether this is an existing post being updated
 * @return void
 */
function ufclas_apply_new_post_defaults($post_id, $post, $update) {
    // Only apply to posts (not pages or custom post types)
    if ($post->post_type !== 'post') {
        return;
    }

    // Skip autosaves and revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Skip imports (they have their own defaults on lines 246-268)
    if (defined('WP_IMPORTING') && WP_IMPORTING) {
        return;
    }

    // For updated posts, only apply if meta doesn't exist yet
    // This ensures we only set defaults once
    if ($update) {
        $existing_hide_date = get_post_meta($post_id, 'hide_date', true);
        if ($existing_hide_date !== '') {
            return; // Meta already exists, this isn't a new post
        }
    }

    // Apply Hide Elements defaults from Customizer
    $hide_defaults = array(
        'hide_date' => (bool) get_theme_mod('default_hide_date', false),
        'hide_author' => (bool) get_theme_mod('default_hide_author', false),
        'hide_featured_image' => (bool) get_theme_mod('default_hide_featured_image', false)
    );

    foreach ($hide_defaults as $field => $value) {
        // Only set if meta doesn't exist
        if (get_post_meta($post_id, $field, true) === '') {
            update_post_meta($post_id, $field, $value ? 1 : 0);
        }
    }

    // Apply Template default from Customizer
    $default_template = get_theme_mod('default_post_template', '');
    if (!empty($default_template)) {
        // Check if template meta already set
        $existing_template = get_post_meta($post_id, '_wp_page_template', true);

        // Only set if not already set or is default
        if (empty($existing_template) || $existing_template === 'default') {
            // Validate template still exists (in case it was removed)
            $theme = wp_get_theme();
            $all_templates = $theme->get_post_templates();
            $available_templates = array();
            if (isset($all_templates['post']) && is_array($all_templates['post'])) {
                $available_templates = $all_templates['post'];
            }
            $available_templates = apply_filters('theme_post_templates', $available_templates, $theme, null);

            if (isset($available_templates[$default_template])) {
                update_post_meta($post_id, '_wp_page_template', $default_template);
            }
        }
    }
}
add_action('wp_insert_post', 'ufclas_apply_new_post_defaults', 10, 3);

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

/**
 * Apply default meta values to imported posts
 *
 * NOTE: These defaults are specifically for bulk-imported content and are
 * intentionally different from the "New Post Settings" in the Customizer.
 * Imported posts typically need different defaults than manually created posts.
 *
 * @param int $post_id Post ID
 * @return void
 */
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

/**
 * Shortcode to display current year
 * Usage: [year]
 *
 * @return string Current year
 */
function year_shortcode() {
	$year = date_i18n('Y');
	return $year;
}
add_shortcode('year', 'year_shortcode');


/**
 * Disable core block patterns
 *
 * @return void
 */
add_action('init', function() {
	remove_theme_support('core-block-patterns');
});

/**
 * Add unfiltered HTML capability to administrators
 * Prevents WordPress from stripping iframes and other HTML tags
 *
 * @param array $caps Required capabilities
 * @param string $cap Capability being checked
 * @param int $user_id User ID
 * @return array Modified capabilities
 */
function km_add_unfiltered_html_capability_to_editors( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = [ 'unfiltered_html' ];
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'km_add_unfiltered_html_capability_to_editors', 1, 3 );

/**
 * Add Analytics and Social Settings to WordPress Customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance
 * @return void
 */
function ufclas_mercury_customizer_settings($wp_customize) {
    // Add Analytics Section
    $wp_customize->add_section('analytics_settings_section', array(
        'title'    => __('Analytics Settings', 'ufclas-mercurytemplates'),
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
        'label'    => __('Google Analytics', 'ufclas-mercurytemplates'),
        'section'  => 'analytics_settings_section',
        'settings' => 'google_analytics',
        'type'     => 'text',
    ));

    // Google Tag Manager Control
    $wp_customize->add_control('google_tag_manager_control', array(
        'label'    => __('Google Tag Manager', 'ufclas-mercurytemplates'),
        'section'  => 'analytics_settings_section',
        'settings' => 'google_tag_manager',
        'type'     => 'text',
    ));

    // Add Social Settings Section
    $wp_customize->add_section('social_settings_section', array(
        'title'    => __('Social Settings', 'ufclas-mercurytemplates'),
        'priority' => 35,
        'description' => 'Configure default social sharing buttons for posts that use the Full Width Article or No Sidebar Inc. Breadcrumbs page templates. If using the Post Intro Block to display social sharing buttons, use the Block Settings\' Social Sharing Options instead.',
    ));

    // Social platform settings
    $social_platforms = array(
        'show_facebook' => 'Show Facebook on Posts',
        'show_twitter' => 'Show Twitter on Posts',
        'show_email' => 'Show Email on Posts',
        'show_linkedin' => 'Show LinkedIn on Posts',
        'show_bluesky' => 'Show Bluesky on Posts'
    );

    foreach ($social_platforms as $platform => $label) {
        // Add setting with default value of true
        $wp_customize->add_setting($platform, array(
            'default'   => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean'
        ));

        // Add checkbox control
        $wp_customize->add_control($platform . '_control', array(
            'label'    => __($label, 'ufclas-mercurytemplates'),
            'section'  => 'social_settings_section',
            'settings' => $platform,
            'type'     => 'checkbox',
        ));
    }
}
add_action('customize_register', 'ufclas_mercury_customizer_settings');

/**
 * Add New Post Settings to WordPress Customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance
 * @return void
 */
function ufclas_new_post_settings_customizer($wp_customize) {
    // Add New Post Settings Section
    $wp_customize->add_section('new_post_settings_section', array(
        'title'    => __('New Post Settings', 'ufclas-mercurytemplates'),
        'priority' => 40,
        'description' => __('Configure default settings that will be automatically applied when creating new posts. Users can override these settings for individual posts in the post editor.', 'ufclas-mercurytemplates'),
    ));

    // Setting: Default Post Template
    $wp_customize->add_setting('default_post_template', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'ufclas_sanitize_template_choice'
    ));

    // Control: Default Post Template - Dynamically populate with ALL available post templates
    $template_choices = array('' => __('Default Template', 'ufclas-mercurytemplates'));

    // Get ALL post templates - both from file headers and filter registration
    // First get templates defined in theme files via headers
    $theme = wp_get_theme();
    $post_templates = $theme->get_post_templates();

    // Filter to get only templates for 'post' post type
    $post_type_templates = array();
    if (isset($post_templates['post']) && is_array($post_templates['post'])) {
        $post_type_templates = $post_templates['post'];
    }

    // Apply filter to add plugin-registered templates
    $post_type_templates = apply_filters('theme_post_templates', $post_type_templates, $theme, null);

    // Merge with choices
    if (!empty($post_type_templates) && is_array($post_type_templates)) {
        $template_choices = array_merge($template_choices, $post_type_templates);
    }

    $wp_customize->add_control('default_post_template_control', array(
        'label'    => __('Default Post Template', 'ufclas-mercurytemplates'),
        'description' => __('Select which template should be pre-selected for new posts.', 'ufclas-mercurytemplates'),
        'section'  => 'new_post_settings_section',
        'settings' => 'default_post_template',
        'type'     => 'select',
        'choices'  => $template_choices
    ));

    // Setting: Hide Date by Default
    $wp_customize->add_setting('default_hide_date', array(
        'default'   => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('default_hide_date_control', array(
        'label'    => __('Hide Date by Default', 'ufclas-mercurytemplates'),
        'description' => __('Check to hide the post date on new posts by default.', 'ufclas-mercurytemplates'),
        'section'  => 'new_post_settings_section',
        'settings' => 'default_hide_date',
        'type'     => 'checkbox',
    ));

    // Setting: Hide Author by Default
    $wp_customize->add_setting('default_hide_author', array(
        'default'   => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('default_hide_author_control', array(
        'label'    => __('Hide Author by Default', 'ufclas-mercurytemplates'),
        'description' => __('Check to hide the post author on new posts by default.', 'ufclas-mercurytemplates'),
        'section'  => 'new_post_settings_section',
        'settings' => 'default_hide_author',
        'type'     => 'checkbox',
    ));

    // Setting: Hide Featured Image by Default
    $wp_customize->add_setting('default_hide_featured_image', array(
        'default'   => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('default_hide_featured_image_control', array(
        'label'    => __('Hide Featured Image by Default', 'ufclas-mercurytemplates'),
        'description' => __('Check to hide the featured image on new posts by default.', 'ufclas-mercurytemplates'),
        'section'  => 'new_post_settings_section',
        'settings' => 'default_hide_featured_image',
        'type'     => 'checkbox',
    ));
}
add_action('customize_register', 'ufclas_new_post_settings_customizer');

/**
 * Sanitize template choice from Customizer
 * Validates that selected template exists in available templates
 *
 * @param string $value Template filename
 * @return string Sanitized template filename or empty string
 */
function ufclas_sanitize_template_choice($value) {
    // Empty string is valid (means "Default Template")
    if (empty($value)) {
        return '';
    }

    // Get ALL available post templates (file headers + filter)
    $theme = wp_get_theme();
    $all_templates = $theme->get_post_templates();
    $available_templates = array();
    if (isset($all_templates['post']) && is_array($all_templates['post'])) {
        $available_templates = $all_templates['post'];
    }
    // Add filter-registered templates
    $available_templates = apply_filters('theme_post_templates', $available_templates, $theme, null);

    // Check if selected template exists in available templates
    if (isset($available_templates[$value])) {
        return sanitize_text_field($value);
    }

    // Invalid template, return empty string (Default Template)
    return '';
}

/**
 * Add Google Analytics and Tag Manager code to site head
 *
 * @return void
 */
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

/**
 * Add Google Tag Manager noscript code to body
 *
 * @return void
 */
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

/**
 * Hook GTM body code into wp_body_open
 *
 * @return void
 */
function insert_gtm_code() {
    add_action('wp_body_open', 'add_google_tag_manager_body');
}
add_action('init', 'insert_gtm_code');

/**
 * Add custom CSS class to Gravity Forms submit button
 *
 * @param string $submit_button Submit button HTML
 * @param array $form Current form object
 * @return string Modified button HTML
 */
function add_custom_submit_button_class( $submit_button, $form ) {
  $submit_button = str_replace( '<input', '<input class="animated-border-button button-border-orange"', $submit_button );
  return $submit_button;
}
add_filter( 'gform_submit_button', 'add_custom_submit_button_class', 10, 2 );

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
 * Uses sidebars_widgets filter to hide in admin only, preserving frontend functionality
 */
function hide_widget_areas_for_non_superadmins( $sidebars_widgets ) {
    // Only hide in admin area (not during AJAX) and for non-superadmins
    if ( is_admin() && ! wp_doing_ajax() && ! is_super_admin() ) {
        // Check if we're specifically on the widgets page
        global $pagenow;
        if ( $pagenow === 'widgets.php' ) {
            // Remove widget areas from the list
            unset( $sidebars_widgets['top-nav'] );
            unset( $sidebars_widgets['footer-1'] );
            unset( $sidebars_widgets['footer-2'] );
            unset( $sidebars_widgets['footer-3'] );
            unset( $sidebars_widgets['footer-4'] );
            unset( $sidebars_widgets['footer-copyright'] );
        }
    }
    return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'hide_widget_areas_for_non_superadmins' );

// Add body classes based on social sharing settings from Customizer
add_filter('body_class', function($classes) {
    if (is_single()) {
        // Add hide classes for ShareThis buttons based on Customizer settings
        if (!(bool)get_theme_mod('show_facebook', true)) {
            $classes[] = 'hide-facebook-share';
        }
        if (!(bool)get_theme_mod('show_twitter', true)) {
            $classes[] = 'hide-twitter-share';
        }
        if (!(bool)get_theme_mod('show_email', true)) {
            $classes[] = 'hide-email-share';
        }
        if (!(bool)get_theme_mod('show_linkedin', true)) {
            $classes[] = 'hide-linkedin-share';
        }
    }
    return $classes;
});

// Include Post Intro Block override
require_once plugin_dir_path(__FILE__) . 'includes/post-intro-override.php';

// Include custom social icon accessibility fixes
require_once plugin_dir_path(__FILE__) . 'includes/custom-social-icon-accessibility.php';

// Add attributes to Post Intro Block via filter
add_filter('register_block_type_args', 'ufclas_add_post_intro_attributes', 10, 2);

function ufclas_add_post_intro_attributes($args, $block_type) {
    // Check if this is our target block
    if ($block_type === 'create-block/single-post-intro') {
        // Add social sharing attributes to the existing attributes
        if (!isset($args['attributes'])) {
            $args['attributes'] = array();
        }

        // Add our custom attributes
        $args['attributes']['showFacebook'] = array(
            'type' => 'boolean',
            'default' => true
        );
        $args['attributes']['showTwitter'] = array(
            'type' => 'boolean',
            'default' => true
        );
        $args['attributes']['showEmail'] = array(
            'type' => 'boolean',
            'default' => true
        );
        $args['attributes']['showLinkedin'] = array(
            'type' => 'boolean',
            'default' => true
        );
        $args['attributes']['showBluesky'] = array(
            'type' => 'boolean',
            'default' => true
        );

        // Override the render callback
        $args['render_callback'] = 'ufclas_render_single_post_intro_block_enhanced';
    }

    return $args;
}

// Enqueue block editor extensions
add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_script(
        'ufclas-post-intro-extension',
        plugins_url('js/post-intro-extension.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-compose', 'wp-hooks'),
        '1.0.0'
    );
});

// Show updated content in dashboard widget
function recently_updated_dashboard_widget() {
    wp_add_dashboard_widget(
        'recently_updated_widget',
        'Recently Updated Content',
        'recently_updated_widget_display'
    );
}

function recently_updated_widget_display() {
    $recent_updates = get_posts([
        'post_type'      => ['post', 'page'],
        'posts_per_page' => 10,
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ]);

    if (empty($recent_updates)) {
        echo '<p>No recent updates.</p>';
        return;
    }

    echo '<ul>';
    foreach ($recent_updates as $post) {
        $modified = get_the_modified_date('M j, g:i a', $post);
        echo '<li>' . esc_html($modified) . ' — <a href="' . get_edit_post_link($post->ID) . '">' . esc_html(get_the_title($post)) . '</a></li>';
    }
    echo '</ul>';
}

add_action('wp_dashboard_setup', 'recently_updated_dashboard_widget');
  ?>
