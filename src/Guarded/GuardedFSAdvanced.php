<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Assertion\Assertion;
use Metapraxis\WPFileSystem\Contracts\FSAdvanced;
use Metapraxis\WPFileSystem\Exceptions\FSException;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Exceptions\FSPermissionException;

/**
 * @extends GuardedFileSystem<FSAdvanced>
 */
class GuardedFSAdvanced extends GuardedFileSystem implements FSAdvanced
{
    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function atomicWrite(string $path, string $content, int $mode = null): bool
    {
        return Assertion::validateResourceOperation($this->fs->atomicWrite($path, $content, $mode), $path);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function append(string $path, string $content): bool
    {
        return Assertion::validateResourceOperation($this->fs->append($path, $content), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function prepend(string $path, string $content): bool
    {
        return Assertion::validateResourceOperation($this->fs->prepend($path, $content), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function replace(string $path, $search, $replace): bool
    {
        return Assertion::validateResourceOperation($this->fs->replace($path, $search, $replace), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function extension(string $path): string
    {
        return Assertion::validateResourceOperation($this->fs->extension($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function filename(string $path): string
    {
        return Assertion::validateResourceOperation($this->fs->filename($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function dirname(string $path): string
    {
        return Assertion::validateResourceOperation($this->fs->dirname($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function cleanDirectory(string $directory): bool
    {
        return Assertion::validateResourceOperation($this->fs->cleanDirectory($directory), $directory);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function isDirectoryEmpty(string $directory): bool
    {
        return Assertion::validateResourceOperation($this->fs->isDirectoryEmpty($directory), $directory);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function getMimeType(string $path)
    {
        return Assertion::validateResourceOperation($this->fs->getMimeType($path), $path);
    }

    /**
     * @throws FSPermissionException
     * @throws FSPathNotFoundException
     * @throws FSException
     */
    public function hash(string $path, string $algorithm = 'md5')
    {
        return Assertion::validateResourceOperation($this->fs->hash($path, $algorithm), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function filesEqual(string $path1, string $path2): bool
    {
        Assertion::ensureResourceExists($path2);

        return Assertion::validateResourceOperation($this->fs->filesEqual($path1, $path2), $path1);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function readJson(string $path, bool $assoc = true)
    {
        return Assertion::validateResourceOperation($this->fs->readJson($path, $assoc), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function writeJson(string $path, $data, int $options = JSON_PRETTY_PRINT): bool
    {
        return Assertion::validateResourceOperation($this->fs->writeJson($path, $data, $options), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function readXml(string $path)
    {
        return Assertion::validateResourceOperation($this->fs->readXml($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function writeXml(string $path, $xml): bool
    {
        return Assertion::validateResourceOperation($this->fs->writeXml($path, $xml), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function readDom(string $path)
    {
        return Assertion::validateResourceOperation($this->fs->readDom($path), $path);
    }

    /**
     * @throws FSPathNotFoundException
     * @throws FSPermissionException
     * @throws FSException
     */
    public function writeDom(string $path, $dom): bool
    {
        return Assertion::validateResourceOperation($this->fs->writeDom($path, $dom), $path);
    }
}
