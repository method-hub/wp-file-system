<?php

namespace Metapraxis\WPFileSystem\Setup;

use Metapraxis\WPFileSystem\Exceptions\FSConnectionException;

final class FSInitializer
{
    /**
     * @throws FSConnectionException
     */
    public static function initializeFileSystem(): void
    {
        global $wp_filesystem;

        if ($wp_filesystem && is_object($wp_filesystem) && empty($wp_filesystem->errors)) {
            return;
        }

        if (!function_exists('request_filesystem_credentials') || !function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if (is_admin()) {
            $credentials = request_filesystem_credentials('', '', false, WP_CONTENT_DIR);

            if (false === $credentials) {
                throw new FSConnectionException('Filesystem credentials were not provided.');
            }

            if (!WP_Filesystem($credentials, WP_CONTENT_DIR)) {
                throw new FSConnectionException(
                    'Failed to connect to the filesystem with the provided credentials.'
                );
            }
        }

        if (!is_admin()) {
            $credentials = [
                'hostname' => defined('FTP_HOST') ? constant('FTP_HOST') : '',
                'username' => defined('FTP_USER') ? constant('FTP_USER') : '',
                'password' => defined('FTP_PASS') ? constant('FTP_PASS') : '',
                'ssl' => defined('FTP_SSL') ? constant('FTP_SSL') : '',
                'port' => defined('FTP_PORT') ? constant('FTP_PORT') : '',
                'public_key' => defined('FTP_PUBKEY') ? constant('FTP_PUBKEY') : '',
                'private_key' => defined('FTP_PRIVKEY') ? constant('FTP_PRIVKEY') : '',
            ];

            if (!WP_Filesystem($credentials, WP_CONTENT_DIR, defined('FS_METHOD'))) {
                throw new FSConnectionException(
                    'Cannot initialize filesystem for non-admin requests. Use the "direct" method or define ' .
                    'filesystem credentials in wp-config.php. Read more about this in the documentation: ' .
                    'https://github.com/metapraxis/wp-file-system'
                );
            }
        }
    }
}
