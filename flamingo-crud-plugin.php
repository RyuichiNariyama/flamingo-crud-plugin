<?php
/*
Plugin Name: Flamingo CRUD Plugin
Description: Custom admin page to view Flamingo messages and perform CRUD operations.
Version: 0.1.0
Author: YourName
*/

if (!defined('ABSPATH')) exit;

class FlamingoCrudPlugin {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('rest_api_init', [$this, 'register_api']);
    }

    // 管理画面メニュー追加
    public function register_menu() {
        add_menu_page(
            'Flamingo Messages',
            'Flamingo CRUD',
            'manage_options',
            'flamingo-crud',
            [$this, 'admin_page'],
            'dashicons-edit'
        );
    }

    // 管理画面 HTML
    public function admin_page() {
        echo '<div id="flamingo-crud-app"></div>';
        wp_enqueue_script(
            'flamingo-crud-app',
            plugin_dir_url(__FILE__) . 'admin/app.js',
            ['wp-element'],
            filemtime(plugin_dir_path(__FILE__) . 'admin/app.js'),
            true
        );
    }

    // REST API
    public function register_api() {
        register_rest_route('flamingo-crud/v1', '/messages', [
            'methods' => 'GET',
            'callback' => [$this, 'get_messages'],
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ]);
    }

    // メッセージ取得（一覧のみ）
    public function get_messages() {
        global $wpdb;

        $table = $wpdb->prefix . 'flamingo_inbound';
        $results = $wpdb->get_results("SELECT id, subject, from_name, from_email, created_at FROM $table ORDER BY created_at DESC LIMIT 50");

        return $results;
    }
}

new FlamingoCrudPlugin();

