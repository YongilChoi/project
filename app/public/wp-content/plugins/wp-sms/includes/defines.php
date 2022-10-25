<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Check get_plugin_data function exist
 */
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Set Plugin path and url defines.
define('WP_SMS_URL', plugin_dir_url(dirname(__FILE__)));
define('WP_SMS_DIR', plugin_dir_path(dirname(__FILE__)));

// Get plugin Data.
$plugin_data = get_plugin_data(WP_SMS_DIR . 'wp-sms.php');

// Set another useful Plugin defines.
define('WP_SMS_VERSION', $plugin_data['Version']);
define('WP_SMS_ADMIN_URL', get_admin_url());
define('WP_SMS_SITE', 'https://wp-sms-pro.com');
define('WP_SMS_MOBILE_REGEX', '/^[\+|\(|\)|\d|\- ]*$/');

// old: define('WP_SMS_CURRENT_DATE', current_datetime()->format('Y-m-d H:i:s'));

// New added by Yong 2022-10-24
if ( function_exists( 'current_datetime' ) ) {
	define( 'WP_SMS_CURRENT_DATE', current_datetime()->format( 'Y-m-d H:i:s' ) );
} else {
	define( 'WP_SMS_CURRENT_DATE', get_date_from_gmt( current_time( 'mysql', true ), 'Y-m-d H:i:s' ) );
}

