<?php

/**
 * Plugin Name: Metapraxis | WP File System
 * Plugin URI: https://github.com/method-hub/wp-file-system
 * Description: A safe and convenient object-oriented wrapper for the WordPress Filesystem API.
 * Version: 0.0.1
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Author: Metapraxis
 * Author URI: https://github.com/metapraxis
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: metapraxis-wp-file-system
 * Domain Path: /languages
 */

require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Setup\FSInitializer;

add_action('plugins_loaded', [FSInitializer::class, 'initializeFileSystem']);

/**
 * Load the E2E test bootstrap file if it's defined in the wp-env config.
 * This ensures our test-specific hooks are loaded only in the testing environment.
 */
if (defined('WP_TESTS_BOOTSTRAP') && file_exists(WP_TESTS_BOOTSTRAP)) {
    require_once WP_TESTS_BOOTSTRAP;
}
