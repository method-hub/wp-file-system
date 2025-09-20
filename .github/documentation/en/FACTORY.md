# FSFactory API Documentation

FSFactory is a factory for creating and managing instances of file system services. This class implements
the Singleton pattern to ensure that for each service type (Reader, Action, etc.)
and its configuration (with or without hooks) there is only one instance per request. This prevents object
duplication and ensures a predictable state.

In most cases, you won't need to interact with the factory directly as the WPFS facade does this for you.
However, FSFactory is useful for dependency injection or when you need a specific service instance with certain
decorator configurations.

## API Contents

| Method                                     | Brief Description                                              | Group      |
|:-------------------------------------------|:---------------------------------------------------------------|:-----------|
| [`FSFactory::create()`](#create)           | Universal method for creating (or getting) a service instance. | `FSReader` |
| [`FSFactory::getReader()`](#getreader)     | Gets a service for reading data from the file system.          | `FSReader` |
| [`FSFactory::getAction()`](#getaction)     | Gets a service for performing active file operations.          | `FSReader` |
| [`FSFactory::getManager()`](#getmanager)   | Gets a service for managing files and directories.             | `FSReader` |
| [`FSFactory::getAuditor()`](#getauditor)   | Gets a service for checking and validating filesystem state.   | `FSReader` |
| [`FSFactory::getAdvanced()`](#getadvanced) | Gets a service for advanced, high-level operations.            | `FSReader` |

---

#### `create()`

This is the main factory method that contains all instance creation logic. It is "lazy", meaning it creates an object
only on the first request, and on later requests with the same configuration returns the already created instance.

**Signature**: `FSFactory::create(string $className): FileSystem`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;

$reader = FSFactory::create(FSBaseReader::class);

if ($reader->exists('/path/to/file.txt')) {
    echo $reader->getContents('/path/to/file.txt');
}
```

---

#### `getReader()`

Convenient shortcut method for `create(FSBaseReader::class)`.

**Signature**: `FSFactory::getReader(): \Metapraxis\WPFileSystem\Contracts\FSBaseReader`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$reader = FSFactory::getReader();
$contentPath = $reader->getContentPath();
```

---

#### `getAction()`

Convenient shortcut method for `create(FSBaseAction::class)`.

**Signature**: `FSFactory::getAction(): \Metapraxis\WPFileSystem\Contracts\FSBaseAction`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$action = FSFactory::getAction();
$action->putContents(WP_CONTENT_DIR . '/uploads/new-file.txt', 'Hello!');
```

---

#### `getManager()`

Convenient shortcut method for create(FSBaseManager::class).

**Signature**: `FSFactory::getManager(): \Metapraxis\WPFileSystem\Contracts\FSBaseManager`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$manager = FSFactory::getManager();
$safeFilename = $manager->ensureUniqueFilename(WP_CONTENT_DIR . '/uploads', 'image.jpg');
```

---

#### `getAuditor()`

Convenient shortcut method for create(FSBaseAuditor::class).

**Signature**: `FSFactory::getAuditor(): \Metapraxis\WPFileSystem\Contracts\FSBaseAuditor`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$auditor = FSFactory::getAuditor();
if ($auditor->isWritable(WP_CONTENT_DIR . '/uploads')) {
    // Directory is writable
}
```

---

#### `getAdvanced()`

Convenient shortcut method for `create(FSAdvanced::class)`.

**Signature**: `FSFactory::getAdvanced(): \Metapraxis\WPFileSystem\Contracts\FSAdvanced`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$advanced = FSFactory::getAdvanced();
$data = ['status' => 'ok'];
$advanced->writeJson(WP_CONTENT_DIR . '/data.json', $data);
```

### Working with Decorators (Hooks and Guardians)

The factory automatically considers the state of FSHooksProvider and FSGuardiansProvider. When these providers are
enabled,
FSFactory will return "wrapped" (decorated) service instances. The factory caches instances for each
unique combination of provider states.

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;

// 1. Providers are disabled (by default)
$reader = FSFactory::getReader();
// $reader is an instance of Metapraxis\WPFileSystem\Adapters\FSBaseReader

// 2. Enable hooks
FSHooksProvider::setStatus(true);
$hookableReader = FSFactory::getReader();
// $hookableReader is an instance of Metapraxis\WPFileSystem\Hooks\HookableFSReader

// 3. Enable "protection" (exceptions on errors)
FSGuardiansProvider::setStatus(true);
$guardedHookableReader = FSFactory::getReader();
// $guardedHookableReader is an instance of Metapraxis\WPFileSystem\Guarded\GuardedFSReader,
// which wraps HookableFSReader, which in turn wraps FSBaseReader.

// 4. Disable hooks but keep protection
FSHooksProvider::setStatus(false);
$guardedReader = FSFactory::getReader();
// $guardedReader is a new instance of Metapraxis\WPFileSystem\Guarded\GuardedFSReader,
// which wraps the base FSBaseReader.
```
