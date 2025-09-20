<?php

namespace Metapraxis\WPFileSystem\Adapters;

use WP_Filesystem_Base;

abstract class FileSystem
{
    protected WP_Filesystem_Base $wp_filesystem;

    public function __construct(WP_Filesystem_Base $wp_filesystem)
    {
        $this->wp_filesystem = $wp_filesystem;
    }
}
