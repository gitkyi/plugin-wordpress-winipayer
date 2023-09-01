<?php

use WpPlugins\Winipayer\Winipayer;

/**
 * @package Winipayer
 * @version 1.0.0
 */
/*
Plugin Name: WiniPayer
Plugin URI: http://wordpress.org/plugins/winipayer/
Description: WiniPayer your online payment API, easy integration on mobile and web applications.
Author: Jars Technologies
Version: 1.0.0
Author URI: https://www.jarstechnologies.com/
*/

if (!defined('ABSPATH'))
    exit;

define('WINIPAYER_PLUGIN_DIR',plugin_dir_path(__FILE__));

require WINIPAYER_PLUGIN_DIR . 'vendor/autoload.php';

$winipayer = new Winipayer(__FILE__);

?>