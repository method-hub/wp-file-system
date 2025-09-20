<?php

namespace Metapraxis\WPFileSystem\Hooks\Collection;

/**
 * Trait containing hooks related to read operations.
 */
trait ReadingHooks
{
    public static string $BEFORE_GET_HOME_PATH_ACTION = 'wpfs_before_get_home_path_action';
    public static string $AFTER_GET_HOME_PATH_ACTION = 'wpfs_after_get_home_path_action';
    public static string $GET_HOME_PATH_FILTER = 'wpfs_get_home_path_filter';

    public static string $BEFORE_GET_INSTALLATION_PATH_ACTION = 'wpfs_before_get_installation_path_action';
    public static string $AFTER_GET_INSTALLATION_PATH_ACTION = 'wpfs_after_get_installation_path_action';
    public static string $GET_INSTALLATION_PATH_FILTER = 'wpfs_get_installation_path_filter';

    public static string $BEFORE_GET_CONTENT_PATH_ACTION = 'wpfs_before_get_content_path_action';
    public static string $AFTER_GET_CONTENT_PATH_ACTION = 'wpfs_after_get_content_path_action';
    public static string $GET_CONTENT_PATH_FILTER = 'wpfs_get_content_path_filter';

    public static string $BEFORE_GET_PLUGINS_PATH_ACTION = 'wpfs_before_get_plugins_path_action';
    public static string $AFTER_GET_PLUGINS_PATH_ACTION = 'wpfs_after_get_plugins_path_action';
    public static string $GET_PLUGINS_PATH_FILTER = 'wpfs_get_plugins_path_filter';

    public static string $BEFORE_GET_THEMES_PATH_ACTION = 'wpfs_before_get_themes_path_action';
    public static string $AFTER_GET_THEMES_PATH_ACTION = 'wpfs_after_get_themes_path_action';
    public static string $GET_THEMES_PATH_FILTER = 'wpfs_get_themes_path_filter';

    public static string $BEFORE_GET_LANG_PATH_ACTION = 'wpfs_before_get_lang_path_action';
    public static string $AFTER_GET_LANG_PATH_ACTION = 'wpfs_after_get_lang_path_action';
    public static string $GET_LANG_PATH_FILTER = 'wpfs_get_lang_path_filter';

    public static string $BEFORE_GET_HUMAN_READABLE_PERMISSIONS_ACTION =
        'wpfs_before_get_human_readable_permissions_action';
    public static string $AFTER_GET_HUMAN_READABLE_PERMISSIONS_ACTION =
        'wpfs_after_get_human_readable_permissions_action';
    public static string $GET_HUMAN_READABLE_PERMISSIONS_FILTER = 'wpfs_get_human_readable_permissions_filter';

    public static string $BEFORE_GET_PERMISSIONS_ACTION = 'wpfs_before_get_permissions_action';
    public static string $AFTER_GET_PERMISSIONS_ACTION = 'wpfs_after_get_permissions_action';
    public static string $GET_PERMISSIONS_FILTER = 'wpfs_get_permissions_filter';

    public static string $BEFORE_GET_CONTENTS_ACTION = 'wpfs_before_get_contents_action';
    public static string $AFTER_GET_CONTENTS_ACTION = 'wpfs_after_get_contents_action';
    public static string $GET_CONTENTS_FILTER = 'wpfs_get_contents_filter';

    public static string $BEFORE_GET_CONTENTS_AS_ARRAY_ACTION = 'wpfs_before_get_contents_as_array_action';
    public static string $AFTER_GET_CONTENTS_AS_ARRAY_ACTION = 'wpfs_after_get_contents_as_array_action';
    public static string $GET_CONTENTS_AS_ARRAY_FILTER = 'wpfs_get_contents_as_array_filter';

    public static string $BEFORE_GET_CURRENT_PATH_ACTION = 'wpfs_before_get_current_path_action';
    public static string $AFTER_GET_CURRENT_PATH_ACTION = 'wpfs_after_get_current_path_action';
    public static string $GET_CURRENT_PATH_FILTER = 'wpfs_get_current_path_filter';

    public static string $BEFORE_GET_OWNER_ACTION = 'wpfs_before_get_owner_action';
    public static string $AFTER_GET_OWNER_ACTION = 'wpfs_after_get_owner_action';
    public static string $GET_OWNER_FILTER = 'wpfs_get_owner_filter';

