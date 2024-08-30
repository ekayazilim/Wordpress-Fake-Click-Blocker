<?php
/*
Plugin Name: Fake Click Blocker
Description: Sahte tıklama ve bot trafiği tespit ve engelleme eklentisi.
Version: 1.0
Author: Eka Yazılım Bilişim Sistemleri
Author URI: https://ekayazilim.com.tr
*/

if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files.
require_once plugin_dir_path(__FILE__) . 'includes/class-fake-click-blocker.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-fake-click-blocker-admin.php';

// Initialize the plugin.
function fcb_init() {
    Fake_Click_Blocker::get_instance();
    if (is_admin()) {
        Fake_Click_Blocker_Admin::get_instance();
    }
}
add_action('plugins_loaded', 'fcb_init');

// Create tables for logging user and admin visits.
register_activation_hook(__FILE__, function() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table for user logs
    $user_logs_table = $wpdb->prefix . 'fcb_user_logs';
    $user_logs_sql = "CREATE TABLE $user_logs_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address varchar(100) NOT NULL,
        user_agent text NOT NULL,
        visit_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        page_visited varchar(255) NOT NULL,
        banned tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Table for admin logs
    $admin_logs_table = $wpdb->prefix . 'fcb_admin_logs';
    $admin_logs_sql = "CREATE TABLE $admin_logs_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address varchar(100) NOT NULL,
        user_agent text NOT NULL,
        visit_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        page_visited varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($user_logs_sql);
    dbDelta($admin_logs_sql);
});
?>
