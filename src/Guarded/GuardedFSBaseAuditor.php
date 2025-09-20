<?php

namespace Metapraxis\WPFileSystem\Guarded;

use Metapraxis\WPFileSystem\Assertion\Assertion;
use Metapraxis\WPFileSystem\Contracts\FSBaseAuditor;
use Metapraxis\WPFileSystem\Exceptions\FSException;

/**
 * @extends GuardedFileSystem<FSBaseAuditor>
 */
class GuardedFSBaseAuditor extends GuardedFileSystem implements FSBaseAuditor
{
    /**
     * @throws FSException
     */
    public function isBinary(string $text): bool
    {
        return Assertion::ensureSuccessful($this->fs->isBinary($text));
    }

    /**
     * @throws FSException
     */
    public function isFile(string $path): bool
    {
        return Assertion::ensureSuccessful($this->fs->isFile($path));
    }

    /**
     * @throws FSException
     */
    public function isDirectory(string $path): bool
    {
        return Assertion::ensureSuccessful($this->fs->isDirectory($path));
    }

    /**
     * @throws FSException
     */
    public function isReadable(string $path): bool
    {
        return Assertion::ensureSuccessful($this->fs->isReadable($path));
    }

    /**
     * @throws FSException
     */
    public function isWritable(string $path): bool
    {
        return Assertion::ensureSuccessful($this->fs->isWritable($path));
    }

    /**
     * @throws FSException
     */
    public function exists(string $path): bool
    {
        return Assertion::ensureSuccessful($this->fs->exists($path));
    }

    /**
     * @throws FSException
     */
    public function connect(): bool
    {
        return Assertion::ensureSuccessful($this->fs->connect());
    }

    /**
     * @throws FSException
     */
    public function verifyMd5(string $filename, string $expectedMd5)
    {
        return Assertion::ensureSuccessful($this->fs->verifyMd5($filename, $expectedMd5));
    }

    /**
     * @throws FSException
     */
    public function verifySignature(string $filename, $signatures, $filenameForErrors = false)
    {
        return Assertion::ensureSuccessful($this->fs->verifySignature($filename, $signatures, $filenameForErrors));
    }

    /**
     * @throws FSException
     */
    public function isZipFile(string $file): bool
    {
        return Assertion::ensureSuccessful($this->fs->isZipFile($file));
    }
}
