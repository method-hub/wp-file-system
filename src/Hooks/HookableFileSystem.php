<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FileSystem;

/**
 * @template T of FileSystem
 * @property-read T $fs The wrapped filesystem instance.
 */
abstract class HookableFileSystem
{
    /**
     * @var FileSystem The filesystem instance.
     */
    protected FileSystem $fs;

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->fs = $fileSystem;
    }
}
