<?php

namespace Metapraxis\WPFileSystem\Contracts;

use WP_Error;

/**
 * Defines the contract for all filesystem audit operations.
 *
 * This interface groups methods responsible for checking the state of files
 * and directories (existence, permissions, type) without modifying any data.
 *
 * @package Metapraxis\WPFileSystem\Contracts
 */
interface FSBaseAuditor extends FileSystem
{
    /**
     * Checks if a file or directory exists.
     *
     * @param string $path Path to the file or directory.
     *
     * @see WP_Filesystem_Base::exists()
     * @fires wpfs_before_exists_action
     * @fires wpfs_after_exists_action
     * @return bool Whether $path exists.
     */
    public function exists(string $path): bool;

    /**
     * Determines if a string contains binary characters.
     *
     * @param string $text The string to check.
     *
     * @see WP_Filesystem_Base::is_binary()
     * @fires wpfs_before_is_binary_action
     * @fires wpfs_after_is_binary_action
     * @return bool True if the string is binary, false otherwise.
     */
    public function isBinary(string $text): bool;

    /**
     * Checks if a resource is a file.
     *
     * @param string $path Path to the file.
     *
     * @see WP_Filesystem_Base::is_file()
     * @fires wpfs_before_is_file_action
     * @fires wpfs_after_is_file_action
     * @return bool True if it's a file, false otherwise.
     */
    public function isFile(string $path): bool;

    /**
     * Checks if a resource is a directory.
     *
     * @param string $path Path to the directory.
     *
     * @see WP_Filesystem_Base::is_dir()
     * @fires wpfs_before_is_directory_action
     * @fires wpfs_after_is_directory_action
     * @return bool True if it's a directory, false otherwise.
     */
    public function isDirectory(string $path): bool;

    /**
     * Checks if a file is readable.
     *
     * @param string $path Path to the file.
     *
     * @see WP_Filesystem_Base::is_readable()
     * @fires wpfs_before_is_readable_action
     * @fires wpfs_after_is_readable_action
     * @return bool True if the file is readable, false otherwise.
     */
    public function isReadable(string $path): bool;

    /**
     * Checks if a path is writable.
     *
     * @param string $path Path to the resource.
     *
     * @see WP_Filesystem_Base::is_writable()
     * @fires wpfs_before_is_writable_action
     * @fires wpfs_after_is_writable_action
     * @return bool True if the path is writable, false otherwise.
     */
    public function isWritable(string $path): bool;

    /**
     * Establishes a connection to the filesystem.
     *
     * @see WP_Filesystem_Base::connect()
     * @fires wpfs_before_connect_action
     * @fires wpfs_after_connect_action
     * @return bool True on success, false on failure.
     */
    public function connect(): bool;

    /**
     * Calculates and compares the MD5 hash of a file with an expected value.
     *
     * @param string $filename The name of the file to check.
     * @param string $expectedMd5 The expected MD5 hash.
     *
     * @see verify_file_md5
     * @fires wpfs_before_verify_md5_action
     * @fires wpfs_after_verify_md5_action
     * @return bool|WP_Error True on success, otherwise WP_Error.
     */
    public function verifyMd5(string $filename, string $expectedMd5);

    /**
     * Verifies the contents of a file against its ED25519 signature.
     *
     * @param string $filename File to check.
     * @param string|array $signatures Signature for the file.
     * @param string|false $filenameForErrors Optional. Filename to output in errors.
     *
     * @see verify_file_signature
     * @fires wpfs_before_verify_signature_action
     * @fires wpfs_after_verify_signature_action
     * @return bool|WP_Error True on success, otherwise WP_Error.
     */
    public function verifySignature(string $filename, $signatures, $filenameForErrors = false);

    /**
     * Determines if a given file is a valid ZIP archive.
     *
     * @param string $file Path to the file.
     *
     * @see wp_zip_file_is_valid
     * @fires wpfs_before_is_zip_file_action
     * @fires wpfs_after_is_zip_file_action
     * @return bool True if the file is valid, otherwise false.
     */
    public function isZipFile(string $file): bool;
}
