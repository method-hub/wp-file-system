# WP File System

`WP File System` is a plugin library created for the entire WordPress developer community. It is indispensable not only
for plugin and theme authors but also for technical specialists involved in deploying, maintaining, and customizing
sites, as well as for anyone who actively works with the WordPress filesystem during development.

Its philosophy is to solve one of the most common and complex problems in the WordPress ecosystem:
**reliable and secure file operations.** This problem has two aspects:

1.  **Direct use of PHP functions:** Functions like `file_put_contents`, `mkdir`, or `unlink` are a source of
    instability. Code written with them becomes brittle and environment-dependent, often leading to fatal errors due to
    incorrect file permissions or specific hosting security configurations.

2.  **Using third-party libraries:** Many developers try to solve this issue by integrating popular packages like
    `symfony/filesystem` or Laravel components. Despite their high quality, they become a "foreign element" in the WordPress
    context. These libraries are **unaware of the WordPress Filesystem API's existence**. They bypass the built-in WordPress
    mechanisms, ignoring the required file access method (`direct`, `ftp`, `ssh2`) that WordPress selects to ensure security
    and server compatibility. This leads to the same permission issues and makes secure interaction with the WordPress
    core—for example, during extension installation or updates—impossible.

`WP File System` solves both of these problems. It provides a simple, unified, and object-oriented interface that serves
as a convenient wrapper for the native `WP_Filesystem` API. The library handles all the complexity: it automatically
initializes the filesystem, determines the correct access method, manages credentials, and ensures proper permissions
are set, freeing the developer from this headache.

Using `WP File System` ensures that your code will be reliable, secure, and truly portable across different hosting
environments, as it operates **in full compliance with the principles and mechanisms of the WordPress core.**

## Getting started

Install the project from the WordPress plugin repository or use the following command to install via composer:

```shell
  composer require metapraxis/wp-file-system
```

### Usage Examples

For working with the WordPress filesystem, you'll have access to the `WPFS` facade. This is the preferred method of
using
the library, but not the only one.

```php
require_once '../wp-content/plugins/metapraxis-wp-file-system/src/Facade/WPFS.php';

use Metapraxis\WPFileSystem\Facade\WPFS;

$reportsDir = WP_CONTENT_DIR . '/reports';
$reportFile = $reportsDir . '/daily_report.txt';
$archiveFile = $reportsDir . '/archive/report_' . date('Y-m-d') . '.txt';

// 1. Atomically create or overwrite a report with JSON data and subsequent content.
WPFS::writeJson($reportFile, ['status' => 'pending', 'entries' => 100]);
WPFS::prepend($reportFile, "# Daily Report " . date('Y-m-d') . "\n");
WPFS::append($reportFile, "\n-- Check Complete --");
WPFS::replace($reportFile, '"status":"pending"', '"status":"complete"');

// 2. Create a backup, archive directory and move our file.
WPFS::copyFile($reportFile, $reportFile . '.bak');
WPFS::createDirectory(dirname($archiveFile));
WPFS::moveFile($reportFile, $archiveFile, true);

echo "Archive hash: " . WPFS::hash($archiveFile);
```

Use the FSBuilder file system query builder if you are working within a single file.

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$reportPath = WP_CONTENT_DIR . '/uploads/daily_report.txt';
$archivePath = WP_CONTENT_DIR . '/archives/report_' . date('Y-m-d') . '.txt';

// 1. Create a directory for archives if it doesn't exist
WPFS::createDirectory(dirname($archivePath));

// 2. Use elegant "Builder" for creating and modifying the report
FSBuilder::create($reportPath, $wp_filesystem)
    ->setContent("Line 2: Main data.\n")
    ->prepend("Line 1: Report Header\n")
    ->append("Line 3: Totals.")
    ->replace('Line', 'Report - Line')
    ->when(WP_DEBUG, fn($builder) => $builder->append("\n\n-- DEBUG MODE ACTIVE --"))
    ->transform('mb_strtoupper')
    ->backup('.bak')
    ->save();

// 3. Move a final report to archive with new date
WPFS::moveFile($reportPath, $archivePath, true);
```

Use the factory to create the instance you need to work with.

```php
require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;

$reader = FSFactory::create(FSBaseReader::class);

$log = $reader->getContents(__DIR__ . '/log.txt');
```

Use data providers to change the behavior when working with the file system.

```php
require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;

if (WPFS::exists(__DIR__ . '/file.txt.tmp')) {
    WPFS::append(__DIR__ . '/file.txt.tmp', 'Text content');
} else {
    echo 'File not exists';
}

// or set up exception throwing

FSGuardiansProvider::setStatus(true);

try {
    WPFS::exists(__DIR__ . '/file.txt.tmp');
    WPFS::append(__DIR__ . '/file.txt.tmp', 'Text content');
    // ...
} catch (FSPathNotFoundException $exception) {
    echo $exception->getMessage();
}
```

_In all other examples within the documentation, the class loading mechanism will no longer be used,
and all classes will be called with their namespace_.

*   [**Facade documentation WPFS**](./WPFS.md)
*   [**FSBuilder Documentation**](./BUILDER.md)
*   [**FSFactory Documentation**](./FACTORY.md)
*   [**Documentation on advanced usage**](./ADVANCED.md)
