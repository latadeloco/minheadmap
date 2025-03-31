<?php
/**
 * @package Min Headmap
 * @version 1.0.0
 */
/*
Plugin Name: Min Headmap
Plugin URI: https://github.com/latadeloco
Description: This pluging contains a small headmap to track what your users are doing on your website
Author: Jesús Robles
Version: 1.0.0
Author URI: https://jesusrobles.es
*/

if (!defined('ABSPATH')) {
    throw new Exception("Access Denied!");
}

define( 'MINHEADMAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MINHEADMAP_PLUGIN_DIR_BACKOFFICE', plugin_dir_path( __FILE__ ). 'Backoffice/' );

require_once ABSPATH . 'wp-settings.php';
require_once MINHEADMAP_PLUGIN_DIR . 'Utils/UUID.php';
require __DIR__ . '/app/bin/Autoloader.php';
use MinHeadmap\App\Bin\Autoloader;
use MinHeadmap\Report\Infrastructure\Bin\ReportProvider;

Autoloader::addNamespace('MinHeadmap\\Report', __DIR__ . '/app/Report');
Autoloader::register();

new ReportProvider();













register_activation_hook(  __FILE__ , 'activate' );
register_deactivation_hook(  __FILE__ , 'deactivate' );
add_action('wp_footer', 'scripting', 11);
add_action('init', 'pushCookie');

function activate() {
    global $wpdb;
    $table = $wpdb->prefix . 'minheadmap_data';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
            uuid binary(16) NOT NULL PRIMARY KEY,
            session_id binary(16) NOT NULL,
            data JSON NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function deactivate() {
    global $wpdb;
    $table = $wpdb->prefix . 'minheadmap_data';
    $sql = "DROP TABLE IF EXISTS $table";
    $wpdb->query($sql);
}

function scripting(): void
{
    wp_enqueue_script(
        'mi-script',
        plugins_url(
            'mi-script.js',
            __FILE__
        ),
        array(),
        null,
        true
    );
    wp_localize_script(
        'mi-script',
        'backend_data',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('min_headmap_nopriv_store_nonce'),
            'logued' => is_user_logged_in()
        )
    );
}

function pushCookie(): void {
    if (!isset($_COOKIE['min_headmap_uuid_cookie'])) {
        $name = 'min_headmap_uuid_cookie';
        $value = UUID::v4();
        $time = time() + (86400 * 30);
        setcookie($name, $value, $time);
    }
}

add_action('wp_ajax_nopriv_min_head_store_data', 'min_head_store_data');
function min_head_store_data(): void
{
    try {
        $nonce = isset($_SERVER['HTTP_X_WP_NONCE']) ? sanitize_text_field($_SERVER['HTTP_X_WP_NONCE']) : '';
        if (!wp_verify_nonce($nonce, 'min_headmap_nopriv_store_nonce')) {
            wp_send_json_error('Nonce inválido', 403);
            exit;
        }
        $data = $_POST['data'] ?? null;
        if (null === $data) {
            wp_send_json_error( 'Invalid data!',400);
        }

        $data = str_replace('\\', '', $data);
        $arrayData = json_decode($data, true);
        $sessionId = $arrayData['session_id'];
        $event = $arrayData['event'];
        $device = $arrayData['device'];
        $screenX = $arrayData['screenX'];
        $screenY = $arrayData['screenY'];
        $coordX = $arrayData['coordX'];
        $coordY = $arrayData['coordY'];
        $timestamp = $arrayData['timestamp'];

        $data = [
            'event' => $event,
            'device' => $device,
            'screenX' => $screenX,
            'screenY' => $screenY,
            'coordX' => $coordX,
            'coordY' => $coordY,
            'timestamp' => $timestamp
        ];

        global $wpdb;
        $table = $wpdb->prefix . 'minheadmap_data';
        $criteria = array(
            'uuid' => UUID::v4(),
            'session_id' => $sessionId,
            'data' => json_encode($data));
        $formats = array('%s', '%s', '%s');
        $result = $wpdb->insert($table, $criteria, $formats);

        if (!$result) throw new Exception($wpdb->last_error);

        wp_send_json_success([
            'insert_id' => $wpdb->insert_id
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage()
        ], 500);
    }
}
require_once MINHEADMAP_PLUGIN_DIR . 'Backoffice/index.php';