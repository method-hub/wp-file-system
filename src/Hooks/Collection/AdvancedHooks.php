<?php

namespace Metapraxis\WPFileSystem\Hooks\Collection;

/**
 * Trait containing hooks related to advanced operations.
 */
trait AdvancedHooks
{
    public static string $BEFORE_ATOMIC_WRITE_ACTION = 'wpfs_before_atomic_write_action';
    public static string $AFTER_ATOMIC_WRITE_ACTION = 'wpfs_after_atomic_write_action';

    public static string $BEFORE_APPEND_ACTION = 'wpfs_before_append_action';
    public static string $AFTER_APPEND_ACTION = 'wpfs_after_append_action';

    public static string $BEFORE_PREPEND_ACTION = 'wpfs_before_prepend_action';
    public static string $AFTER_PREPEND_ACTION = 'wpfs_after_prepend_action';

    public static string $BEFORE_REPLACE_ACTION = 'wpfs_before_replace_action';
    public static string $AFTER_REPLACE_ACTION = 'wpfs_after_replace_action';

    public static string $BEFORE_EXTENSION_ACTION = 'wpfs_before_extension_action';
    public static string $AFTER_EXTENSION_ACTION = 'wpfs_after_extension_action';
    public static string $EXTENSION_FILTER = 'wpfs_extension_filter';

    public static string $BEFORE_FILENAME_ACTION = 'wpfs_before_filename_action';
    public static string $AFTER_FILENAME_ACTION = 'wpfs_after_filename_action';
    public static string $FILENAME_FILTER = 'wpfs_filename_filter';

    public static string $BEFORE_DIRNAME_ACTION = 'wpfs_before_dirname_action';
    public static string $AFTER_DIRNAME_ACTION = 'wpfs_after_dirname_action';
    public static string $DIRNAME_FILTER = 'wpfs_dirname_filter';

    public static string $BEFORE_CLEAN_DIRECTORY_ACTION = 'wpfs_before_clean_directory_action';
    public static string $AFTER_CLEAN_DIRECTORY_ACTION = 'wpfs_after_clean_directory_action';

    public static string $BEFORE_IS_DIRECTORY_EMPTY_ACTION = 'wpfs_before_is_directory_empty_action';
    public static string $AFTER_IS_DIRECTORY_EMPTY_ACTION = 'wpfs_after_is_directory_empty_action';

    public static string $BEFORE_GET_MIME_TYPE_ACTION = 'wpfs_before_get_mime_type_action';
    public static string $AFTER_GET_MIME_TYPE_ACTION = 'wpfs_after_get_mime_type_action';
    public static string $GET_MIME_TYPE_FILTER = 'wpfs_get_mime_type_filter';

    public static string $BEFORE_HASH_ACTION = 'wpfs_before_hash_action';
    public static string $AFTER_HASH_ACTION = 'wpfs_after_hash_action';
    public static string $HASH_FILTER = 'wpfs_hash_filter';

    public static string $BEFORE_FILES_EQUAL_ACTION = 'wpfs_before_files_equal_action';
    public static string $AFTER_FILES_EQUAL_ACTION = 'wpfs_after_files_equal_action';

    public static string $BEFORE_READ_JSON_ACTION = 'wpfs_before_read_json_action';
    public static string $AFTER_READ_JSON_ACTION = 'wpfs_after_read_json_action';
    public static string $READ_JSON_FILTER = 'wpfs_read_json_filter';

    public static string $BEFORE_WRITE_JSON_ACTION = 'wpfs_before_write_json_action';
    public static string $AFTER_WRITE_JSON_ACTION = 'wpfs_after_write_json_action';

    public static string $BEFORE_READ_XML_ACTION = 'wpfs_before_read_xml_action';
    public static string $AFTER_READ_XML_ACTION = 'wpfs_after_read_xml_action';
    public static string $READ_XML_FILTER = 'wpfs_read_xml_filter';

    public static string $BEFORE_WRITE_XML_ACTION = 'wpfs_before_write_xml_action';
    public static string $AFTER_WRITE_XML_ACTION = 'wpfs_after_write_xml_action';

    public static string $BEFORE_READ_DOM_ACTION = 'wpfs_before_read_dom_action';
    public static string $AFTER_READ_DOM_ACTION = 'wpfs_after_read_dom_action';
    public static string $READ_DOM_FILTER = 'wpfs_read_dom_filter';

    public static string $BEFORE_WRITE_DOM_ACTION = 'wpfs_before_write_dom_action';
    public static string $AFTER_WRITE_DOM_ACTION = 'wpfs_after_write_dom_action';
}
