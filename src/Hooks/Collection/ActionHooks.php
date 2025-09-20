<?php

namespace Metapraxis\WPFileSystem\Hooks\Collection;

/**
 * Trait containing hooks related to action operations (write, delete, etc.).
 */
trait ActionHooks
{
    public static string $BEFORE_PUT_CONTENTS_ACTION = 'wpfs_before_put_contents_action';
    public static string $AFTER_PUT_CONTENTS_ACTION = 'wpfs_after_put_contents_action';

    public static string $BEFORE_COPY_FILE_ACTION = 'wpfs_before_copy_file_action';
    public static string $AFTER_COPY_FILE_ACTION = 'wpfs_after_copy_file_action';

    public static string $BEFORE_COPY_DIRECTORY_ACTION = 'wpfs_before_copy_directory_action';
    public static string $AFTER_COPY_DIRECTORY_ACTION = 'wpfs_after_copy_directory_action';

    public static string $BEFORE_MOVE_FILE_ACTION = 'wpfs_before_move_file_action';
    public static string $AFTER_MOVE_FILE_ACTION = 'wpfs_after_move_file_action';

    public static string $BEFORE_MOVE_DIRECTORY_ACTION = 'wpfs_before_move_directory_action';
    public static string $AFTER_MOVE_DIRECTORY_ACTION = 'wpfs_after_move_directory_action';

    public static string $BEFORE_DELETE_ACTION = 'wpfs_before_delete_action';
    public static string $AFTER_DELETE_ACTION = 'wpfs_after_delete_action';

    public static string $BEFORE_TOUCH_ACTION = 'wpfs_before_touch_action';
    public static string $AFTER_TOUCH_ACTION = 'wpfs_after_touch_action';

    public static string $BEFORE_CREATE_DIRECTORY_ACTION = 'wpfs_before_create_directory_action';
    public static string $AFTER_CREATE_DIRECTORY_ACTION = 'wpfs_after_create_directory_action';

    public static string $BEFORE_CREATE_TEMP_FILE_ACTION = 'wpfs_before_create_temp_file_action';
    public static string $AFTER_CREATE_TEMP_FILE_ACTION = 'wpfs_after_create_temp_file_action';
    public static string $CREATE_TEMP_FILE_FILTER = 'wpfs_create_temp_file_filter';

    public static string $BEFORE_DELETE_DIRECTORY_ACTION = 'wpfs_before_delete_directory_action';
    public static string $AFTER_DELETE_DIRECTORY_ACTION = 'wpfs_after_delete_directory_action';

    public static string $BEFORE_HANDLE_UPLOAD_ACTION = 'wpfs_before_handle_upload_action';
    public static string $AFTER_HANDLE_UPLOAD_ACTION = 'wpfs_after_handle_upload_action';
    public static string $HANDLE_UPLOAD_FILTER = 'wpfs_handle_upload_filter';

    public static string $BEFORE_HANDLE_SIDELOAD_ACTION = 'wpfs_before_handle_sideload_action';
    public static string $AFTER_HANDLE_SIDELOAD_ACTION = 'wpfs_after_handle_sideload_action';
    public static string $HANDLE_SIDELOAD_FILTER = 'wpfs_handle_sideload_filter';

    public static string $BEFORE_DOWNLOAD_FROM_URL_ACTION = 'wpfs_before_download_from_url_action';
    public static string $AFTER_DOWNLOAD_FROM_URL_ACTION = 'wpfs_after_download_from_url_action';
    public static string $DOWNLOAD_FROM_URL_FILTER = 'wpfs_download_from_url_filter';

    public static string $BEFORE_UNZIP_ACTION = 'wpfs_before_unzip_action';
    public static string $AFTER_UNZIP_ACTION = 'wpfs_after_unzip_action';
}
