<?php
/**
 * Plugin Name:       UFCLAS Additional Mercury Templates
 * Description:       Additional custom UFCLAS templates for use with base Mercury theme.
 * Version:           1.0.0
 * Text Domain:       clas.ufl.edu
 */

function my_template_array () 
{
		$temps = [];

		$temps['custom-post-archive.php'] = 'Custom Post Archive';

		return $temps;
}

function my_template_register($page_templates,$theme,$post) 
{

	$templates = my_template_array();

	foreach($templates as $tk=>$tv) 
	{
		$page_templates[$tk] = $tv;
	}

	return $page_templates;
}
add_filter('theme_page_templates','my_template_register',10,3);

function my_template_select($template)
{
	global $post,$wp_query,$wpdb;

	$page_temp_slug = get_page_template_slug( $post->ID );

	$templates = my_template_array();


	if(isset($templates[$page_temp_slug]))
	{
			$template = plugin_dir_path(__FILE__).'templates/'.$page_temp_slug;
	}

	return $template;
}
add_filter('template_include','my_template_select', 99);

function use_custom_template($tpl){
	if ( is_post_type_archive () ) {
	  $tpl = plugin_dir_path( __FILE__ ) . 'templates/archive.php';
	}
	return $tpl;
  }
  
  add_filter( 'archive_template', 'use_custom_template' ) ;
  
  ?>