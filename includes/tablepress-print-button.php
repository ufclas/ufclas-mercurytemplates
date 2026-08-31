<?php
/**
 * TablePress Print Button
 * Adds a Print button to TablePress tables that have "print-button" in their
 * Extra CSS Classes, using the bundled DataTables Buttons extension.
 *
 * @package UFCLAS_MercuryTemplates
 * @since 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// CSS class (in the table's "Extra CSS Classes" field) that opts a table in
define('UFCLAS_TP_PRINT_CLASS', 'print-button');

// Additional opt-in class for compact print styling (fit tables of typical
// size on one letter page at a fixed readable font size; never scale-to-fit)
define('UFCLAS_TP_PRINT_COMPACT_CLASS', 'print-compact');

// Bundled DataTables Buttons extension version
define('UFCLAS_TP_PRINT_BUTTONS_VERSION', '3.2.6');

/**
 * Register the DataTables Buttons extension assets.
 * They are only enqueued when an opted-in table is rendered on the page.
 */
function ufclas_tp_print_register_assets() {
    $url = plugin_dir_url(dirname(__FILE__));
    wp_register_script('ufclas-dt-buttons', $url . 'js/dataTables.buttons.min.js', array('tablepress-datatables'), UFCLAS_TP_PRINT_BUTTONS_VERSION, true);
    wp_register_script('ufclas-dt-buttons-print', $url . 'js/buttons.print.min.js', array('ufclas-dt-buttons'), UFCLAS_TP_PRINT_BUTTONS_VERSION, true);
    wp_register_style('ufclas-dt-buttons', $url . 'css/buttons.dataTables.min.css', array(), UFCLAS_TP_PRINT_BUTTONS_VERSION);
}
add_action('wp_enqueue_scripts', 'ufclas_tp_print_register_assets');

/**
 * Detect the opt-in class and stash the table name in the render options,
 * so the button's accessible name and print title can include it.
 *
 * @param array $render_options Render options (include extra_css_classes).
 * @param array $table          The current table (includes name).
 * @return array
 */
function ufclas_tp_print_render_options($render_options, $table) {
    $classes = preg_split('/\s+/', (string) ($render_options['extra_css_classes'] ?? ''));
    if (in_array(UFCLAS_TP_PRINT_CLASS, $classes, true)) {
        $render_options['ufclas_print_table_name'] = (string) ($table['name'] ?? '');
        $render_options['ufclas_print_compact'] = in_array(UFCLAS_TP_PRINT_COMPACT_CLASS, $classes, true);
    }
    return $render_options;
}
add_filter('tablepress_table_render_options', 'ufclas_tp_print_render_options', 10, 2);

/**
 * When a table with the opt-in class is rendered, enqueue the Buttons assets
 * and flag the table instance so the DataTables parameters can be extended.
 *
 * @param array  $js_options     JavaScript options for the table.
 * @param string $table_id       Table ID.
 * @param array  $render_options Render options.
 * @return array
 */
function ufclas_tp_print_js_options($js_options, $table_id, $render_options) {
    if (isset($render_options['ufclas_print_table_name'])) {
        $js_options['ufclas_print_button'] = $render_options['ufclas_print_table_name'];
        $js_options['ufclas_print_compact'] = !empty($render_options['ufclas_print_compact']);
        wp_enqueue_script('ufclas-dt-buttons-print');
        wp_enqueue_style('ufclas-dt-buttons');
    }
    return $js_options;
}
add_filter('tablepress_table_js_options', 'ufclas_tp_print_js_options', 10, 3);

/**
 * Add the Print button to the DataTables configuration of flagged tables.
 *
 * @param array  $parameters DataTables JS parameters (strings of JS code).
 * @param string $table_id   Table ID.
 * @param string $html_id    HTML ID of the table element.
 * @param array  $js_options JavaScript options for the table.
 * @return array
 */
function ufclas_tp_print_parameters($parameters, $table_id, $html_id, $js_options) {
    if (!isset($js_options['ufclas_print_button'])) {
        return $parameters;
    }
    // The top-left layout slot holds the "entries per page" control when pagination
    // with length change is active; use a second row above the table in that case.
    $slot = (!empty($js_options['datatables_paginate']) && !empty($js_options['datatables_lengthchange'])) ? 'top2Start' : 'topStart';
    $parameters['layout'] = "layout:{{$slot}:'buttons'}";

    $button = "extend:'print'";

    // Table names can be stored HTML-encoded (users without unfiltered_html).
    $table_name = wp_strip_all_tags(html_entity_decode($js_options['ufclas_print_button'], ENT_QUOTES, get_option('blog_charset')));
    if ('' !== $table_name) {
        // Distinct accessible name per button (pages can have several tables),
        // and the table name as the heading of the print view.
        $json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        $label = wp_json_encode(sprintf('Print the %s table', $table_name), $json_flags);
        $title = wp_json_encode(esc_html($table_name), $json_flags); // HTML context in the print view.
        $button .= ",attr:{'aria-label':{$label}},title:{$title}";
    }

    if (!empty($js_options['ufclas_print_compact'])) {
        $button .= ',customize:' . ufclas_tp_print_compact_customize_js();
    }

    $parameters['buttons'] = "buttons:[{{$button}}]";
    return $parameters;
}
add_filter('tablepress_datatables_parameters', 'ufclas_tp_print_parameters', 10, 4);

/**
 * JS callback that styles the print view compactly: letter page, tight
 * margins, and a fixed readable font size. Typical tables fit on one page;
 * oversized tables flow to a second page instead of shrinking further.
 *
 * @return string JS function expression for the print button's customize option.
 */
function ufclas_tp_print_compact_customize_js() {
    // 9.5pt fits the ~22-row Chairs and Directors tables on one letter page
    // with headroom; 10pt measured just over the page height.
    $css = '@page{size:letter;margin:0.5in}'
        . 'body{margin:0}'
        . 'h1{font-size:13pt;margin:0 0 5pt 0}'
        . 'table{border-collapse:collapse;width:100%}'
        . 'th,td{font-size:9.5pt!important;padding:2pt 5pt!important;line-height:1.2!important;text-align:left}';
    return "function(win){var s=win.document.createElement('style');s.textContent='{$css}';win.document.head.appendChild(s);}";
}
