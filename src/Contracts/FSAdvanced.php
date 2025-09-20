<?php

namespace Metapraxis\WPFileSystem\Contracts;

use DOMDocument;
use SimpleXMLElement;

/**
 * Provides advanced, high-level filesystem operations that are not available
 * in the standard WP_Filesystem API. This class aims to offer powerful
 * utilities similar to those found in modern PHP frameworks.
 *
 * @package Metapraxis\WPFileSystem\Adapters
 */
interface FSAdvanced extends FileSystem
{
    /**
     * Atomically writes content to a file.
     *
     * This method ensures that the file is always in a consistent state by writing
     * to a temporary file first and then renaming it. This prevents data
     * corruption if the write operation is interrupted.
     *
     * @param string $path The path to the file.
     * @param string $content The content to write.
     * @param int|null $mode Optional. The file permissions in octal format.
     *
     * @fires wpfs_before_atomic_write_action
     * @fires wpfs_after_atomic_write_action
     * @return bool True on success, false on failure.
     */
    public function atomicWrite(string $path, string $content, int $mode = null): bool;

    /**
     * Appends content to an existing file.
     *
     * @param string $path The path to the file.
     * @param string $content The content to append.
     *
     * @fires wpfs_before_append_action
     * @fires wpfs_after_append_action
     * @return bool True on success, false on failure.
     */
    public function append(string $path, string $content): bool;

    /**
     * Prepends content to an existing file.
     *
     * @param string $path The path to the file.
     * @param string $content The content to prepend.
     *
     * @fires wpfs_before_prepend_action
     * @fires wpfs_after_prepend_action
     * @return bool True on success, false on failure.
     */
    public function prepend(string $path, string $content): bool;

    /**
     * Replaces a string within a file.
     *
     * @param string $path The path to the file.
     * @param string|string[] $search The string or array of strings to search for.
     * @param string|string[] $replace The string or array of strings to replace with.
     *
     * @fires wpfs_before_replace_action
     * @fires wpfs_after_replace_action
     * @return bool True on success, false on failure.
     */
    public function replace(string $path, $search, $replace): bool;

    /**
     * Gets the file extension from a path.
     *
     * @param string $path The path to the file.
     *
     * @fires wpfs_before_extension_action
     * @fires wpfs_after_extension_action
     * @fires wpfs_extension_filter
     * @return string The file extension (without the dot).
     */
    public function extension(string $path): string;

    /**
     * Gets the file name from a path, without the extension.
     *
     * @param string $path The path to the file.
     *
     * @fires wpfs_before_filename_action
     * @fires wpfs_after_filename_action
     * @fires wpfs_filename_filter
     * @return string The file name.
     */
    public function filename(string $path): string;

    /**
     * Gets the directory name from a path.
     *
     * @param string $path The path to the file or directory.
     *
     * @fires wpfs_before_dirname_action
     * @fires wpfs_after_dirname_action
     * @fires wpfs_dirname_filter
     * @return string The directory name.
     */
    public function dirname(string $path): string;

    /**
     * Deletes all files and subdirectories within a directory, leaving the directory itself.
     *
     * @param string $directory The path to the directory to clean.
     *
     * @fires wpfs_before_clean_directory_action
     * @fires wpfs_after_clean_directory_action
     * @return bool True on success, false if any deletion fails.
     */
    public function cleanDirectory(string $directory): bool;

    /**
     * Checks if a directory is empty.
     *
     * @param string $directory The path to the directory.
     *
     * @fires wpfs_before_is_directory_empty_action
     * @fires wpfs_after_is_directory_empty_action
     * @return bool True if the directory is empty or does not exist, false otherwise.
     */
    public function isDirectoryEmpty(string $directory): bool;

    /**
     * Gets the MIME type of file.
     *
     * @param string $path The path to the file.
     *
     * @fires wpfs_before_get_mime_type_action
     * @fires wpfs_after_get_mime_type_action
     * @fires wpfs_get_mime_type_filter
     * @return string|false The MIME type or false on failure.
     */
    public function getMimeType(string $path);

    /**
     * Gets the hash of a file's content.
     *
     * @param string $path The path to the file.
     * @param string $algorithm The hashing algorithm (e.g., "md5", "sha256").
     *
     * @fires wpfs_before_hash_action
     * @fires wpfs_after_hash_action
     * @fires wpfs_hash_filter
     * @return string|false The hash or false on failure.
     */
    public function hash(string $path, string $algorithm = 'md5');

    /**
     * Compares two files to see if their contents are identical.
     *
     * @param string $path1 The path to the first file.
     * @param string $path2 The path to the second file.
     *
     * @fires wpfs_before_files_equal_action
     * @fires wpfs_after_files_equal_action
     * @return bool True if files are equal, false otherwise.
     */
    public function filesEqual(string $path1, string $path2): bool;

    /**
     * Reads and decodes a JSON file.
     *
     * @param string $path The path to the JSON file.
     * @param bool $assoc When true, returns an associative array; otherwise, an object.
     *
     * @fires wpfs_before_read_json_action
     * @fires wpfs_after_read_json_action
     * @fires wpfs_read_json_filter
     * @return mixed The decoded data, or false on failure (file not found or invalid JSON).
     */
    public function readJson(string $path, bool $assoc = true);

    /**
     * Encodes data to JSON and writes it to a file.
     *
     * @param string $path The path to the file.
     * @param mixed $data The data to encode.
     * @param int $options Options for `json_encode` (e.g., JSON_PRETTY_PRINT).
     *
     * @fires wpfs_before_write_json_action
     * @fires wpfs_after_write_json_action
     * @return bool True on success, false on failure.
     */
    public function writeJson(string $path, $data, int $options = JSON_PRETTY_PRINT): bool;

    /**
     * Read XML from a file and return a SimpleXMLElement object.
     *
     * @param string $path The path to the XML file.
     * @return SimpleXMLElement|false A SimpleXMLElement object on success, or false on error.
     */
    public function readXml(string $path);

    /**
     * Write XML-compatible data to a file.
     *
     * Accepts a DOMDocument object, a SimpleXMLElement object, or an XML string.
     *
     * @param string $path The path to the file.
     * @param DOMDocument|SimpleXMLElement|string $xml  The XML data to write.
     * @return bool True on success, false on failure.
     */
    public function writeXml(string $path, $xml): bool;

    /**
     * Read XML from a file and return a DOMDocument object.
     *
     * @param string $path The path to the XML file.
     * @return DOMDocument|false A DOMDocument object on success, or false on error.
     */
    public function readDom(string $path);

    /**
     * Write XML-compatible data to a file. This method is an alias for writeXml().
     *
     * @param string $path The path to the file.
     * @param DOMDocument|SimpleXMLElement|string $dom The XML data to write.
     * @return bool True on success, false on failure.
     * @see self::writeXml()
     */
    public function writeDom(string $path, $dom): bool;
}
