<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Contracts\FileSystem;

/**
 * @template T of FileSystem
 * @property-read T $fs The wrapped filesystem instance.
 */
abstract class GuardedFileSystem
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
