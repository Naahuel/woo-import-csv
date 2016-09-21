<?php
/**
 * Register a custom menu page.
 */
function woo_csv_nombres_menu() {
    add_menu_page(
        'WooCommerce Nombres por SKU',
        'WooCommerce Nombres por SKU',
        'manage_options',
        'woo-csv-nombres/woo-csv-nombres-do.php'
    );
}

add_action( 'admin_menu', 'woo_csv_nombres_menu' );
