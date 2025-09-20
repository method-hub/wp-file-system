# FSBuilder API Documentation

FSBuilder provides a fluent interface for creating and managing files. It allows combining complex file operations into
a sequence of method calls, making the code more readable and expressive. Work begins with static methods `from()` (for
existing files) or `create()` (for new ones), followed by modifier methods, and finally, a terminal method (like`save()`)
is called.

See [usage examples](#examples) of the query builder.

## API Contents

| Method                                  | Brief Description                                              | Group      |
|:----------------------------------------|:---------------------------------------------------------------|:-----------|
| **Creation Methods**                    |                                                                |            |
| [`FSBuilder::from()`](#from)            | Starts the chain with an existing file by loading its content. | `FSReader` |
| [`FSBuilder::create()`](#create)        | Starts the chain for a new file (or for complete overwrite).   | `FSReader` |
| **Content Modification Methods**        |                                                                |            |
| [`->setContent`](#setcontent)           | Completely replaces content in the buffer.                     | `FSReader` |
| [`->append`](#append)                   | Adds a string to the end of buffer content.                    | `FSReader` |
| [`->prepend`](#prepend)                 | Adds a string to the beginning of buffer content.              | `FSReader` |
| [`->replace`](#replace)                 | Performs search and replace in buffer content.                 | `FSReader` |
| [`->replaceRegex`](#replaceregex)       | Performs search and replace using regular expression.          | `FSReader` |
| [`->transform`](#transform)             | Applies a custom callback function to the content.             | `FSReader` |
| **Conditional Methods**                 |                                                                |            |
| [`->when`](#when)                       | Executes callback if condition is true.                        | `FSReader` |
| [`->unless`](#unless)                   | Executes callback if condition is false.                       | `FSReader` |
| **Attribute Methods**                   |                                                                |            |
| [`->withPermissions`](#withpermissions) | Sets file permissions that will be applied when saving.        | `FSReader` |
| [`->withOwner`](#withowner)             | Sets owner and/or group that will be applied when saving.      | `FSReader` |
| [`->backup`](#backup)                   | Creates a backup copy of the source file.                      | `FSReader` |
| **Terminal Methods**                    |                                                                |            |
| [`->save`](#save)                       | Saves buffer content to the target file.                       | `FSReader` |
| [`->get`](#get)                         | Returns content from buffer without saving to disk.            | `FSReader` |
| [`->move`](#move)                       | Moves source file to new location.                             | `FSReader` |
| [`->delete`](#delete)                   | Deletes source file.                                           | `FSReader` |

### Creation Methods

These static methods are used to initialize an FSBuilder instance.

---

#### `from()`

Starts a chain of calls for an existing file by loading its content into the buffer.

**Signature**: `FSBuilder::from(string $path, WP_Filesystem_Base $fs): self`

**Exceptions**: `FSPathNotFoundException` - if a file at a specified path is not found.

```php
global $wp_filesystem;

$path = WP_CONTENT_DIR . '/uploads/my-file.txt';
// Assuming the file exists and contains 'initial content'
$builder = \Metapraxis\WPFileSystem\Builder\FSBuilder::from($path, $wp_filesystem);

echo $builder->get();
```

_Return value from the example will be_: `initial content`.

---

#### `create()`

Starts a chain of calls for a new file or complete overwrite of existing one. Content buffer is initially empty.

**Signature**: `FSBuilder::create(string $path, WP_Filesystem_Base $fs): self`

```php
global $wp_filesystem;

$path = WP_CONTENT_DIR . '/uploads/new-log.txt';
$builder = \Metapraxis\WPFileSystem\Builder\FSBuilder::create($path, $wp_filesystem);

// At this point the file is not yet created on disk
var_dump($builder->get());
```

_Return value from the example will be_: `NULL`.

---

### Content Modification Methods

These methods modify the content of FSBuilder's internal buffer.

---

#### `setContent()`

Completely replaces content in the buffer.

**Signature**: `->setContent(string $content): self`

```php
$builder->setContent("First line.\n");
$builder->setContent("This will overwrite the first line.");
```

_Return value from the example will be_: `This will overwrite the first line.`.

---

#### `append()`

Adds a string to the end of buffer content.

**Signature**: `->append(string $data): self`

```php
$builder->setContent("First line.")->append(" Second part.");
```

_Return value from the example will be_: `First line. Second part.`.

---

#### `prepend()`

Adds a string to the beginning of buffer content.

**Signature**: `->prepend(string $data): self`

```php
$builder->setContent("Second part.")->prepend("First part. ");
```

_Return value from the example will be_: `First part. Second part.`.

---

#### `replace()`

Performs search and replace of substring in buffer content.

**Signature**: `->replace(string|string[] $search, string|string[] $replace): self`

```php
$builder->setContent("Hello world!")->replace("world", "WordPress");
```

_Return value from the example will be_: `Hello WordPress!`.

---

#### `replaceRegex()`

Performs search and replace using regular expression in buffer content.

**Signature**: `->replaceRegex(string $pattern, string|callable $replacement): self`

```php
$builder->setContent("Error on line 42.")->replaceRegex('/[0-9]+/', '100');
```

_Return value from the example will be_: `Error on line 100.`.

---

#### `transform()`

Applies a custom callback function to buffer content.

**Signature**: `->transform(callable $callback): self`

```php
$builder->setContent("some text")->transform('strtoupper');
```

_Return value from the example will be_: `SOME TEXT`.

---

### Conditional Methods

These methods allow executing operations in a chain only when certain conditions are met.

---

#### `when()`

Executes the provided callback function if the condition is true.

**Signature**: `->when(bool|callable $condition, callable $callback): self`

```php
$builder->setContent("User: Guest")
        ->when(true, function($builder) {
            $builder->replace("Guest", "Admin");
        });
```

_Return value from the example will be_: `User: Admin`.

---

#### `unless()`

Executes the provided callback function if the condition is false.

**Signature**: `->unless(bool|callable $condition, callable $callback): self`

```php
$isDebug = false;
$builder->setContent("Log:")
        ->unless($isDebug, function($builder) {
            $builder->append(" Production mode.");
        });
```

_Return value from the example will be_: `Log: Production mode.`.

### Attribute Methods

These methods allow managing file metadata and state.

---

#### `withPermissions()`

Sets file permissions (in octal format) that will be applied when calling `save()` method.

**Signature**: `->withPermissions(int $mode): self`

```php
$builder->setContent("secret data")->withPermissions(0600)->save();
```

_Return value from the example will be_: `0600`.

---

#### `withOwner()`

Sets file owner and/or group that will be applied when calling `save()` method.

**Signature**: `->withOwner(string|int|null $user, string|int|null $group = null): self`

```php
$builder->setContent("content")->withOwner('www-data', 'www-data')->save();
```

_Return value from the example will be_: `www-data`.

---

#### `backup()`

Creates a backup copy of the current file before modifying it.

**Signature**: `->backup(string $suffix = '.bak'): self`

```php
global $wp_filesystem;
$path = WP_CONTENT_DIR . '/uploads/config.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($path, 'version=1');

\Metapraxis\WPFileSystem\Builder\FSBuilder::from($path, $wp_filesystem)
    ->backup()
    ->setContent('version=2')
    ->save();
```

**Action**: File config.txt.bak will be created with content version=1, and config.txt will contain version=2.

---

### Terminal Methods

These methods complete the call chain by executing the final action.

---

#### `save()`

Saves buffer content to focus on file on disk.

**Signature**: `->save(): bool`

```php
$success = $builder->setContent("Final content.")->save();
```

_Return value from the example will be_: `true`.

---

#### `get()`

Returns current buffer content without saving to disk.

**Signature**: `->get(): ?string`

```php
$content = $builder->setContent("temporary data")->get();
```

_Return value from the example will be_: `temporary data`.

---

#### `move()`

Moves a source file to a new location.

**Signature**: `->move(string $newPath, bool $overwrite = false): bool`

```php
$wasMoved = $builder->move(WP_CONTENT_DIR . '/uploads/archive/config.txt');
```

**Action**: Moves the file that the builder was initialized with.
_Return value from the example will be_: `true` if move was successful.

---

#### `delete()`

Deletes source file.

**Signature**: `->delete(): bool`

```php
$wasDeleted = $builder->delete();
```

**Action**: Deletes the file that builder was initialized with.
_Return value from the example will be_: `true` if deletion was successful.

---

#### Examples

_Example 1: Creating and rotating log files with conditions_

This example demonstrates how to create a daily log, add information to it depending on the environment,
and then archive the old log.

```php
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$logDir = WP_CONTENT_DIR . '/logs';
$logFile = $logDir . '/debug.log';
$archiveFile = $logDir . '/archives/debug-' . date('Y-m-d') . '.log.bak';

// Using WPFS facade to prepare directories
if (!WPFS::isDirectory($logDir)) {
    WPFS::createDirectory($logDir);
}
if (!WPFS::isDirectory(dirname($archiveFile))) {
    WPFS::createDirectory(dirname($archiveFile));
}

// Archive yesterday's log if it exists
if (WPFS::exists($logFile)) {
    FSBuilder::from($logFile, $wp_filesystem)
        ->move($archiveFile, true); // Moving with overwriting if today's archive already exists
}

// Create a a new log file using builder
FSBuilder::create($logFile, $wp_filesystem)
    ->append("Log started at: " . date('H:i:s') . "\n")
    ->append("Operation: Critical Task\n")
    ->when(defined('WP_DEBUG') && WP_DEBUG, function ($builder) {
        // Add debug information only if WP_DEBUG is enabled
        $builder->append("DEBUG MODE: Verbose logging enabled.\n");
        $builder->append("Memory usage: " . memory_get_usage() . "\n");
    })
    ->unless(is_user_logged_in(), function ($builder) {
        $builder->append("WARNING: Operation performed by an anonymous user.\n");
    })
    ->prepend("--- Daily Log for " . date('Y-m-d') . " ---\n")
    ->save();
```

**What happens here**:

1. Archiving: If old `debug.log` file exists, we use `FSBuilder::from()` to "capture" it,
   then immediately call `->move()` to move it to an archive with date in the name.
2. Creation: `FSBuilder::create()` starts creation of a new log file.
3. Population: Methods `append()` and `prepend()` sequentially fill the buffer with content.
4. Conditional logic: `when(WP_DEBUG, ...)` adds detailed debug information only if
   `WP_DEBUG` constant is defined in `wp-config.php` and equals `true`. `unless(is_user_logged_in(), ...)` adds
   warning if action is performed by unauthorized user.
5. Saving: `->save()` atomically writes all collected content to new `debug.log`.

_Example 2: Safe configuration file update_

This example shows how to safely change constant value in `wp-config.php` file after creating
backup copy and preserving original access permissions.

```php
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$configFile = WPFS::getInstallationPath() . 'wp-config.php';

// Check if a file exists and is writable
if (!WPFS::isWritable($configFile)) {
    // In a real application, you could throw an exception or log an error here
    return;
}

// Get current permissions to preserve them
$originalPermissions = (int) octdec(WPFS::getPermissions($configFile));

FSBuilder::from($configFile, $wp_filesystem)
    ->backup('.bak-' . time()) // Create unique backup copy with timestamp
    ->replaceRegex(
        "/define\(\s*'WP_DEBUG',\s*false\s*\);/i", // Pattern to find define('WP_DEBUG', false);
        "define('WP_DEBUG', true);"                // What to replace it with
    )
    ->withPermissions($originalPermissions) // Set original access permissions
    ->save();
```

**What happens here**:

1. Security: First we check if `wp-config.php` file is writable and get its current access permissions,
   to avoid changing them accidentally.
2. Reading and backup: `FSBuilder::from()` reads file content. `->backup('.bak-' . time())` immediately creates its
   backup copy with a unique name containing the current time.
3. Reliable replacement: `->replaceRegex()` is used for precise replacement. Unlike simple `replace()`,
   regular expression allows us to find `WP_DEBUG` constant definition regardless of spaces and character case
   and replace `false` with `true` without affecting other parts of a file.
4. Preserving attributes: `->withPermissions($originalPermissions)` ensures that after saving the file its access
   permissions will remain the same as they were before editing. This is critical for files like `wp-config.php`.
5. Saving: `->save()` applies all changes.
