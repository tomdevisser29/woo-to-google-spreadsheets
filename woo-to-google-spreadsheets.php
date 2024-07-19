<?php

/**
 * Plugin Name: WooCommerce to Google Spreadsheets
 */

defined('ABSPATH') or die;

define('PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once PLUGIN_DIR . 'vendor/autoload.php';
require_once PLUGIN_DIR . 'includes/helpers.php';
require_once PLUGIN_DIR . 'includes/handle-orders.php';
