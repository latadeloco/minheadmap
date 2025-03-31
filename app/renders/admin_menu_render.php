<?php

use MinHeadmap\Report\Infrastructure\Bin\ReportProvider;

function minheadmap_bcko_menu(): void {
    add_menu_page(
        __( 'Minheadmap Reporting', 'textdomain' ),
        __( 'Minheadmap Reporting', 'textdomain' ),
        'manage_options',
        'minheadmap-menu',
        'render',
        'dashicons-admin-site-alt',
        6
    );
}
add_action( 'admin_menu', 'minheadmap_bcko_menu');

function render() {
    $data = new ReportProvider();
    $controller = $data->bind();
    $result = ($controller)('iphone', 1);

    $generalStyle = plugins_url('../../Backoffice/css/general.css', __FILE__);
    $header = __('Reporting', 'textdomain');
    $data = [
        'byDevice' => [
            'h2' => 'Iphone touchs',
            'theads' => [
                'Session ID',
                'Event type',
                'Coordinates',
                'Screen size',
                'Datetime'
            ],
        'results' => [$result['registers']],
        'total' => $result['total'],
        ]
    ];

    include plugin_dir_path(__FILE__) . '../../Backoffice/views/admin_menu.php';
}