<?php

namespace Metapraxis\WPFileSystem\Adapters;

use Metapraxis\WPFileSystem\Contracts\FSBaseAuditor as BaseAuditor;
use WP_Error;

/**
 * @see tests/Unit/FSBaseAuditorTest.php
 */
class FSBaseAuditor extends FileSystem implements BaseAuditor
{
    public function isBinary(string $text): bool
    {
        return $this->wp_filesystem->is_binary($text);
    }

    public function isFile(string $path): bool
    {
        return $this->wp_filesystem->is_file($path);
    }

    public function isDirectory(string $path): bool
    {
        return $this->wp_filesystem->is_dir($path);
    }

    public function isReadable(string $path): bool
    {
        return $this->wp_filesystem->is_readable($path);
    }

    public function isWritable(string $path): bool
    {
        return $this->wp_filesystem->is_writable($path);
    }

    public function exists(string $path): bool
    {
        return $this->wp_filesystem->exists($path);
    }

    public function connect(): bool
    {
        return $this->wp_filesystem->connect();
    }

    public function verifyMd5(string $filename, string $expectedMd5)
    {
        $result = verify_file_md5($filename, $expectedMd5);

        return $result instanceof WP_Error ? false : $result;
    }

    public function verifySignature(string $filename, $signatures, $filenameForErrors = false)
    {
        $result = verify_file_signature($filename, $signatures, $filenameForErrors);

        return $result instanceof WP_Error ? false : $result;
    }

    public function isZipFile(string $file): bool
    {
        return wp_zip_file_is_valid($file);
    }
}
