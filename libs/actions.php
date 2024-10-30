<?php
/*
  Built using Caldera Engine
  (C) 2012 - David Cramer
  All Rights Reserved
*/

add_action('init', 'bootstrapbuttons_start');
add_action('admin_footer-widgets.php', 'bootstrapbuttons_widgetjs');
add_action('admin_head-widgets.php', 'bootstrapbuttons_widgetcss');
add_action('admin_head-widgets.php', 'bootstrapbuttons_widgetscripts');

add_filter('template_include', 'bootstrapbuttons_contentPrefilter');

add_action('get_header', 'bootstrapbuttons_getheader');
add_action('get_footer', 'bootstrapbuttons_getfooter');
add_action('wp_head', 'bootstrapbuttons_header');
add_action('wp_footer', 'bootstrapbuttons_footer');

add_action('media_buttons', 'bootstrapbuttons_button', 11);
add_filter('widget_text', 'do_shortcode');



add_shortcode('bsbutton', 'bootstrapbuttons_doShortcode');

if(is_admin() === true){
    add_action('wp_ajax_bootstrapbuttons_load_elementConfig', 'bootstrapbuttons_load_elementConfig');
    add_action('wp_ajax_bootstrapbuttons_loadmedia_page', 'bootstrapbuttons_loadmedia_page');
    add_action('wp_ajax_bootstrapbuttons_addgroup', 'bootstrapbuttons_addGroup');
    
}
add_action('wp_ajax_my_shortcode_ajax', 'bootstrapbuttons_shortcode_ajax');
add_action('wp_ajax_nopriv_my_shortcode_ajax', 'bootstrapbuttons_shortcode_ajax');
add_action('wp_ajax_element_ajax', 'bootstrapbuttons_shortcode_ajax');
add_action('wp_ajax_nopriv_element_ajax', 'bootstrapbuttons_shortcode_ajax');

?>