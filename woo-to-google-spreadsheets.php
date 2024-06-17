<?php

/**
 * Plugin Name: WooCommerce to Google Spreadsheets
 */

defined('ABSPATH') or die;

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

function add_order_to_spreadsheet($order_id) {
    // Initialize Google Client
    $client = new Client();
    $client->setApplicationName('WooCommerce to Spreadsheets');
    $client->setScopes(Sheets::SPREADSHEETS);

    // Set service account credentials
    $client->setAuthConfig(plugin_dir_path(__FILE__) . 'credentials.json');

    // Create Google Sheets service
    $service = new Sheets($client);

    // Spreadsheet ID and range
    $spreadsheetId = '';
    $range = ''; // Modify as per your sheet

    // Get the order object
    $order = wc_get_order($order_id);

    // Prepare values to insert
    $values = [];

    // Get items in the order
    $items = $order->get_items();

    // Loop through each item
    foreach ($items as $item) {
        // Get the product title
        $product_name = $item->get_name();

        $date = $order->get_date_created();
        $formatted_date = $date->format('d-m-Y');

        // Get the product price
        $product_price = $item->get_total(); // This gets the total price for the quantity of this item

        if (!str_contains($product_price, '.')) {
            $product_price = $product_price . '.00';
        }

        $values[] = [$formatted_date, $product_name, '€' . $product_price];
    }

    // Prepare ValueRange object
    $body = new ValueRange([
        'values' => $values
    ]);

    // Call the Sheets API to append values
    $result = $service->spreadsheets_values->append(
        $spreadsheetId,
        $range,
        $body,
        ['valueInputOption' => 'USER_ENTERED']
    );

    // Handle response or errors
    if ($result->getUpdates()->getUpdatedCells() > 0) {
        echo "Data appended successfully!";
    } else {
        echo "Failed to append data.";
    }
}
add_action('woocommerce_order_status_completed', 'add_order_to_spreadsheet');
