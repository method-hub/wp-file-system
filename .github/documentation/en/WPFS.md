# WPFS API Documentation

`WPFS` is a static facade that provides simple and convenient access to the full power of the `wp-file-system` library.
All methods are called statically, making the code clean and readable.

## API Contents

| Method                                                                | Brief Description                                                  | Group        |
|:----------------------------------------------------------------------|:-------------------------------------------------------------------|:-------------|
| **FSReader**                                                          |                                                                    |              |
| [`WPFS::getHomePath()`](#gethomepath)                                 | Returns the absolute path to the WordPress root directory.         | `FSReader`   |
| [`WPFS::getInstallationPath()`](#getinstallationpath)                 | Returns the absolute path to the WordPress installation directory. | `FSReader`   |
| [`WPFS::getContentPath()`](#getcontentpath)                           | Returns the path to the `wp-content` directory.                    | `FSReader`   |
| [`WPFS::getPluginsPath()`](#getpluginspath)                           | Returns path to `wp-content/plugins` directory.                    | `FSReader`   |
| [`WPFS::getThemesPath()`](#getthemespath)                             | Returns path to `wp-content/themes` directory.                     | `FSReader`   |
| [`WPFS::getLangPath()`](#getlangpath)                                 | Returns path to `wp-content/languages` directory.                  | `FSReader`   |
| [`WPFS::getHumanReadablePermissions()`](#gethumanreadablepermissions) | Gets file permissions in `-rwxr-xr-x` format.                      | `FSReader`   |
| [`WPFS::getPermissions()`](#getpermissions)                           | Gets file permissions in octal format (`0644`).                    | `FSReader`   |
| [`WPFS::getContents()`](#getcontents)                                 | Reads file contents as a string.                                   | `FSReader`   |
| [`WPFS::getContentsAsArray()`](#getcontentsasarray)                   | Reads file contents as an array of strings.                        | `FSReader`   |
| [`WPFS::getCurrentPath()`](#getcurrentpath)                           | Returns current working directory.                                 | `FSReader`   |
| [`WPFS::getOwner()`](#getowner)                                       | Returns file owner.                                                | `FSReader`   |
| [`WPFS::getGroup()`](#getgroup)                                       | Returns file group.                                                | `FSReader`   |
| [`WPFS::getLastAccessedTime()`](#getlastaccessedtime)                 | Returns last file access time.                                     | `FSReader`   |
| [`WPFS::getLastModifiedTime()`](#getlastmodifiedtime)                 | Returns last file modification time.                               | `FSReader`   |
| [`WPFS::getFileSize()`](#getfilesize)                                 | Returns file size in bytes.                                        | `FSReader`   |
| [`WPFS::getPermissionsAsOctal()`](#getpermissionsasoctal)             | Converts permissions from `-rwxr-xr-x` format to `0755`.           | `FSReader`   |
| [`WPFS::getDirectoryList()`](#getdirectorylist)                       | Gets detailed list of files and directories.                       | `FSReader`   |
| [`WPFS::getFiles()`](#getfiles)                                       | Gets recursive list of all files in directory.                     | `FSReader`   |
| [`WPFS::getUploadsDirInfo()`](#getuploadsdirinfo)                     | Gets information about uploads directory (`uploads`).              | `FSReader`   |
| [`WPFS::getTempDir()`](#gettempdir)                                   | Gets path to server temporary directory.                           | `FSReader`   |
| [`WPFS::getNormalizePath()`](#getnormalizepath)                       | Normalizes path (replaces backslashes with forward slashes).       | `FSReader`   |
| [`WPFS::getSanitizeFilename()`](#getsanitizefilename)                 | Cleans filename from invalid characters.                           | `FSReader`   |
| [`WPFS::findFolder()`](#findfolder)                                   | Finds folder in standard WordPress directories.                    | `FSReader`   |
| [`WPFS::searchForFolder()`](#searchforfolder)                         | Searches for folder starting from specified base directory.        | `FSReader`   |
| **FSAction**                                                          |                                                                    |              |
| [`WPFS::putContents()`](#putcontents)                                 | Writes a string to a file.                                         | `FSAction`   |
| [`WPFS::copyFile()`](#copyfile)                                       | Copies a file from one location to another.                        | `FSAction`   |
| [`WPFS::copyDirectory()`](#copydirectory)                             | Copies a directory recursively.                                    | `FSAction`   |
| [`WPFS::moveFile()`](#movefile)                                       | Moves a file.                                                      | `FSAction`   |
| [`WPFS::moveDirectory()`](#movedirectory)                             | Moves a directory.                                                 | `FSAction`   |
| [`WPFS::delete()`](#delete)                                           | Deletes a file or directory.                                       | `FSAction`   |
| [`WPFS::touch()`](#touch)                                             | Creates an empty file or updates its modification time.            | `FSAction`   |
| [`WPFS::createDirectory()`](#createdirectory)                         | Creates a directory.                                               | `FSAction`   |
| [`WPFS::createTempFile()`](#createtempfile)                           | Creates a unique temporary file.                                   | `FSAction`   |
| [`WPFS::deleteDirectory()`](#deletedirectory)                         | Deletes a directory.                                               | `FSAction`   |
| [`WPFS::handleUpload()`](#handleupload)                               | Handles a file uploaded via `$_FILES`.                             | `FSAction`   |
| [`WPFS::handleSideload()`](#handlesideload)                           | Loads a file from external source (e.g., by URL).                  | `FSAction`   |
| [`WPFS::downloadFromUrl()`](#downloadfromurl)                         | Downloads a file by URL to a temporary file.                       | `FSAction`   |
| [`WPFS::unzip()`](#unzip)                                             | Extracts a ZIP archive.                                            | `FSAction`   |
| **FSAuditor**                                                         |                                                                    |              |
| [`WPFS::isBinary()`](#isbinary)                                       | Checks if a string is binary.                                      | `FSAuditor`  |
| [`WPFS::isFile()`](#isfile)                                           | Checks if a path is a file.                                        | `FSAuditor`  |
| [`WPFS::isDirectory()`](#isdirectory)                                 | Checks if a path is a directory.                                   | `FSAuditor`  |
| [`WPFS::isReadable()`](#isreadable)                                   | Checks if a file/directory is readable.                            | `FSAuditor`  |
| [`WPFS::isWritable()`](#iswritable)                                   | Checks if a file/directory is writable.                            | `FSAuditor`  |
| [`WPFS::exists()`](#exists)                                           | Checks if a file or directory exists.                              | `FSAuditor`  |
| [`WPFS::connect()`](#connect)                                         | Establishes connection with the file system.                       | `FSAuditor`  |
| [`WPFS::verifyMd5()`](#verifymd5)                                     | Verifies file's MD5 hash.                                          | `FSAuditor`  |
| [`WPFS::verifySignature()`](#verifysignature)                         | Verifies file's digital signature.                                 | `FSAuditor`  |
| [`WPFS::isZipFile()`](#iszipfile)                                     | Checks if a file is a valid ZIP archive.                           | `FSAuditor`  |
| **FSManager**                                                         |                                                                    |              |
| [`WPFS::ensureUniqueFilename()`](#ensureuniquefilename)               | Ensures filename uniqueness in a directory.                        | `FSManager`  |
| [`WPFS::setOwner()`](#setowner)                                       | Sets file/directory owner.                                         | `FSManager`  |
| [`WPFS::setGroup()`](#setgroup)                                       | Sets file/directory group.                                         | `FSManager`  |
| [`WPFS::setPermissions()`](#setpermissions)                           | Sets file/directory access permissions.                            | `FSManager`  |
| [`WPFS::setCurrentDirectory()`](#setcurrentdirectory)                 | Changes current working directory.                                 | `FSManager`  |
| [`WPFS::invalidateOpCache()`](#invalidateopcache)                     | Invalidates OPcache for a specific file.                           | `FSManager`  |
| [`WPFS::invalidateDirectoryOpCache()`](#invalidatedirectoryopcache)   | Invalidates OPcache for entire directory.                          | `FSManager`  |
| **FSAdvanced**                                                        |                                                                    |              |
| [`WPFS::atomicWrite()`](#atomicwrite)                                 | Atomically (safely) writes data to a file.                         | `FSAdvanced` |
| [`WPFS::append()`](#append)                                           | Appends content to the end of file.                                | `FSAdvanced` |
| [`WPFS::prepend()`](#prepend)                                         | Adds content to the beginning of file.                             | `FSAdvanced` |
| [`WPFS::replace()`](#replace)                                         | Replaces text inside a file.                                       | `FSAdvanced` |
| [`WPFS::extension()`](#extension)                                     | Returns file extension.                                            | `FSAdvanced` |
| [`WPFS::filename()`](#filename)                                       | Returns filename without extension.                                | `FSAdvanced` |
| [`WPFS::dirname()`](#dirname)                                         | Returns path to parent directory of file.                          | `FSAdvanced` |
| [`WPFS::cleanDirectory()`](#cleandirectory)                           | Removes all directory contents without removing directory itself.  | `FSAdvanced` |
| [`WPFS::isDirectoryEmpty()`](#isdirectoryempty)                       | Checks if directory is empty.                                      | `FSAdvanced` |
| [`WPFS::getMimeType()`](#getmimetype)                                 | Returns file MIME type.                                            | `FSAdvanced` |
| [`WPFS::hash()`](#hash)                                               | Calculates file hash (MD5 by default).                             | `FSAdvanced` |
| [`WPFS::filesEqual()`](#filesequal)                                   | Compares two files by their hash.                                  | `FSAdvanced` |
| [`WPFS::readJson()`](#readjson)                                       | Reads and decodes JSON file.                                       | `FSAdvanced` |
| [`WPFS::writeJson()`](#writejson)                                     | Writes data to file in JSON format.                                | `FSAdvanced` |
| [`WPFS::readXml()`](#readxml)                                         | Reads XML file and returns `SimpleXMLElement` object.              | `FSAdvanced` |
| [`WPFS::writeXml()`](#writexml)                                       | Writes data to file in XML format.                                 | `FSAdvanced` |
| [`WPFS::readDom()`](#readdom)                                         | Reads XML file and returns `DOMDocument` object.                   | `FSAdvanced` |
| [`WPFS::writeDom()`](#writedom)                                       | Writes DOM object to XML file.                                     | `FSAdvanced` |

#### Reading Methods (FSReader)

This group of methods is designed for getting information about files, directories and paths in WordPress.

---

#### `getHomePath()`

Returns an absolute path to the WordPress root directory.

**Facade Method**: `WPFS::getHomePath(): string`

**WordPress Equivalent**: `get_home_path(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getHomePath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress`.

---

#### `getInstallationPath()`

Returns an absolute path to the WordPress installation directory (where wp-config.php is located).

**Facade Method**: `WPFS::getInstallationPath(): string`

**WordPress Equivalent**: `$wp_filesystem->abspath(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress`.

---

#### `getContentPath()`

Returns an absolute path to the wp-content directory.

**Facade Method**: `WPFS::getContentPath(): string`

**WordPress Equivalent**: `$wp_filesystem->wp_content_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content`.

---

#### `getPluginsPath(): string`

Returns a path to plugins directory.

**Facade Method**: `WPFS::getPluginsPath(): string`

**WordPress Equivalent**: `$wp_filesystem->wp_plugins_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content/plugins`.

---

#### `getThemesPath()`

Returns a path to themes directory. Can accept an optional argument - specific theme name.

**Facade Method**: `WPFS::getThemesPath(string|false $theme = false): string`

**WordPress Equivalent**: `$wp_filesystem->wp_themes_dir(string|false $theme = false): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getThemesPath();
echo \Metapraxis\WPFileSystem\Facade\WPFS::getThemesPath('twentytwentyfour');
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content/themes`,
`/var/www/html/wordpress/wp-content/themes/twentytwentyfour`.

---

#### `getLangPath()`

Returns a path to language files directory.

**Facade Method**: `WPFS::getLangPath(): string`

**WordPress Equivalent**: `$wp_filesystem->wp_lang_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getLangPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content/languages`.

---

#### `getHumanReadablePermissions()`

Gets file permissions in symbolic format common for *nix systems.

**Facade Method**: `WPFS::getHumanReadablePermissions(string $file): string`

**WordPress Equivalent**: `$wp_filesystem->gethchmod(string $file): string`.

```php
$path = \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath() . '/index.php';
echo \Metapraxis\WPFileSystem\Facade\WPFS::getHumanReadablePermissions($path);
```

_The return value from the example will be equal to_: `drwxr-xr-x`.

---

#### `getPermissions()`

Gets file permissions in octal format.

**Facade Method**: `WPFS::getPermissions(string $file): string()`

**WordPress Equivalent**: `$wp_filesystem->getchmod(string $file): string()`.

```php
$path = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
echo \Metapraxis\WPFileSystem\Facade\WPFS::getPermissions($path);
```

_The return value from the example will be equal to_: `0644`.

---

#### `getContents()`

Reads entire file content and returns it as a single string.

**Facade Method**: `WPFS::getContents(string $file): string | false`.

**WordPress Equivalent**: `$wp_filesystem->get_contents(string $file): string | false`.

```php
$content = \Metapraxis\WPFileSystem\Facade\WPFS::getContents( __DIR__ . '/my-file.txt' );
echo $content;
```

_The return value from the example will be equal to_: `Hello World!`.

---

#### `getContentsAsArray()`

Reads file content and returns it as an array where each element is one line of the file.

**Facade Method**: `WPFS::getContentsAsArray(string $file): array`.

**WordPress Equivalent**: `$wp_filesystem->get_contents_array(string $file): array`.

```php
$lines = \Metapraxis\WPFileSystem\Facade\WPFS::getContentsAsArray( __DIR__ . '/my-file.txt' );
print_r($lines);
```

_The return value from the example will be equal to_: `['Hello World!']`.

---

#### `getCurrentPath()`

Returns the current working directory.

**Facade Method**: `WPFS::getCurrentPath(): string`.

**WordPress Equivalent**: `$wp_filesystem->cwd(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getCurrentPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-admin`.

---

#### `getOwner()`

Returns file owner's name or ID.

**Facade Method**: `WPFS::getOwner(string $file): string`.

**WordPress Equivalent**: `$wp_filesystem->owner(string $file): string`.

```php
$owner = \Metapraxis\WPFileSystem\Facade\WPFS::getOwner( WPFS::getInstallationPath() . 'wp-config.php' );
echo $owner;
```

_The return value from the example will be equal to_: `www-data`.

---

#### `getGroup()`

Returns the file group's name or ID.

**Facade Method**: `WPFS::getGroup(string $file): string`.

**WordPress Equivalent**: `$wp_filesystem->group(string $file): string`.

```php
$group = \Metapraxis\WPFileSystem\Facade\WPFS::getGroup(\Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php' );
echo $group;
```

_The return value from the example will be_: `www-data`.

---

#### `getLastAccessedTime()`

Returns last file access time as Unix timestamp.

**Facade Method**: `WPFS::getLastAccessedTime(string $file): false | int`.

**WordPress Equivalent**: `$wp_filesystem->atime(string $file): false | int`.

```php
$lastAccess = \Metapraxis\WPFileSystem\Facade\WPFS::getLastAccessedTime( __FILE__ );
echo date('Y-m-d H:i:s', $lastAccess);
```

_The return value from the example will be equal to_: `2024-03-10 12:34:56`.

---

#### `getLastModifiedTime()`

Returns the last file modification time as a Unix timestamp.

**Facade Method**: `WPFS::getLastModifiedTime(string $file): int | false`.

**WordPress Equivalent**: `$wp_filesystem->mtime(string $file): int | false`.

```php
$lastModified = \Metapraxis\WPFileSystem\Facade\WPFS::getLastModifiedTime( __FILE__ );
echo date('Y-m-d H:i:s', $lastModified);
```

_The return value from the example will be equal to_: `2024-03-10 12:40:00`.

---

#### `getFileSize()`

Returns file size in bytes.

**Facade Method**: `WPFS::getFileSize(string $file): int | false`.

**WordPress Equivalent**: `$wp_filesystem->size(string $file): int | false`.

```php
$sizeInBytes = \Metapraxis\WPFileSystem\Facade\WPFS::getFileSize( __FILE__ );
echo $sizeInBytes . ' bytes';
```

_The return value from the example will be equal to_: `14321`.

---

#### `getPermissionsAsOctal()`

Converts access rights from character format (drwxr-xr-x) to octal format (0755).

**Facade method**: `WPFS::getPermissionsAsOctal(string $mode): string`.

**WordPress Equivalent**: `$wp_filesystem->getnumchmodfromh(string $mode): string`.

```php
$octalPermissions = \Metapraxis\WPFileSystem\Facade\WPFS::getPermissionsAsOctal('drwxr-xr-x');
echo $octalPermissions;
```

_The return value from the example will be equal to_: `0755`.

---

#### `getDirectoryList()`

Retrieves a detailed list of files and directories by the specified path.

**Facade method**: `WPFS::getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false): array | false`.

**WordPress Equivalent**: `$wp_filesystem->dirlist(string $path, bool $includeHidden = true, bool $recursive = false): array | false`.

```php
$list = \Metapraxis\WPFileSystem\Facade\WPFS::getDirectoryList( \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath() );
print_r($list);
```

_The return value from the example will be equal to_:
`['akismet' => ['name' => 'akismet','type' => 'd'], 'hello.php' => ['name' => 'hello.php','type' => 'f']]`.

---

#### `getFiles()`

Retrieves a recursive list of all files in a directory.

**Facade method**: `WPFS::getFiles(string $folder = '', int $levels = 100, array $exclusions = [], bool $includeHidden = false): array | false`.

**WordPress Equivalent**: `list_files(string $folder = '', int $levels = 100, array $exclusions = [], bool $includeHidden = false): array | false`.

```php
$allFilesInPlugins = \Metapraxis\WPFileSystem\Facade\WPFS::getFiles( \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath() );
print_r($allFilesInPlugins);
```

_The return value from the example will be equal to_:
`['/var/www/html/wordpress/wp-content/plugins/akismet/akismet.php', '/var/www/html/wordpress/wp-content/plugins/hello.php']`.

---

#### `getUploadsDirInfo()`

Gets an array with information about the uploads directory, including the path and URL.

**Facade method**: `WPFS::getUploadsDirInfo(): array`.

**WordPress Equivalent**: `wp_upload_dir(): array`.

```php
$uploads = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo();
echo 'Путь к загрузкам: ' . $uploads['basedir'];
```

_The return value from the example will be equal to_:
`['path' => '/var/www/html/wordpress/wp-content/uploads/2025/09','url' => 'https://example.com/wp-content/uploads/2025/09', 'basedir' => '/var/www/html/wordpress/wp-content/uploads','baseurl' => 'https://example.com/wp-content/uploads', 'error' => false]`.

---

#### `getTempDir()`

Gets the path to the directory for temporary files on the server.

**Facade method**: `WPFS::getTempDir(): string`.

**WordPress Equivalent**: `get_temp_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getTempDir();
```

_The return value from the example will be equal to_: `/tmp`.

---

#### `getNormalizePath()`

Brings the path to a single format by replacing backslashes (\) with forward slashes (/).

**Facade method**: `WPFS::getNormalizePath(string $path): string`.

**WordPress Equivalent**: `wp_normalize_path(string $path): string`.

```php
$normalized = \Metapraxis\WPFileSystem\Facade\WPFS::getNormalizePath('C:\\Users\\Admin');
echo $normalized;
```

_The return value from the example will be equal to_: `C:/Users/Admin`.

---

#### `getSanitizeFilename()`

Clears the file name from invalid characters by replacing spaces and special characters with hyphens.

**Facade method**: `WPFS::getSanitizeFilename(string $filename): string`.

**WordPress Equivalent**: `sanitize_file_name(string $filename): string`.

```php
$safeName = \Metapraxis\WPFileSystem\Facade\WPFS::getSanitizeFilename('My Cool Image (2)?.jpg');
echo $safeName;
```

_The return value from the example will be equal to_: `My-Cool-Image-2.jpg`.

---

#### `findFolder()`

Finds a folder in the standard WordPress directories.

**Facade method**: `WPFS::findFolder(string $folder): string`.

**WordPress Equivalent**: `$wp_filesystem->find_folder(string $folder): string`.

```php
$contentDir = \Metapraxis\WPFileSystem\Facade\WPFS::findFolder('wp-content');
echo $contentDir;
```

_The return value from the example will be equal to_: `/var/www/html/wprdpress/wp-content`.

---

#### `searchForFolder()`

Searches for the specified folder, starting from a specific base directory.

**Facade method**: `WPFS::searchForFolder(string $folder, string $base = '.', bool $loop = false): string | false`.

**WordPress Equivalent**: `$wp_filesystem->search_for_folder(string $folder, string $base = '.', bool $loop = false): string | false`.

```php
$themesDir = \Metapraxis\WPFileSystem\Facade\WPFS::searchForFolder('themes', \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath());
echo $themesDir;
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content/themes`.

---

#### Methods for writing and modifying (FSAction)

This group of methods is used to create, modify, and delete files and directories.

---

#### `putContents()`

Writes string content to a file. If the file does not exist, it will be created. If it exists, its contents
will be overwritten.

**Facade method**: `WPFS::putContents(string $file, string $contents, ?int $mode = null): bool`.

**WordPress Equivalent**: `$wp_filesystem->put_contents(string $file, string $contents, ?int $mode = null): bool`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/my-log.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($filePath, 'User logged in at ' . date('Y-m-d H:i:s'));
```

_The return value from the example will be equal to_: `true`.

---

#### `copyFile()`

Copies a file from one location to another.

**Facade method**: `WPFS::copyFile(string $source, string $destination, bool $overwrite = false, ?int $mode = null): bool`.

**WordPress Equivalent**: `$wp_filesystem->copy(string $source, string $destination, bool $overwrite = false, ?int $mode = null): bool`.

```php
$source = WP_CONTENT_DIR . '/uploads/source.txt';
$destination = WP_CONTENT_DIR . '/uploads/destination.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::copyFile($source, $destination, true); // true - разрешить перезапись
```

_The return value from the example will be equal to_: `true`.

---

#### `copyDirectory()`

Recursively copies the directory with all its contents.

**Facade method**: `WPFS::copyDirectory(string $from, string $to, array $skipList = []): bool`.

**WordPress Equivalent**: `copy_dir(string $from, string $to, array $skipList = []): bool`.

```php
$sourceDir = WP_PLUGIN_DIR . '/my-plugin/assets';
$destDir = WP_CONTENT_DIR . '/uploads/my-plugin-assets';
\Metapraxis\WPFileSystem\Facade\WPFS::copyDirectory($sourceDir, $destDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `moveFile()`

Moves (or renames) the file.

**Facade method**: `WPFS::moveFile(string $source, string $destination, bool $overwrite = false): bool`.

**WordPress Equivalent**: `$wp_filesystem->move(string $source, string $destination, bool $overwrite = false): bool`.

```php
$oldPath = WP_CONTENT_DIR . '/uploads/temp.log';
$newPath = WP_CONTENT_DIR . '/uploads/archived.log';
\Metapraxis\WPFileSystem\Facade\WPFS::moveFile($oldPath, $newPath, true);
```

_The return value from the example will be equal to_: `true`.

---

#### `moveDirectory()`

Moves (or renames) the directory.

**Facade method**: `WPFS::moveDirectory(string $from, string $to, bool $overwrite = false): bool`.

**WordPress Equivalent**: `move_dir(string $from, string $to, bool $overwrite = false): bool`.

```php
$oldDir = WP_CONTENT_DIR . '/uploads/temp-files';
$newDir = WP_CONTENT_DIR . '/uploads/permanent-files';
\Metapraxis\WPFileSystem\Facade\WPFS::moveDirectory($oldDir, $newDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `delete()`

Deletes a file or directory. To delete a non-empty directory, set the second parameter to true.

**Facade method**: `WPFS::delete(string $path, bool $recursive = false, ?string $type = null): bool`.

**WordPress Equivalent**: `$wp_filesystem->delete(string $path, bool $recursive = false, ?string $type = null): bool`.

```php
// Deleting a file
\Metapraxis\WPFileSystem\Facade\WPFS::delete( WP_CONTENT_DIR . '/uploads/stale-file.txt' );

// Recursive directory deletion
\Metapraxis\WPFileSystem\Facade\WPFS::delete( WP_CONTENT_DIR . '/uploads/old-cache', true );

```

_The return value from the example will be equal to_: `true`.

---

#### `touch()`

Creates an empty file if it does not exist, or updates its last modification time if it already exists.

**Facade method**: `WPFS::touch(string $file, int $mtime = 0, int $atime = 0): bool`.

**WordPress Equivalent**: `$wp_filesystem->touch(string $file, int $mtime = 0, int $atime = 0): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::touch( WP_CONTENT_DIR . '/uploads/cron-last-run.lock' );
```

_The return value from the example will be equal to_: `true`.

---

#### `createDirectory()`

Creates a new directory.

**Facade method**: `WPFS::createDirectory(string $path, ?int $chmod = null, mixed $chown = null, mixed $chgrp = null): bool`.

**WordPress Equivalent**: `$wp_filesystem->mkdir(string $path, ?int $chmod = null, mixed $chown = null, mixed $chgrp = null): bool`.

```php
$logsDir = WP_CONTENT_DIR . '/logs';
\Metapraxis\WPFileSystem\Facade\WPFS::createDirectory($logsDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `createTempFile()`

Creates a unique temporary file in the system temporary directory.

**Facade method**: `WPFS::createTempFile(string $filename = '', string $dir = ''): string | false`.

**WordPress Equivalent**: `wp_tempnam(string $filename = '', string $dir = ''): string | false`.

```php
$tempFilePath = \Metapraxis\WPFileSystem\Facade\WPFS::createTempFile('my_app_');
echo $tempFilePath;
```

_The return value from the example will be equal to_: `true`.

---

#### `deleteDirectory()`

Deletes the directory. It is an alias for delete($path, true).

**Facade method**: `WPFS::deleteDirectory(string $path, bool $recursive = false): bool`.

**WordPress Equivalent**: `$wp_filesystem->rmdir(string $path, bool $recursive = false): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::deleteDirectory( WP_CONTENT_DIR . '/uploads/temp-backup', true );
```

_The return value from the example will be equal to_: `true`.

---

#### `handleUpload()`

Processes the file uploaded via the standard HTML form (the $_FILES array) and moves it to the downloads directory
WordPress.

**Facade method**: `WPFS::handleUpload(array $file, array|false $overrides = false, ?string $time = null): array`.

**WordPress Equivalent**: `wp_handle_upload(array $file, array|false $overrides = false, ?string $time = null): array`.

```php
if ( ! empty($_FILES['my_image_upload']) ) {
    $result = \Metapraxis\WPFileSystem\Facade\WPFS::handleUpload($_FILES['my_image_upload'], ['test_form' => false]);
}
```

_The return value from the example will be equal to_: `['file' => '...', 'url' => '...', 'type' => '...']`.

---

#### `handleSideload()`

Downloads a file from the outside (for example, from a temporary file obtained by URL) and moves it to the downloads 
directory.

**Facade method**: `WPFS::handleSideload(array $file, array|false $overrides = false, ?string $time = null): array`.

**WordPress Equivalent**: `wp_handle_sideload(array $file, array|false $overrides = false, ?string $time = null): array`.

```php
$file = ['name' => 'image.jpg', 'tmp_name' => '/tmp/some_image.jpg'];
$result = \Metapraxis\WPFileSystem\Facade\WPFS::handleSideload($file, ['test_form' => false]);
```

_The return value from the example will be equal to_: `['file' => '...', 'url' => '...', 'type' => '...']`.

---

#### `downloadFromUrl()`

Downloads a file from the outside (for example, from a temporary file obtained by URL) and moves it to the downloads
directory.

**Facade method**: `WPFS::downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false): string | false`.

**WordPress Equivalent**: `download_url(string $url, int $timeout = 300, bool $signatureVerification = false): string | false`.

```php
$url = 'https://downloads.wordpress.org/plugin/akismet.latest.zip';
$tempFile = \Metapraxis\WPFileSystem\Facade\WPFS::downloadFromUrl($url);
```

_The return value from the example will be equal to_: `/tmp/akismet.latest.zip.tmp`.

---

#### `unzip()`

Unpacks the ZIP archive to the specified directory.

**Facade method**: `WPFS::unzip(string $file, string $to): bool`.

**WordPress Equivalent**: `unzip_file(string $file, string $to): bool`.

```php
$url = 'https://downloads.wordpress.org/plugin/akismet.latest.zip';
$tempFile = \Metapraxis\WPFileSystem\Facade\WPFS::downloadFromUrl($url);
```

_The return value from the example will be equal to_: `true`.

---

### Audit Methods (FSAuditor)

This group of methods is designed for checking file and directory states (existence, access permissions, type)
without modifying any data.

---

#### `isBinary()`

Checks whether the string contains binary characters (for example, zero bytes).

**Facade method**: `WPFS::isBinary(string $text): bool`.

**WordPress Equivalent**: `$wp_filesystem->is_binary(): bool`.

```php
$binaryContent = "Это бинарный текст с нулевым байтом \x00 внутри.";
$isBinary = \Metapraxis\WPFileSystem\Facade\WPFS::isBinary($binaryContent);
```

_The return value from the example will be equal to_: `true`.

---

#### `isFile()`

Checks whether the specified path is a file.

**Facade method**: `WPFS::isFile(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->is_file(): bool`.

```php
$filePath = WP_PLUGIN_DIR . '/hello.php';
$isFile = \Metapraxis\WPFileSystem\Facade\WPFS::isFile($filePath);
```

_The return value from the example will be equal to_: `true`.

---

#### `isDirectory()`

Checks whether the specified path is a directory.

**Facade method**: `WPFS::isDirectory(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->is_dir(): bool`.

```php
$dirPath = WP_PLUGIN_DIR;
$isDir = \Metapraxis\WPFileSystem\Facade\WPFS::isDirectory($dirPath);
```

_The return value from the example will be equal to_: `true`.

---

#### `isReadable()`

Checks whether the file or directory is readable by the current server user.

**Facade method**: `WPFS::isReadable(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->is_readable(string $path): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
$isReadable = \Metapraxis\WPFileSystem\Facade\WPFS::isReadable($filePath);
```

_The return value from the example will be equal to_: `true`.

---

#### `isWritable()`

Checks whether the file or directory is writable by the current server user.

**Facade method**: `WPFS::isWritable(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->is_writable(string $path): bool`.

```php
$uploadsDir = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'];
$isWritable = \Metapraxis\WPFileSystem\Facade\WPFS::isWritable($uploadsDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `exists()`

Checks if a file or directory exists on the specified path.

**Facade method**: `WPFS::exists(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->exists(string $path): bool`.

```php
$configFile = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
$exists = \Metapraxis\WPFileSystem\Facade\WPFS::exists($configFile);
```

_The return value from the example will be equal to_: `true`.

---

#### `connect()`

Establishes a connection to the file system. It is usually called automatically by the kernel, but it can be useful
for debugging.

**Facade method**: `WPFS::connect(): bool`.

**WordPress Equivalent**: `$wp_filesystem->connect(string $path): bool`.

```php
$connected = \Metapraxis\WPFileSystem\Facade\WPFS::connect();
```

_The return value from the example will be equal to_: `true`.

---

#### `verifyMd5()`

Checks the MD5 hash of the file for compliance with the expected value.

**Facade method**: `WPFS::verifyMd5(string $filename, string $expectedMd5): bool | int`.

**WordPress Equivalent**: `verify_file_md5(string $filename, string $expectedMd5): bool | int`.

```php
$connected = \Metapraxis\WPFileSystem\Facade\WPFS::connect();
```

_The return value from the example will be equal to_: `true`.

---

#### `verifySignature()`

Verifies the digital signature of the file (used by the WordPress core for authentication updates).

**Facade method**: `WPFS::verifySignature(string $filename, string|array $signatures, string|false $filenameForErrors = false): bool`.

**WordPress Equivalent**: `verify_file_signature(string $filename, string|array $signatures, string|false $filenameForErrors = false): bool`.

```php
$coreFile = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-includes/version.php';
$signatures = ['...some_valid_signature_from_wordpress_org...'];
$isValid = \Metapraxis\WPFileSystem\Facade\WPFS::verifySignature($coreFile, $signatures);
```

_The return value from the example will be equal to_: `true`.

---

#### `isZipFile()`

Checks whether the specified file is a valid ZIP archive.

**Facade method**: `WPFS::isZipFile(string $file): bool`.

**WordPress Equivalent**: `wp_zip_file_is_valid(string $file): bool`.

```php
$zipFile = WP_CONTENT_DIR . '/upgrade/some-plugin.zip';
$isZip = \Metapraxis\WPFileSystem\Facade\WPFS::isZipFile($zipFile);
```

_The return value from the example will be equal to_: `true`.

---

### Management Methods (FSManager)

This group of methods is designed for managing file and directory properties such as access permissions, owner,
and for interacting with server utilities like OPcache.

---

#### `ensureUniqueFilename()`

Ensures that the file name is unique in the specified directory. If a file with that name already exists,
a numeric suffix will be added to it (for example, image-1.jpg ).

**Facade method**: `WPFS::ensureUniqueFilename(string $dir, string $filename, ?callable $unique_filename_callback = null): string`.

**WordPress Equivalent**: `wp_unique_filename(string $dir, string $filename, ?callable $unique_filename_callback = null): string`.

```php
$uploadsDir = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'];

\Metapraxis\WPFileSystem\Facade\WPFS::touch($uploadsDir . '/my-picture.jpg');
$uniqueName = \Metapraxis\WPFileSystem\Facade\WPFS::ensureUniqueFilename($uploadsDir, 'my-picture.jpg');
```

_The return value from the example will be equal to_: `my-picture-1.jpg`.

---

#### `setOwner()`

Changes the owner of a file or directory. Requires the appropriate rights on the server.

**Facade method**: `WPFS::setOwner(string $file, string|int $owner, bool $recursive = false): bool`.

**WordPress Equivalent**: `$wp_filesystem->chown(string $file, string|int $owner, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';

$result = \Metapraxis\WPFileSystem\Facade\WPFS::setOwner($filePath, 'www-data');
```

_The return value from the example will be equal to_: `true`.

---

#### `setGroup()`

Modifies the group of a file or directory. Requires the appropriate rights on the server.

**Facade method**: `WPFS::setGroup(string $file, string|int $group, bool $recursive = false): bool`.

**WordPress Equivalent**: `$wp_filesystem->chgrp(string $file, string|int $group, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';

$result = \Metapraxis\WPFileSystem\Facade\WPFS::setGroup($filePath, 'www-data');
```

_The return value from the example will be equal to_: `true`.

---

#### `setPermissions()`

Sets access rights for a file or directory in octal format.

**Facade method**: `WPFS::setPermissions(string $file, ?int $mode = null, bool $recursive = false): bool`.

**WordPress Equivalent**: `$wp_filesystem->chmod(string $file, ?int $mode = null, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'] . '/secret.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($filePath, 'secret data');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::setPermissions($filePath, 0600);
```

_The return value from the example will be equal to_: `true`.

---

#### `setCurrentDirectory()`

Modifies the current working directory for the file system.

**Facade method**: `WPFS::setCurrentDirectory(string $path): bool`.

**WordPress Equivalent**: `$wp_filesystem->chdir(string $path): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::setCurrentDirectory( WP_PLUGIN_DIR );
$current = \Metapraxis\WPFileSystem\Facade\WPFS::getCurrentPath();
```

_The return value from the example will be equal to_: `/var/www/html/wordpress/wp-content/plugins`.

---

#### `invalidateOpCache()`

Resets (invalidates) the OPcache for a specific PHP file. It is useful after programmatically changing the file
so that PHP uses the new version.

**Facade method**: `WPFS::invalidateOpCache(string $filepath, bool $force = false): bool`.

**WordPress Equivalent**: `wp_opcache_invalidate(string $filepath, bool $force = false): bool`.

```php
$filePath = WP_PLUGIN_DIR . '/my-plugin/functions.php';
// ... code that modifies $filePath file goes here ...
$result = \Metapraxis\WPFileSystem\Facade\WPFS::invalidateOpCache($filePath, true);
```

_The return value from the example will be equal to_: `true`.

---

#### `invalidateDirectoryOpCache()`

Recursively invalidates OPcache for all PHP files in the specified directory.

**Facade method**: `WPFS::invalidateDirectoryOpCache(string $dir): void`.

**WordPress Equivalent**: `wp_opcache_invalidate_directory(string $dir): void`.

```php
$pluginDir = WP_PLUGIN_DIR . '/my-plugin';
\Metapraxis\WPFileSystem\Facade\WPFS::invalidateDirectoryOpCache($pluginDir);
```

_The return value from the example will be equal to_: `void`.

---

### Advanced Operation Methods (FSAdvanced)

This group of methods provides high-level utilities for working with files and directories that are not part of
the standard WP_Filesystem set but are often useful in modern development.

---

#### `atomicWrite()`

Atomically (safely) writes data to a file. This method ensures that the file won't be corrupted if writing is
interrupted,
as data is first written to a temporary file which is then renamed to the target file.

**Facade Method**: `WPFS::atomicWrite(string $path, string $content, ?int $mode = null): bool`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/config.json';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::atomicWrite($filePath, '{"key":"value"}');
```

_The return value from the example will be equal to_: `true`.

---

#### `append()`

Adds specified content to the end of the file.

**Facade Method**: `WPFS::append(string $path, string $content): bool`.

```php
$logFile = WP_CONTENT_DIR . '/debug.log';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($logFile, "Initial event.\n");
$result = \Metapraxis\WPFileSystem\Facade\WPFS::append($logFile, "Another event.\n");
```

_The return value from the example will be equal to_: `true`.

---

#### `prepend()`

Adds specified content to the beginning of the file.

**Facade Method**: `WPFS::prepend(string $path, string $content): bool`.

```php
$listFile = WP_CONTENT_DIR . '/list.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($listFile, "Item 2");
$result = \Metapraxis\WPFileSystem\Facade\WPFS::prepend($listFile, "Item 1\n");
```

_The return value from the example will be equal to_: `true`.

---

#### `replace()`

Finds and replaces text inside a file.

**Facade Method**: `WPFS::replace(string $path, string|array $search, string|array $replace): bool`.

```php
$configFile = WP_CONTENT_DIR . '/config.ini';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($configFile, 'mode = development');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::replace($configFile, 'development', 'production');
```

_The return value from the example will be equal to_: `true`.

---

#### `extension()`

Returns the file extension from the specified path.

**Facade Method**: `WPFS::extension(string $path): string`.

```php
$extension = \Metapraxis\WPFileSystem\Facade\WPFS::extension('path/to/image.jpeg');
```

_The return value from the example will be equal to_: `jpeg`.

---

#### `filename()`

Returns the file name from the specified path without the extension.

**Facade Method**: `WPFS::filename(string $path): string`.

```php
$name = \Metapraxis\WPFileSystem\Facade\WPFS::filename('path/to/archive.tar.gz');
```

_The return value from the example will be equal to_: `archive.tar`.

---

#### `dirname()`

Returns the path to the parent directory of the file.

**Facade Method**: `WPFS::dirname(string $path): string`.

```php
$dir = \Metapraxis\WPFileSystem\Facade\WPFS::dirname('/var/www/html/wp-content/plugins/my-plugin/main.php');
```

_The return value from the example will be equal to_: `/var/www/html/wp-content/plugins/my-plugin`.

---

#### `cleanDirectory()`

Deletes all files and subdirectories inside the specified directory without deleting the directory itself.

**Facade Method**: `WPFS::cleanDirectory(string $directory): bool`.

```php
$cacheDir = WP_CONTENT_DIR . '/cache/my-plugin';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::cleanDirectory($cacheDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `isDirectoryEmpty()`

Checks if the directory is empty.

Метод фасада: `WPFS::isDirectoryEmpty(string $directory): bool`.

```php
$emptyDir = WP_CONTENT_DIR . '/uploads/empty-folder';
\Metapraxis\WPFileSystem\Facade\WPFS::createDirectory($emptyDir);
$isEmpty = \Metapraxis\WPFileSystem\Facade\WPFS::isDirectoryEmpty($emptyDir);
```

_The return value from the example will be equal to_: `true`.

---

#### `getMimeType()`

Returns the MIME type of the file.

**Facade Method**: `WPFS::getMimeType(string $path): string | false`.

```php
$filePath = WP_PLUGIN_DIR . '/hello.php';
$mime = \Metapraxis\WPFileSystem\Facade\WPFS::getMimeType($filePath);
```

_The return value from the example will be equal to_: `text/plain` (or `application/x-httpd-php` depending on server configuration).

---

#### `hash()`

Calculates the hash of the file (by default, the md5 algorithm is used).

**Facade Method**: `WPFS::hash(string $path, string $algorithm = 'md5'): string | false`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/image.jpg';
$hash = \Metapraxis\WPFileSystem\Facade\WPFS::hash($filePath, 'sha256');
```

_The return value from the example will be equal to_: string containing file's SHA256 hash.

---

#### `filesEqual()`

Compares two files by their contents (via an MD5 hash) and returns true if they are identical.

Метод фасада: `WPFS::filesEqual(string $path1, string $path2): bool`.

```php
$file1 = WP_CONTENT_DIR . '/file1.txt';
$file2 = WP_CONTENT_DIR . '/file2.txt'; // Exact copy of file1.txt
$areEqual = \Metapraxis\WPFileSystem\Facade\WPFS::filesEqual($file1, $file2);
```

_The return value from the example will be equal to_: `true`.

---

#### `readJson()`

Reads and decodes a JSON file, returning the result as an associative array or object.

**Facade Method**: `WPFS::readJson(string $path, bool $assoc = true): mixed`.

```php
$jsonPath = WP_CONTENT_DIR . '/data.json';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($jsonPath, '{"id": 1, "name": "Test"}');
$data = \Metapraxis\WPFileSystem\Facade\WPFS::readJson($jsonPath);
```

_The return value from the example will be equal to_: `['id' => 1, 'name' => 'Test']`.

---

#### `writeJson()`

Writes an array or object to a file in JSON format. By default formats output for readability.

**Facade Method**: `WPFS::writeJson(string $path, mixed $data, int $options = JSON_PRETTY_PRINT): bool`.

```php
$settingsPath = WP_CONTENT_DIR . '/settings.json';
$settings = ['theme' => 'dark', 'version' => '1.2.0'];
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeJson($settingsPath, $settings);
```

_The return value from the example will be equal to_: `true`.

---

#### `readXml()`

Reads XML file and returns its content as SimpleXMLElement object.

**Facade Method**: `WPFS::readXml(string $path): SimpleXMLElement|false`.

```php
$xmlPath = WP_CONTENT_DIR . '/feed.xml';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($xmlPath, '<items><item>First</item></items>');
$xmlObject = \Metapraxis\WPFileSystem\Facade\WPFS::readXml($xmlPath);
```

_The return value from the example will be equal to_: `SimpleXMLElement`.

---

#### `writeXml()`

Writes XML data (string, SimpleXMLElement, or DOMDocument) to a file.

**Facade Method**: `WPFS::writeXml(string $path, DOMDocument|SimpleXMLElement|string $xml): bool`.

```php
$newXmlPath = WP_CONTENT_DIR . '/new_feed.xml';
$xmlString = '<products><product id="1">Book</product></products>';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeXml($newXmlPath, $xmlString);
```

_The return value from the example will be equal to_: `true`.

---

#### `readDom()`

Reads an XML file and returns its content as DOMDocument object.

**Facade Method**: `WPFS::readDom(string $path): DOMDocument | false`.

```php
$xmlPath = WP_CONTENT_DIR . '/sitemap.xml';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($xmlPath, '<urlset><url><loc>[https://example.com](https://example.com)</loc></url></urlset>');
$domObject = \Metapraxis\WPFileSystem\Facade\WPFS::readDom($xmlPath);
```

_The return value from the example will be equal to_: `DOMDocument`.

---

#### `writeDom()`

Writes DOMDocument object to XML file. Acts as a synonym for writeXml() method.

**Facade Method**: `WPFS::writeDom(string $path, DOMDocument|SimpleXMLElement|string $dom): bool`.

```php
$newXmlPath = WP_CONTENT_DIR . '/new_sitemap.xml';
$dom = new DOMDocument();
$dom->loadXML('<pages><page>Home</page></pages>');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeDom($newXmlPath, $dom);
```

_The return value from the example will be equal to_: `true`.