    public static string $BEFORE_GET_GROUP_ACTION = 'wpfs_before_get_group_action';
    public static string $AFTER_GET_GROUP_ACTION = 'wpfs_after_get_group_action';
    public static string $GET_GROUP_FILTER = 'wpfs_get_group_filter';

    public static string $BEFORE_GET_LAST_ACCESSED_TIME_ACTION = 'wpfs_before_get_last_accessed_time_action';
    public static string $AFTER_GET_LAST_ACCESSED_TIME_ACTION = 'wpfs_after_get_last_accessed_time_action';
    public static string $GET_LAST_ACCESSED_TIME_FILTER = 'wpfs_get_last_accessed_time_filter';

    public static string $BEFORE_GET_LAST_MODIFIED_TIME_ACTION = 'wpfs_before_get_last_modified_time_action';
    public static string $AFTER_GET_LAST_MODIFIED_TIME_ACTION = 'wpfs_after_get_last_modified_time_action';
    public static string $GET_LAST_MODIFIED_TIME_FILTER = 'wpfs_get_last_modified_time_filter';

    public static string $BEFORE_GET_FILE_SIZE_ACTION = 'wpfs_before_get_file_size_action';
    public static string $AFTER_GET_FILE_SIZE_ACTION = 'wpfs_after_get_file_size_action';
    public static string $GET_FILE_SIZE_FILTER = 'wpfs_get_file_size_filter';

    public static string $BEFORE_GET_PERMISSIONS_AS_OCTAL_ACTION = 'wpfs_before_get_permissions_as_octal_action';
    public static string $AFTER_GET_PERMISSIONS_AS_OCTAL_ACTION = 'wpfs_after_get_permissions_as_octal_action';
    public static string $GET_PERMISSIONS_AS_OCTAL_FILTER = 'wpfs_get_permissions_as_octal_filter';

    public static string $BEFORE_GET_DIRECTORY_LIST_ACTION = 'wpfs_before_get_directory_list_action';
    public static string $AFTER_GET_DIRECTORY_LIST_ACTION = 'wpfs_after_get_directory_list_action';
    public static string $GET_DIRECTORY_LIST_FILTER = 'wpfs_get_directory_list_filter';

    public static string $BEFORE_GET_FILES_ACTION = 'wpfs_before_get_files_action';
    public static string $AFTER_GET_FILES_ACTION = 'wpfs_after_get_files_action';
    public static string $GET_FILES_FILTER = 'wpfs_get_files_filter';

    public static string $BEFORE_GET_UPLOADS_DIR_INFO_ACTION = 'wpfs_before_get_uploads_dir_info_action';
    public static string $AFTER_GET_UPLOADS_DIR_INFO_ACTION = 'wpfs_after_get_uploads_dir_info_action';
    public static string $GET_UPLOADS_DIR_INFO_FILTER = 'wpfs_get_uploads_dir_info_filter';

    public static string $BEFORE_GET_TEMP_DIR_ACTION = 'wpfs_before_get_temp_dir_action';
    public static string $AFTER_GET_TEMP_DIR_ACTION = 'wpfs_after_get_temp_dir_action';
    public static string $GET_TEMP_DIR_FILTER = 'wpfs_get_temp_dir_filter';

    public static string $BEFORE_GET_NORMALIZE_PATH_ACTION = 'wpfs_before_get_normalize_path_action';
    public static string $AFTER_GET_NORMALIZE_PATH_ACTION = 'wpfs_after_get_normalize_path_action';
    public static string $GET_NORMALIZE_PATH_FILTER = 'wpfs_get_normalize_path_filter';

    public static string $BEFORE_GET_SANITIZE_FILENAME_ACTION = 'wpfs_before_get_sanitize_filename_action';
    public static string $AFTER_GET_SANITIZE_FILENAME_ACTION = 'wpfs_after_get_sanitize_filename_action';
    public static string $GET_SANITIZE_FILENAME_FILTER = 'wpfs_get_sanitize_filename_filter';

    public static string $BEFORE_FIND_FOLDER_ACTION = 'wpfs_before_find_folder_action';
    public static string $AFTER_FIND_FOLDER_ACTION = 'wpfs_after_find_folder_action';
    public static string $FIND_FOLDER_FILTER = 'wpfs_find_folder_filter';

    public static string $BEFORE_SEARCH_FOR_FOLDER_ACTION = 'wpfs_before_search_for_folder_action';
    public static string $AFTER_SEARCH_FOR_FOLDER_ACTION = 'wpfs_after_search_for_folder_action';
    public static string $SEARCH_FOR_FOLDER_FILTER = 'wpfs_search_for_folder_filter';
}
