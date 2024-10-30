<?php
/*
Plugin Name: Bootstrap Buttons
Plugin URI: http://cramer.co.za
Description: The full set of buttons from Twitters Bootstrap available to use in your content via a shortcode.
Author: David Cramer
Version: 1.2
Author URI: http://cramer.co.za
Build Date: 2013-07-25 @ 16:57 pm
*/

/*
  Built using Caldera Engine 2.1 on WordPress 3.6-RC1
  (C) 2013 - David Cramer
  All Rights Reserved
*/
//initilize plugin
define('BOOTSTRAPBUTTONS_PATH', plugin_dir_path(__FILE__));
define('BOOTSTRAPBUTTONS_URL', plugin_dir_url(__FILE__));

require_once BOOTSTRAPBUTTONS_PATH.'libs/functions.php';
require_once BOOTSTRAPBUTTONS_PATH.'libs/actions.php';




register_activation_hook( __FILE__, 'bootstrapbuttons_setup');
register_deactivation_hook( __FILE__, 'bootstrapbuttons_exit');
?>