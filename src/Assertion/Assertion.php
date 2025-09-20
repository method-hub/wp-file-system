<?php

namespace Metapraxis\WPFileSystem\Assertion;

use Metapraxis\WPFileSystem\Exceptions\FSException;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Exceptions\FSPermissionException;
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use WP_Error;

/**
 * A utility class for centralized validation of filesystem operations.
 *
 * Provides a set of static methods to check function execution results,
 * diagnose errors, and ensure preconditions are met. The class behavior
 * can be globally enabled or disabled using the setAsserting() method.
 */
final class Assertion
{
    /**
     * Validates the result of an operation on a specific file resource.
     *
     * If assertions are enabled, this method performs a "lazy" diagnosis: it only triggers
     * if the operation result ($result) is "falsy" (false, WP_Error, etc.).
     * In that case, it attempts to determine the cause of failure: resource not found or a permission issue.
     *
     * @param mixed $result The result of the file operation.
     * @param object|string|null $resource The path to the file or directory that was being operated on.
     *
     * @return mixed The original result ($result) if the operation was successful or assertions are disabled.
     * @throws FSPathNotFoundException If the diagnosis indicates the resource does not exist.
     * @throws FSPermissionException If the resource exists but the operation failed (assumed to be a permission issue).
     * @throws FSException If the result is a WP_Error object or contains an 'error' key.
     */
    public static function validateResourceOperation($result, $resource)
    {
        if (!FSGuardiansProvider::getStatus()) {
            return $result;
        }

        if ($result instanceof WP_Error) {
            throw new FSException($result->get_error_message());
        }

        if (isset($resource['error']) && $resource['error']) {
            throw new FSException($resource['error']);
        }

        if ($result) {
            return $result;
        }

        global $wp_filesystem;

        if ($wp_filesystem->exists($resource)) {
            throw new FSPermissionException("Permission denied for $resource");
        }

        throw new FSPathNotFoundException("Path or file $resource not found");
    }

    /**
     * Ensures that an operation completed successfully.
     *
     * This method is used for operations where the specific resource is not important,
     * but the success of the operation itself is (e.g., connecting to the FS).
     * It throws a generic exception for any "falsy" result.
     *
     * @param mixed $result The result of the operation.
     *
     * @return mixed The original result ($result) if it is not an error.
     * @throws FSException If the operation result is false, null, a WP_Error, or contains an 'error' key.
     */
    public static function ensureSuccessful($result)
    {
        if (!FSGuardiansProvider::getStatus()) {
            return $result;
        }

        if ($result instanceof WP_Error) {
            throw new FSException($result->get_error_message());
        }

        if (isset($result['error']) && $result['error']) {
            throw new FSException($result['error']);
        }

        if ($result) {
            return $result;
        }

        throw new FSException("Operation failed without a specific error message.");
    }

    /**
     * Checks for a resource's existence as a precondition for another operation.
     *
     * This is used for "void" methods or before operations that implicitly
     * assume a file or directory already exists.
     *
     * @param string $resource The path to the file or directory.
     *
     * @throws FSPathNotFoundException If the resource does not exist and assertions are enabled.
     */
    public static function ensureResourceExists(string $resource): void
    {
        global $wp_filesystem;

        if (FSGuardiansProvider::getStatus() && !$wp_filesystem->exists($resource)) {
            throw new FSPathNotFoundException("Path or file $resource not found");
        }
    }
}
