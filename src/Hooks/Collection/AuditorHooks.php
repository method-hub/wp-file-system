<?php

namespace Metapraxis\WPFileSystem\Hooks\Collection;

/**
 * Trait containing hooks related to auditor operations.
 */
trait AuditorHooks
{
    public static string $BEFORE_EXISTS_ACTION = 'wpfs_before_exists_action';
    public static string $AFTER_EXISTS_ACTION = 'wpfs_after_exists_action';

    public static string $BEFORE_IS_BINARY_ACTION = 'wpfs_before_is_binary_action';
    public static string $AFTER_IS_BINARY_ACTION = 'wpfs_after_is_binary_action';

    public static string $BEFORE_IS_FILE_ACTION = 'wpfs_before_is_file_action';
    public static string $AFTER_IS_FILE_ACTION = 'wpfs_after_is_file_action';

    public static string $BEFORE_IS_DIRECTORY_ACTION = 'wpfs_before_is_directory_action';
    public static string $AFTER_IS_DIRECTORY_ACTION = 'wpfs_after_is_directory_action';

    public static string $BEFORE_IS_READABLE_ACTION = 'wpfs_before_is_readable_action';
    public static string $AFTER_IS_READABLE_ACTION = 'wpfs_after_is_readable_action';

    public static string $BEFORE_IS_WRITABLE_ACTION = 'wpfs_before_is_writable_action';
    public static string $AFTER_IS_WRITABLE_ACTION = 'wpfs_after_is_writable_action';

    public static string $BEFORE_CONNECT_ACTION = 'wpfs_before_connect_action';
    public static string $AFTER_CONNECT_ACTION = 'wpfs_after_connect_action';

    public static string $BEFORE_VERIFY_MD5_ACTION = 'wpfs_before_verify_md5_action';
    public static string $AFTER_VERIFY_MD5_ACTION = 'wpfs_after_verify_md5_action';

    public static string $BEFORE_VERIFY_SIGNATURE_ACTION = 'wpfs_before_verify_signature_action';
    public static string $AFTER_VERIFY_SIGNATURE_ACTION = 'wpfs_after_verify_signature_action';

    public static string $BEFORE_IS_ZIP_FILE_ACTION = 'wpfs_before_is_zip_file_action';
    public static string $AFTER_IS_ZIP_FILE_ACTION = 'wpfs_after_is_zip_file_action';
}
