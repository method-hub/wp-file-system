<?php

namespace Metapraxis\WPFileSystem\Hooks\Collection;

/**
 * Trait containing hooks related to management operations.
 */
trait ManagerHooks
{
    public static string $BEFORE_ENSURE_UNIQUE_FILENAME_ACTION = 'wpfs_before_ensure_unique_filename_action';
    public static string $AFTER_ENSURE_UNIQUE_FILENAME_ACTION = 'wpfs_after_ensure_unique_filename_action';
    public static string $ENSURE_UNIQUE_FILENAME_FILTER = 'wpfs_ensure_unique_filename_filter';

    public static string $BEFORE_SET_GROUP_ACTION = 'wpfs_before_set_group_action';
    public static string $AFTER_SET_GROUP_ACTION = 'wpfs_after_set_group_action';

    public static string $BEFORE_SET_PERMISSIONS_ACTION = 'wpfs_before_set_permissions_action';
    public static string $AFTER_SET_PERMISSIONS_ACTION = 'wpfs_after_set_permissions_action';

    public static string $BEFORE_SET_OWNER_ACTION = 'wpfs_before_set_owner_action';
    public static string $AFTER_SET_OWNER_ACTION = 'wpfs_after_set_owner_action';

    public static string $BEFORE_SET_CURRENT_DIRECTORY_ACTION = 'wpfs_before_set_current_directory_action';
    public static string $AFTER_SET_CURRENT_DIRECTORY_ACTION = 'wpfs_after_set_current_directory_action';

    public static string $BEFORE_INVALIDATE_OP_CACHE_ACTION = 'wpfs_before_invalidate_op_cache_action';
    public static string $AFTER_INVALIDATE_OP_CACHE_ACTION = 'wpfs_after_invalidate_op_cache_action';

    public static string $BEFORE_INVALIDATE_DIRECTORY_OP_CACHE_ACTION =
        'wpfs_before_invalidate_directory_op_cache_action';
    public static string $AFTER_INVALIDATE_DIRECTORY_OP_CACHE_ACTION =
        'wpfs_after_invalidate_directory_op_cache_action';
}
