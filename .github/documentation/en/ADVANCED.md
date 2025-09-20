# Advanced Usage of WP File System

This documentation is intended for developers who want to gain a deeper understanding of how the WP File System library
works and use its advanced features such as hooks, protected mode, and manual service instance creation.

## WordPress File System Basics and Access Methods

To understand the full power of the WPFS library, it's important to know what problem it solves at the lowest level.
WordPress uses an abstraction for file access — WP_Filesystem — to ensure security and compatibility with various
hosting environments. This abstraction can work in several modes (methods).

| Method       | Description                                                                                                                                                           | When to Use                                                                                                                      |
|:-------------|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------|:---------------------------------------------------------------------------------------------------------------------------------|
| `direct`     | The simplest and fastest method. File operations are performed directly using PHP functions (fopen, fwrite, etc.) under the web server user account (e.g., www-data). | Used by default on properly configured servers where the web server has write permissions to the required WordPress directories. |
| `ssh2`       | Operations are performed through a secure SSH connection to the server using provided credentials.                                                                    | Used on servers where the web server doesn't have direct write permissions but has SSH access. Requires the PHP ssh2 extension.  |
| `ftpext`     | Operations are performed via FTP using credentials of an FTP user who has write permissions.                                                                          | Common method on virtual hosting. Requires the PHP ftp extension.                                                                |
| `ftpsockets` | Alternative implementation of FTP client using pure PHP sockets. Used when the ftp extension is not available.                                                        | Fallback option for FTP that doesn't require additional PHP extensions.                                                          |

### Problems and Their Solution with WPFS

- Permission problem: If using `direct` method on a server where `www-data` doesn't have write permissions to the
  `wp-content/uploads` folder, any attempt to save a file will fail.
- Credentials problem: Methods `ssh2` and `ftp*` require credentials. WordPress usually requests them from the user
  through a form in the admin panel. This makes it impossible to perform file operations in the background (e.g., via
  CRON) or through API.

### How WPFS Solves This:

1. Automatic initialization: WPFS automatically initializes WP_Filesystem, eliminating the need to do it manually.
2. Using constants: WPFS relies on credentials being defined as constants in `wp-config.php` for non-interactive
   operations (CRON, WP-CLI). This is a standard WordPress practice.

### Configuration in wp-config.php

You can force the access method and specify the credentials in the `wp-config.php` file.

```php
// Force filesystem method
// Possible values: 'direct', 'ssh2', 'ftpext', 'ftpsockets'
define('FS_METHOD', 'direct');

// MAIN CREDENTIALS (for FTP, FTPS, SSH2)
define('FTP_HOST', 'your-server.com');
define('FTP_USER', 'username');
define('FTP_PASS', 'password');
define('FTP_PORT', '22'); // Port 22 for SSH, 21 for FTP

// CONNECTION SETTINGS
define('FTP_SSL', false); // true for FTPS (FTP methods only)

// SSH KEYS (alternative to password for SSH2)
define('FTP_PUBKEY', '/home/user/.ssh/id_rsa.pub');
define('FTP_PRIKEY', '/home/user/.ssh/id_rsa');

// DEFAULT ACCESS PERMISSIONS
define('FS_CHMOD_DIR', 0755);
define('FS_CHMOD_FILE', 0644);
```

## Decorator Providers: Hooks and Guardian

By default, FSFactory creates basic service instances that simply call corresponding WordPress functions.
However, you can globally enable two powerful decorators: FSHooksProvider and FSGuardiansProvider.

### FSGuardiansProvider (Guardian)

Purpose: Switches error handling mode. By default, according to WordPress API, most methods simply return `false` in
case of failure. This can make debugging difficult. When "Guardian" is enabled, typed exceptions will be thrown instead
of `false`.

- `FSPathNotFoundException`: Resource (file or directory) not found.
- `FSPermissionException`: Resource exists, but insufficient permissions for the operation.
- `FSException`: General filesystem error.

### Как использовать:

```php
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;

// Enabling exception mode
FSGuardiansProvider::setStatus(true);

try {
    $content = WPFS::getContents('/path/to/non-existent-file.txt');
} catch (FSPathNotFoundException $e) {
    wp_log_error($e->getMessage());
} finally {
    // You can disable it, that is, use it as a temporary effect,
    // or not disable it and thereby introduce a global state.
    FSGuardiansProvider::setStatus(false);
}
```

### FSHooksProvider (Hooks)

Purpose: Adds the ability to "hook into" file operations using standard WordPress hooks
(`do_action` and `apply_filters`). This makes it easy to add logging, monitoring, or modify operation behavior
on the fly.

All hook names can be found in traits inside `src/Hooks/Collection/`.

### How to use:

```php
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Hooks\Collection\ActionHooks;

// Enabling hooks
FSHooksProvider::setStatus(true);

// Adding an action that will be triggered AFTER writing to any file
add_action(ActionHooks::$AFTER_PUT_CONTENTS_ACTION, function($result, $file, $contents) {
    if ($result) {
        // We record information about the successful recording in a separate log
        error_log(sprintf('File written successfully: %s, Size: %d bytes', $file, strlen($contents)));
    }
}, 10, 3);

WPFS::putContents(WP_CONTENT_DIR . '/uploads/my-log.txt', 'New log entry.');

// Disabling hooks
FSHooksProvider::setStatus(false);
```

## Instance Caching in FSFactory

FSFactory caches created service instances to avoid unnecessary creation overhead. It's important to understand that the
cache
key depends not only on the class name but also on the current state of FSHooksProvider and FSGuardiansProvider.

This means that within a single request, there can be up to 4 different instances of the same service,
for example, FSBaseReader:

1. Basic (hooks and protection disabled).
2. With hooks only.
3. With protection only.
4. With both hooks and protection simultaneously.

The factory manages this cache itself, ensuring that you always get the correct instance according to the
current provider configuration.

## Manual Instance Creation and Decoration

While the WPFS facade and FSFactory cover 99% of scenarios, you might want to manually create and configure
a service instance. This can be useful for dependency injection in your classes.

The process follows the "Russian doll" principle (Decorator pattern):

1. Create a base adapter object.
2. Wrap it in a hook decorator (if needed).
3. Wrap the resulting object in a protection decorator (if needed).

```php
global $wp_filesystem;

// 1. Creating a base instance
$baseAction = new \Metapraxis\WPFileSystem\Adapters\FSBaseAction($wp_filesystem);

// 2. We wrap it in a hook decorator
$hookableAction = new \Metapraxis\WPFileSystem\Hooks\HookableFSAction($baseAction);

// 3. We wrap the result in a protection decorator
$guardedAction = new \Metapraxis\WPFileSystem\Guarded\GuardedFSBaseAction($hookableAction);


// Now $guardedAction is a full-featured service that
// will both perform hooks and throw exceptions in case of errors.
// It can be passed to the constructor of another class.

class MyPluginService
{
    private $filesystem;

    public function __construct(\Metapraxis\WPFileSystem\Contracts\FSBaseAction $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function doSomething()
    {
        try {
            $this->filesystem->putContents('/path/to/file.txt', 'data');
        } catch (\Metapraxis\WPFileSystem\Exceptions\FSException $e) {
            // ...
        }
    }
}

$service = new MyPluginService($guardedAction);
$service->doSomething();
```
