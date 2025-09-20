# Документация по API WPFS

`WPFS` — это статический фасад, который предоставляет простой и удобный доступ ко всей мощи библиотеки `wp-file-system`.
Все методы вызываются статически, что делает код чистым и читаемым.

## Содержание API

| Метод                                                                 | Краткое описание                                                | Группа       |
|:----------------------------------------------------------------------|:----------------------------------------------------------------|:-------------|
| **FSReader**                                                          |                                                                 |              |
| [`WPFS::getHomePath()`](#gethomepath)                                 | Возвращает абсолютный путь к корневой директории WordPress.     | `FSReader`   |
| [`WPFS::getInstallationPath()`](#getinstallationpath)                 | Возвращает абсолютный путь к директории установки WordPress.    | `FSReader`   |
| [`WPFS::getContentPath()`](#getcontentpath)                           | Возвращает путь к директории `wp-content`.                      | `FSReader`   |
| [`WPFS::getPluginsPath()`](#getpluginspath)                           | Возвращает путь к директории `wp-content/plugins`.              | `FSReader`   |
| [`WPFS::getThemesPath()`](#getthemespath)                             | Возвращает путь к директории `wp-content/themes`.               | `FSReader`   |
| [`WPFS::getLangPath()`](#getlangpath)                                 | Возвращает путь к директории `wp-content/languages`.            | `FSReader`   |
| [`WPFS::getHumanReadablePermissions()`](#gethumanreadablepermissions) | Получает права доступа к файлу в формате `-rwxr-xr-x`.          | `FSReader`   |
| [`WPFS::getPermissions()`](#getpermissions)                           | Получает права доступа к файлу в восьмеричном формате (`0644`). | `FSReader`   |
| [`WPFS::getContents()`](#getcontents)                                 | Читает содержимое файла в виде строки.                          | `FSReader`   |
| [`WPFS::getContentsAsArray()`](#getcontentsasarray)                   | Читает содержимое файла в виде массива строк.                   | `FSReader`   |
| [`WPFS::getCurrentPath()`](#getcurrentpath)                           | Возвращает текущую рабочую директорию.                          | `FSReader`   |
| [`WPFS::getOwner()`](#getowner)                                       | Возвращает владельца файла.                                     | `FSReader`   |
| [`WPFS::getGroup()`](#getgroup)                                       | Возвращает группу файла.                                        | `FSReader`   |
| [`WPFS::getLastAccessedTime()`](#getlastaccessedtime)                 | Возвращает время последнего доступа к файлу.                    | `FSReader`   |
| [`WPFS::getLastModifiedTime()`](#getlastmodifiedtime)                 | Возвращает время последнего изменения файла.                    | `FSReader`   |
| [`WPFS::getFileSize()`](#getfilesize)                                 | Возвращает размер файла в байтах.                               | `FSReader`   |
| [`WPFS::getPermissionsAsOctal()`](#getpermissionsasoctal)             | Конвертирует права из формата `-rwxr-xr-x` в `0755`.            | `FSReader`   |
| [`WPFS::getDirectoryList()`](#getdirectorylist)                       | Получает детальный список файлов и директорий.                  | `FSReader`   |
| [`WPFS::getFiles()`](#getfiles)                                       | Получает рекурсивный список всех файлов в директории.           | `FSReader`   |
| [`WPFS::getUploadsDirInfo()`](#getuploadsdirinfo)                     | Получает информацию о директории загрузок (`uploads`).          | `FSReader`   |
| [`WPFS::getTempDir()`](#gettempdir)                                   | Получает путь к временной директории сервера.                   | `FSReader`   |
| [`WPFS::getNormalizePath()`](#getnormalizepath)                       | Нормализует путь (заменяет обратные слэши на прямые).           | `FSReader`   |
| [`WPFS::getSanitizeFilename()`](#getsanitizefilename)                 | Очищает имя файла от недопустимых символов.                     | `FSReader`   |
| [`WPFS::findFolder()`](#findfolder)                                   | Находит папку в стандартных директориях WordPress.              | `FSReader`   |
| [`WPFS::searchForFolder()`](#searchforfolder)                         | Ищет папку, начиная с указанной базовой директории.             | `FSReader`   |
| **FSAction**                                                          |                                                                 |              |
| [`WPFS::putContents()`](#putcontents)                                 | Записывает строку в файл.                                       | `FSAction`   |
| [`WPFS::copyFile()`](#copyfile)                                       | Копирует файл из одного места в другое.                         | `FSAction`   |
| [`WPFS::copyDirectory()`](#copydirectory)                             | Копирует директорию рекурсивно.                                 | `FSAction`   |
| [`WPFS::moveFile()`](#movefile)                                       | Перемещает файл.                                                | `FSAction`   |
| [`WPFS::moveDirectory()`](#movedirectory)                             | Перемещает директорию.                                          | `FSAction`   |
| [`WPFS::delete()`](#delete)                                           | Удаляет файл или директорию.                                    | `FSAction`   |
| [`WPFS::touch()`](#touch)                                             | Создает пустой файл или обновляет время его изменения.          | `FSAction`   |
| [`WPFS::createDirectory()`](#createdirectory)                         | Создает директорию.                                             | `FSAction`   |
| [`WPFS::createTempFile()`](#createtempfile)                           | Создает уникальный временный файл.                              | `FSAction`   |
| [`WPFS::deleteDirectory()`](#deletedirectory)                         | Удаляет директорию.                                             | `FSAction`   |
| [`WPFS::handleUpload()`](#handleupload)                               | Обрабатывает загруженный через `$_FILES` файл.                  | `FSAction`   |
| [`WPFS::handleSideload()`](#handlesideload)                           | Загружает файл со стороны (например, по URL).                   | `FSAction`   |
| [`WPFS::downloadFromUrl()`](#downloadfromurl)                         | Скачивает файл по URL во временный файл.                        | `FSAction`   |
| [`WPFS::unzip()`](#unzip)                                             | Распаковывает ZIP-архив.                                        | `FSAction`   |
| **FSAuditor**                                                         |                                                                 |              |
| [`WPFS::isBinary()`](#isbinary)                                       | Проверяет, является ли строка бинарной.                         | `FSAuditor`  |
| [`WPFS::isFile()`](#isfile)                                           | Проверяет, является ли путь файлом.                             | `FSAuditor`  |
| [`WPFS::isDirectory()`](#isdirectory)                                 | Проверяет, является ли путь директорией.                        | `FSAuditor`  |
| [`WPFS::isReadable()`](#isreadable)                                   | Проверяет, доступен ли файл/директория для чтения.              | `FSAuditor`  |
| [`WPFS::isWritable()`](#iswritable)                                   | Проверяет, доступен ли файл/директория для записи.              | `FSAuditor`  |
| [`WPFS::exists()`](#exists)                                           | Проверяет, существует ли файл или директория.                   | `FSAuditor`  |
| [`WPFS::connect()`](#connect)                                         | Устанавливает соединение с файловой системой.                   | `FSAuditor`  |
| [`WPFS::verifyMd5()`](#verifymd5)                                     | Проверяет MD5-хэш файла.                                        | `FSAuditor`  |
| [`WPFS::verifySignature()`](#verifysignature)                         | Проверяет цифровую подпись файла.                               | `FSAuditor`  |
| [`WPFS::isZipFile()`](#iszipfile)                                     | Проверяет, является ли файл валидным ZIP-архивом.               | `FSAuditor`  |
| **FSManager**                                                         |                                                                 |              |
| [`WPFS::ensureUniqueFilename()`](#ensureuniquefilename)               | Гарантирует уникальность имени файла в директории.              | `FSManager`  |
| [`WPFS::setOwner()`](#setowner)                                       | Устанавливает владельца файла/директории.                       | `FSManager`  |
| [`WPFS::setGroup()`](#setgroup)                                       | Устанавливает группу файла/директории.                          | `FSManager`  |
| [`WPFS::setPermissions()`](#setpermissions)                           | Устанавливает права доступа для файла/директории.               | `FSManager`  |
| [`WPFS::setCurrentDirectory()`](#setcurrentdirectory)                 | Изменяет текущую рабочую директорию.                            | `FSManager`  |
| [`WPFS::invalidateOpCache()`](#invalidateopcache)                     | Сбрасывает OPcache для конкретного файла.                       | `FSManager`  |
| [`WPFS::invalidateDirectoryOpCache()`](#invalidatedirectoryopcache)   | Сбрасывает OPcache для всей директории.                         | `FSManager`  |
| **FSAdvanced**                                                        |                                                                 |              |
| [`WPFS::atomicWrite()`](#atomicwrite)                                 | Атомарно (безопасно) записывает данные в файл.                  | `FSAdvanced` |
| [`WPFS::append()`](#append)                                           | Добавляет контент в конец файла.                                | `FSAdvanced` |
| [`WPFS::prepend()`](#prepend)                                         | Добавляет контент в начало файла.                               | `FSAdvanced` |
| [`WPFS::replace()`](#replace)                                         | Заменяет текст внутри файла.                                    | `FSAdvanced` |
| [`WPFS::extension()`](#extension)                                     | Возвращает расширение файла.                                    | `FSAdvanced` |
| [`WPFS::filename()`](#filename)                                       | Возвращает имя файла без расширения.                            | `FSAdvanced` |
| [`WPFS::dirname()`](#dirname)                                         | Возвращает путь к родительской директории файла.                | `FSAdvanced` |
| [`WPFS::cleanDirectory()`](#cleandirectory)                           | Удаляет все содержимое директории, не трогая ее саму.           | `FSAdvanced` |
| [`WPFS::isDirectoryEmpty()`](#isdirectoryempty)                       | Проверяет, пуста ли директория.                                 | `FSAdvanced` |
| [`WPFS::getMimeType()`](#getmimetype)                                 | Возвращает MIME-тип файла.                                      | `FSAdvanced` |
| [`WPFS::hash()`](#hash)                                               | Вычисляет хэш файла (по умолчанию MD5).                         | `FSAdvanced` |
| [`WPFS::filesEqual()`](#filesequal)                                   | Сравнивает два файла по их хэшу.                                | `FSAdvanced` |
| [`WPFS::readJson()`](#readjson)                                       | Читает и декодирует JSON-файл.                                  | `FSAdvanced` |
| [`WPFS::writeJson()`](#writejson)                                     | Записывает данные в файл в формате JSON.                        | `FSAdvanced` |
| [`WPFS::readXml()`](#readxml)                                         | Читает XML-файл и возвращает объект `SimpleXMLElement`.         | `FSAdvanced` |
| [`WPFS::writeXml()`](#writexml)                                       | Записывает данные в файл в формате XML.                         | `FSAdvanced` |
| [`WPFS::readDom()`](#readdom)                                         | Читает XML-файл и возвращает объект `DOMDocument`.              | `FSAdvanced` |
| [`WPFS::writeDom()`](#writedom)                                       | Записывает DOM-объект в XML-файл.                               | `FSAdvanced` |

#### Методы для чтения (FSReader)

Эта группа методов предназначена для получения информации о файлах, директориях и путях в WordPress.

---

#### `getHomePath()`

Возвращает абсолютный путь к корневой директории сайта WordPress.

**Метод фасада**: `WPFS::getHomePath(): string`

**Аналог в WordPress**: `get_home_path(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getHomePath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress`.

---

#### `getInstallationPath()`

Возвращает абсолютный путь к директории, где установлены файлы WordPress (где находится wp-config.php).

**Метод фасада**: `WPFS::getInstallationPath(): string`

**Аналог в WordPress**: `$wp_filesystem->abspath(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress`.

---

#### `getContentPath()`

Возвращает абсолютный путь к директории wp-content.

**Метод фасада**: `WPFS::getContentPath(): string`

**Аналог в WordPress**: `$wp_filesystem->wp_content_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content`.

---

#### `getPluginsPath(): string`

Возвращает путь к директории с плагинами.

**Метод фасада**: `WPFS::getPluginsPath(): string`

**Аналог в WordPress**: `$wp_filesystem->wp_plugins_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content/plugins`.

---

#### `getThemesPath()`

Возвращает путь к директории с темами. Может принимать необязательный аргумент — название конкретной темы.

**Метод фасада**: `WPFS::getThemesPath(string|false $theme = false): string`

**Аналог в WordPress**: `$wp_filesystem->wp_themes_dir(string|false $theme = false): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getThemesPath();
echo \Metapraxis\WPFileSystem\Facade\WPFS::getThemesPath('twentytwentyfour');
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content/themes`, `/var/www/html/wordpress/wp-content/themes/twentytwentyfour`.

---

#### `getLangPath()`

Возвращает путь к директории с языковыми файлами.

**Метод фасада**: `WPFS::getLangPath(): string`

**Аналог в WordPress**: `$wp_filesystem->wp_lang_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getLangPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content/languages`.

---

#### `getHumanReadablePermissions()`

Получает права доступа к файлу в символьном формате, привычном для *nix-систем.

**Метод фасада**: `WPFS::getHumanReadablePermissions(string $file): string`

**Аналог в WordPress**: `$wp_filesystem->gethchmod(string $file): string`.

```php
$path = \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath() . '/index.php';
echo \Metapraxis\WPFileSystem\Facade\WPFS::getHumanReadablePermissions($path);
```

_Возврат значения из примера будет равен_: `drwxr-xr-x`.

---

#### `getPermissions()`

Получает права доступа к файлу в восьмеричном формате.

**Метод фасада**: `WPFS::getPermissions(string $file): string()`

**Аналог в WordPress**: `$wp_filesystem->getchmod(string $file): string()`.

```php
$path = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
echo \Metapraxis\WPFileSystem\Facade\WPFS::getPermissions($path);
```

_Возврат значения из примера будет равен_: `0644`.

---

#### `getContents()`

Читает все содержимое файла и возвращает его в виде одной строки.

**Метод фасада**: `WPFS::getContents(string $file): string | false`.

**Аналог в WordPress**: `$wp_filesystem->get_contents(string $file): string | false`.

```php
$content = \Metapraxis\WPFileSystem\Facade\WPFS::getContents( __DIR__ . '/my-file.txt' );
echo $content;
```

_Возврат значения из примера будет равен_: `Hello World!`.

---

#### `getContentsAsArray()`

Читает содержимое файла и возвращает его в виде массива, где каждый элемент — это одна строка файла.

**Метод фасада**: `WPFS::getContentsAsArray(string $file): array`.

**Аналог в WordPress**: `$wp_filesystem->get_contents_array(string $file): array`.

```php
$lines = \Metapraxis\WPFileSystem\Facade\WPFS::getContentsAsArray( __DIR__ . '/my-file.txt' );
print_r($lines);
```

_Возврат значения из примера будет равен_: `['Hello World!']`.

---

#### `getCurrentPath()`

Возвращает текущую рабочую директорию.

**Метод фасада**: `WPFS::getCurrentPath(): string`.

**Аналог в WordPress**: `$wp_filesystem->cwd(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getCurrentPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-admin`.

---

#### `getOwner()`

Возвращает имя или ID владельца файла.

**Метод фасада**: `WPFS::getOwner(string $file): string`.

**Аналог в WordPress**: `$wp_filesystem->owner(string $file): string`.

```php
$owner = \Metapraxis\WPFileSystem\Facade\WPFS::getOwner( WPFS::getInstallationPath() . 'wp-config.php' );
echo $owner;
```

_Возврат значения из примера будет равен_: `www-data`.

---

#### `getGroup()`

Возвращает имя или ID группы файла.

**Метод фасада**: `WPFS::getGroup(string $file): string`.

**Аналог в WordPress**: `$wp_filesystem->group(string $file): string`.

```php
$group = \Metapraxis\WPFileSystem\Facade\WPFS::getGroup(\Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php' );
echo $group;
```

_Возврат значения из примера будет равен_: `www-data`.

---

#### `getLastAccessedTime()`

Возвращает время последнего доступа к файлу в виде Unix-timestamp.

**Метод фасада**: `WPFS::getLastAccessedTime(string $file): false | int`.

**Аналог в WordPress**: `$wp_filesystem->atime(string $file): false | int`.

```php
$lastAccess = \Metapraxis\WPFileSystem\Facade\WPFS::getLastAccessedTime( __FILE__ );
echo date('Y-m-d H:i:s', $lastAccess);
```

_Возврат значения из примера будет равен_: `2024-03-10 12:34:56`.

---

#### `getLastModifiedTime()`

Возвращает время последнего изменения файла в виде Unix-timestamp.

**Метод фасада**: `WPFS::getLastModifiedTime(string $file): int | false`.

**Аналог в WordPress**: `$wp_filesystem->mtime(string $file): int | false`.

```php
$lastModified = \Metapraxis\WPFileSystem\Facade\WPFS::getLastModifiedTime( __FILE__ );
echo date('Y-m-d H:i:s', $lastModified);
```

_Возврат значения из примера будет равен_: `2024-03-10 12:40:00`.

---

#### `getFileSize()`

Возвращает размер файла в байтах.

**Метод фасада**: `WPFS::getFileSize(string $file): int | false`.

**Аналог в WordPress**: `$wp_filesystem->size(string $file): int | false`.

```php
$sizeInBytes = \Metapraxis\WPFileSystem\Facade\WPFS::getFileSize( __FILE__ );
echo $sizeInBytes . ' bytes';
```

_Возврат значения из примера будет равен_: `14321`.

---

#### `getPermissionsAsOctal()`

Конвертирует права доступа из символьного формата (drwxr-xr-x) в восьмеричный (0755).

**Метод фасада**: `WPFS::getPermissionsAsOctal(string $mode): string`.

**Аналог в WordPress**: `$wp_filesystem->getnumchmodfromh(string $mode): string`.

```php
$octalPermissions = \Metapraxis\WPFileSystem\Facade\WPFS::getPermissionsAsOctal('drwxr-xr-x');
echo $octalPermissions;
```

_Возврат значения из примера будет равен_: `0755`.

---

#### `getDirectoryList()`

Получает детальный список файлов и директорий по указанному пути.

**Метод фасада**: `WPFS::getDirectoryList(string $path, bool $includeHidden = true, bool $recursive = false): array | false`.

**Аналог в WordPress**: `$wp_filesystem->dirlist(string $path, bool $includeHidden = true, bool $recursive = false): array | false`.

```php
$list = \Metapraxis\WPFileSystem\Facade\WPFS::getDirectoryList( \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath() );
print_r($list);
```

_Возврат значения из примера будет равен_: `['akismet' => ['name' => 'akismet','type' => 'd'], 'hello.php' => ['name' => 'hello.php','type' => 'f']]`.

---

#### `getFiles()`

Получает рекурсивный список всех файлов в директории.

**Метод фасада**: `WPFS::getFiles(string $folder = '', int $levels = 100, array $exclusions = [], bool $includeHidden = false): array | false`.

**Аналог в WordPress**: `list_files(string $folder = '', int $levels = 100, array $exclusions = [], bool $includeHidden = false): array | false`.

```php
$allFilesInPlugins = \Metapraxis\WPFileSystem\Facade\WPFS::getFiles( \Metapraxis\WPFileSystem\Facade\WPFS::getPluginsPath() );
print_r($allFilesInPlugins);
```

_Возврат значения из примера будет равен_: `['/var/www/html/wordpress/wp-content/plugins/akismet/akismet.php', '/var/www/html/wordpress/wp-content/plugins/hello.php']`.

---

#### `getUploadsDirInfo()`

Получает массив с информацией о директории загрузок (uploads), включая путь и URL.

**Метод фасада**: `WPFS::getUploadsDirInfo(): array`.

**Аналог в WordPress**: `wp_upload_dir(): array`.

```php
$uploads = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo();
echo 'Путь к загрузкам: ' . $uploads['basedir'];
```

_Возврат значения из примера будет равен_: `['path' => '/var/www/html/wordpress/wp-content/uploads/2025/09','url' => 'https://example.com/wp-content/uploads/2025/09', 'basedir' => '/var/www/html/wordpress/wp-content/uploads','baseurl' => 'https://example.com/wp-content/uploads', 'error' => false]`.

---

#### `getTempDir()`

Получает путь к директории для временных файлов на сервере.

**Метод фасада**: `WPFS::getTempDir(): string`.

**Аналог в WordPress**: `get_temp_dir(): string`.

```php
echo \Metapraxis\WPFileSystem\Facade\WPFS::getTempDir();
```

_Возврат значения из примера будет равен_: `/tmp`.

---

#### `getNormalizePath()`

Приводит путь к единому формату, заменяя обратные слэши (\) на прямые (/).

**Метод фасада**: `WPFS::getNormalizePath(string $path): string`.

**Аналог в WordPress**: `wp_normalize_path(string $path): string`.

```php
$normalized = \Metapraxis\WPFileSystem\Facade\WPFS::getNormalizePath('C:\\Users\\Admin');
echo $normalized;
```

_Возврат значения из примера будет равен_: `C:/Users/Admin`.

---

#### `getSanitizeFilename()`

Очищает имя файла от недопустимых символов, заменяя пробелы и спецсимволы на дефисы.

**Метод фасада**: `WPFS::getSanitizeFilename(string $filename): string`.

**Аналог в WordPress**: `sanitize_file_name(string $filename): string`.

```php
$safeName = \Metapraxis\WPFileSystem\Facade\WPFS::getSanitizeFilename('My Cool Image (2)?.jpg');
echo $safeName;
```

_Возврат значения из примера будет равен_: `My-Cool-Image-2.jpg`.

---

#### `findFolder()`

Находит папку в стандартных директориях WordPress.

**Метод фасада**: `WPFS::findFolder(string $folder): string`.

**Аналог в WordPress**: `$wp_filesystem->find_folder(string $folder): string`.

```php
$contentDir = \Metapraxis\WPFileSystem\Facade\WPFS::findFolder('wp-content');
echo $contentDir;
```

_Возврат значения из примера будет равен_: `/var/www/html/wprdpress/wp-content`.

---

#### `searchForFolder()`

Ищет указанную папку, начиная с определенной базовой директории.

**Метод фасада**: `WPFS::searchForFolder(string $folder, string $base = '.', bool $loop = false): string | false`.

**Аналог в WordPress**: `$wp_filesystem->search_for_folder(string $folder, string $base = '.', bool $loop = false): string | false`.

```php
$themesDir = \Metapraxis\WPFileSystem\Facade\WPFS::searchForFolder('themes', \Metapraxis\WPFileSystem\Facade\WPFS::getContentPath());
echo $themesDir;
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content/themes`.

---

#### Методы для записи и изменения (FSAction)

Эта группа методов предназначена для создания, изменения и удаления файлов и директорий.

---

#### `putContents()`

Записывает строковое содержимое в файл. Если файл не существует, он будет создан. Если существует — его содержимое
будет перезаписано.

**Метод фасада**: `WPFS::putContents(string $file, string $contents, ?int $mode = null): bool`.

**Аналог в WordPress**: `$wp_filesystem->put_contents(string $file, string $contents, ?int $mode = null): bool`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/my-log.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($filePath, 'User logged in at ' . date('Y-m-d H:i:s'));
```

_Возврат значения из примера будет равен_: `true`.

---

#### `copyFile()`

Копирует файл из одного места в другое.

**Метод фасада**: `WPFS::copyFile(string $source, string $destination, bool $overwrite = false, ?int $mode = null): bool`.

**Аналог в WordPress**: `$wp_filesystem->copy(string $source, string $destination, bool $overwrite = false, ?int $mode = null): bool`.

```php
$source = WP_CONTENT_DIR . '/uploads/source.txt';
$destination = WP_CONTENT_DIR . '/uploads/destination.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::copyFile($source, $destination, true); // true - разрешить перезапись
```

_Возврат значения из примера будет равен_: `true`.

---

#### `copyDirectory()`

Рекурсивно копирует директорию со всем ее содержимым.

**Метод фасада**: `WPFS::copyDirectory(string $from, string $to, array $skipList = []): bool`.

**Аналог в WordPress**: `copy_dir(string $from, string $to, array $skipList = []): bool`.

```php
$sourceDir = WP_PLUGIN_DIR . '/my-plugin/assets';
$destDir = WP_CONTENT_DIR . '/uploads/my-plugin-assets';
\Metapraxis\WPFileSystem\Facade\WPFS::copyDirectory($sourceDir, $destDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `moveFile()`

Перемещает (или переименовывает) файл.

**Метод фасада**: `WPFS::moveFile(string $source, string $destination, bool $overwrite = false): bool`.

**Аналог в WordPress**: `$wp_filesystem->move(string $source, string $destination, bool $overwrite = false): bool`.

```php
$oldPath = WP_CONTENT_DIR . '/uploads/temp.log';
$newPath = WP_CONTENT_DIR . '/uploads/archived.log';
\Metapraxis\WPFileSystem\Facade\WPFS::moveFile($oldPath, $newPath, true);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `moveDirectory()`

Перемещает (или переименовывает) директорию.

**Метод фасада**: `WPFS::moveDirectory(string $from, string $to, bool $overwrite = false): bool`.

**Аналог в WordPress**: `move_dir(string $from, string $to, bool $overwrite = false): bool`.

```php
$oldDir = WP_CONTENT_DIR . '/uploads/temp-files';
$newDir = WP_CONTENT_DIR . '/uploads/permanent-files';
\Metapraxis\WPFileSystem\Facade\WPFS::moveDirectory($oldDir, $newDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `delete()`

Удаляет файл или директорию. Для удаления непустой директории необходимо установить второй параметр в true.

**Метод фасада**: `WPFS::delete(string $path, bool $recursive = false, ?string $type = null): bool`.

**Аналог в WordPress**: `$wp_filesystem->delete(string $path, bool $recursive = false, ?string $type = null): bool`.

```php
// Удаление файла
\Metapraxis\WPFileSystem\Facade\WPFS::delete( WP_CONTENT_DIR . '/uploads/stale-file.txt' );

// Рекурсивное удаление директории
\Metapraxis\WPFileSystem\Facade\WPFS::delete( WP_CONTENT_DIR . '/uploads/old-cache', true );

```

_Возврат значения из примера будет равен_: `true`.

---

#### `touch()`

Создает пустой файл, если он не существует, или обновляет его время последнего изменения, если он уже существует.

**Метод фасада**: `WPFS::touch(string $file, int $mtime = 0, int $atime = 0): bool`.

**Аналог в WordPress**: `$wp_filesystem->touch(string $file, int $mtime = 0, int $atime = 0): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::touch( WP_CONTENT_DIR . '/uploads/cron-last-run.lock' );
```

_Возврат значения из примера будет равен_: `true`.

---

#### `createDirectory()`

Создает новую директорию.

**Метод фасада**: `WPFS::createDirectory(string $path, ?int $chmod = null, mixed $chown = null, mixed $chgrp = null): bool`.

**Аналог в WordPress**: `$wp_filesystem->mkdir(string $path, ?int $chmod = null, mixed $chown = null, mixed $chgrp = null): bool`.

```php
$logsDir = WP_CONTENT_DIR . '/logs';
\Metapraxis\WPFileSystem\Facade\WPFS::createDirectory($logsDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `createTempFile()`

Создает уникальный временный файл в системной временной директории.

**Метод фасада**: `WPFS::createTempFile(string $filename = '', string $dir = ''): string | false`.

**Аналог в WordPress**: `wp_tempnam(string $filename = '', string $dir = ''): string | false`.

```php
$tempFilePath = \Metapraxis\WPFileSystem\Facade\WPFS::createTempFile('my_app_');
echo $tempFilePath;
```

_Возврат значения из примера будет равен_: `true`.

---

#### `deleteDirectory()`

Удаляет директорию. Является псевдонимом для delete($path, true).

**Метод фасада**: `WPFS::deleteDirectory(string $path, bool $recursive = false): bool`.

**Аналог в WordPress**: `$wp_filesystem->rmdir(string $path, bool $recursive = false): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::deleteDirectory( WP_CONTENT_DIR . '/uploads/temp-backup', true );
```

_Возврат значения из примера будет равен_: `true`.

---

#### `handleUpload()`

Обрабатывает файл, загруженный через стандартную HTML-форму (массив $_FILES), и перемещает его в директорию загрузок
WordPress.

**Метод фасада**: `WPFS::handleUpload(array $file, array|false $overrides = false, ?string $time = null): array`.

**Аналог в WordPress**: `wp_handle_upload(array $file, array|false $overrides = false, ?string $time = null): array`.

```php
if ( ! empty($_FILES['my_image_upload']) ) {
    $result = \Metapraxis\WPFileSystem\Facade\WPFS::handleUpload($_FILES['my_image_upload'], ['test_form' => false]);
}
```

_Возврат значения из примера будет равен_: `['file' => '...', 'url' => '...', 'type' => '...']`.

---

#### `handleSideload()`

Загружает файл со стороны (например, из временного файла, полученного по URL) и перемещает его в директорию загрузок.

**Метод фасада**: `WPFS::handleSideload(array $file, array|false $overrides = false, ?string $time = null): array`.

**Аналог в WordPress**: `wp_handle_sideload(array $file, array|false $overrides = false, ?string $time = null): array`.

```php
$file = ['name' => 'image.jpg', 'tmp_name' => '/tmp/some_image.jpg'];
$result = \Metapraxis\WPFileSystem\Facade\WPFS::handleSideload($file, ['test_form' => false]);
```

_Возврат значения из примера будет равен_: `['file' => '...', 'url' => '...', 'type' => '...']`.

---

#### `downloadFromUrl()`

Загружает файл со стороны (например, из временного файла, полученного по URL) и перемещает его в директорию загрузок.

**Метод фасада**: `WPFS::downloadFromUrl(string $url, int $timeout = 300, bool $signatureVerification = false): string | false`.

**Аналог в WordPress**: `download_url(string $url, int $timeout = 300, bool $signatureVerification = false): string | false`.

```php
$url = 'https://downloads.wordpress.org/plugin/akismet.latest.zip';
$tempFile = \Metapraxis\WPFileSystem\Facade\WPFS::downloadFromUrl($url);
```

_Возврат значения из примера будет равен_: `/tmp/akismet.latest.zip.tmp`.

---

#### `unzip()`

Распаковывает ZIP-архив в указанную директорию.

**Метод фасада**: `WPFS::unzip(string $file, string $to): bool`.

**Аналог в WordPress**: `unzip_file(string $file, string $to): bool`.

```php
$url = 'https://downloads.wordpress.org/plugin/akismet.latest.zip';
$tempFile = \Metapraxis\WPFileSystem\Facade\WPFS::downloadFromUrl($url);
```

_Возврат значения из примера будет равен_: `true`.

---

### Методы для аудита (FSAuditor)

Эта группа методов предназначена для проверки состояния файлов и директорий (существование, права доступа, тип)
без изменения каких-либо данных.

---

#### `isBinary()`

Проверяет, содержит ли строка бинарные символы (например, нулевые байты).

**Метод фасада**: `WPFS::isBinary(string $text): bool`.

**Аналог в WordPress**: `$wp_filesystem->is_binary(): bool`.

```php
$binaryContent = "Это бинарный текст с нулевым байтом \x00 внутри.";
$isBinary = \Metapraxis\WPFileSystem\Facade\WPFS::isBinary($binaryContent);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isFile()`

Проверяет, является ли указанный путь файлом.

**Метод фасада**: `WPFS::isFile(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->is_file(): bool`.

```php
$filePath = WP_PLUGIN_DIR . '/hello.php';
$isFile = \Metapraxis\WPFileSystem\Facade\WPFS::isFile($filePath);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isDirectory()`

Проверяет, является ли указанный путь директорией.

**Метод фасада**: `WPFS::isDirectory(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->is_dir(): bool`.

```php
$dirPath = WP_PLUGIN_DIR;
$isDir = \Metapraxis\WPFileSystem\Facade\WPFS::isDirectory($dirPath);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isReadable()`

Проверяет, доступен ли файл или директория для чтения текущим пользователем сервера.

**Метод фасада**: `WPFS::isReadable(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->is_readable(string $path): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
$isReadable = \Metapraxis\WPFileSystem\Facade\WPFS::isReadable($filePath);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isWritable()`

Проверяет, доступен ли файл или директория для записи текущим пользователем сервера.

**Метод фасада**: `WPFS::isWritable(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->is_writable(string $path): bool`.

```php
$uploadsDir = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'];
$isWritable = \Metapraxis\WPFileSystem\Facade\WPFS::isWritable($uploadsDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `exists()`

Проверяет, существует ли файл или директория по указанному пути.

**Метод фасада**: `WPFS::exists(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->exists(string $path): bool`.

```php
$configFile = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';
$exists = \Metapraxis\WPFileSystem\Facade\WPFS::exists($configFile);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `connect()`

Устанавливает соединение с файловой системой. Обычно вызывается ядром автоматически, но может быть полезно для отладки.

**Метод фасада**: `WPFS::connect(): bool`.

**Аналог в WordPress**: `$wp_filesystem->connect(string $path): bool`.

```php
$connected = \Metapraxis\WPFileSystem\Facade\WPFS::connect();
```

_Возврат значения из примера будет равен_: `true`.

---

#### `verifyMd5()`

Проверяет MD5-хэш файла на соответствие ожидаемому значению.

**Метод фасада**: `WPFS::verifyMd5(string $filename, string $expectedMd5): bool | int`.

**Аналог в WordPress**: `verify_file_md5(string $filename, string $expectedMd5): bool | int`.

```php
$connected = \Metapraxis\WPFileSystem\Facade\WPFS::connect();
```

_Возврат значения из примера будет равен_: `true`.

---

#### `verifySignature()`

Проверяет цифровую подпись файла (используется ядром WordPress при обновлениях для проверки подлинности).

**Метод фасада**: `WPFS::verifySignature(string $filename, string|array $signatures, string|false $filenameForErrors = false): bool`.

**Аналог в WordPress**: `verify_file_signature(string $filename, string|array $signatures, string|false $filenameForErrors = false): bool`.

```php
$coreFile = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-includes/version.php';
$signatures = ['...some_valid_signature_from_wordpress_org...'];
$isValid = \Metapraxis\WPFileSystem\Facade\WPFS::verifySignature($coreFile, $signatures);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isZipFile()`

Проверяет, является ли указанный файл валидным ZIP-архивом.

**Метод фасада**: `WPFS::isZipFile(string $file): bool`.

**Аналог в WordPress**: `wp_zip_file_is_valid(string $file): bool`.

```php
$zipFile = WP_CONTENT_DIR . '/upgrade/some-plugin.zip';
$isZip = \Metapraxis\WPFileSystem\Facade\WPFS::isZipFile($zipFile);
```

_Возврат значения из примера будет равен_: `true`.

---

### Методы для управления (FSManager)

Эта группа методов предназначена для управления свойствами файлов и директорий, такими как права доступа, владелец,
а также для взаимодействия с серверными утилитами, например, OPcache.

---

#### `ensureUniqueFilename()`

Гарантирует, что имя файла будет уникальным в указанной директории. Если файл с таким именем уже существует,
к нему будет добавлен числовой суффикс (например, image-1.jpg).

**Метод фасада**: `WPFS::ensureUniqueFilename(string $dir, string $filename, ?callable $unique_filename_callback = null): string`.

**Аналог в WordPress**: `wp_unique_filename(string $dir, string $filename, ?callable $unique_filename_callback = null): string`.

```php
$uploadsDir = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'];

\Metapraxis\WPFileSystem\Facade\WPFS::touch($uploadsDir . '/my-picture.jpg');
$uniqueName = \Metapraxis\WPFileSystem\Facade\WPFS::ensureUniqueFilename($uploadsDir, 'my-picture.jpg');
```

_Возврат значения из примера будет равен_: `my-picture-1.jpg`.

---

#### `setOwner()`

Изменяет владельца файла или директории. Требует соответствующих прав на сервере.

**Метод фасада**: `WPFS::setOwner(string $file, string|int $owner, bool $recursive = false): bool`.

**Аналог в WordPress**: `$wp_filesystem->chown(string $file, string|int $owner, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';

$result = \Metapraxis\WPFileSystem\Facade\WPFS::setOwner($filePath, 'www-data');
```

_Возврат значения из примера будет равен_: `true`.

---

#### `setGroup()`

Изменяет группу файла или директории. Требует соответствующих прав на сервере.

**Метод фасада**: `WPFS::setGroup(string $file, string|int $group, bool $recursive = false): bool`.

**Аналог в WordPress**: `$wp_filesystem->chgrp(string $file, string|int $group, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getInstallationPath() . 'wp-config.php';

$result = \Metapraxis\WPFileSystem\Facade\WPFS::setGroup($filePath, 'www-data');
```

_Возврат значения из примера будет равен_: `true`.

---

#### `setPermissions()`

Устанавливает права доступа для файла или директории в восьмеричном формате.

**Метод фасада**: `WPFS::setPermissions(string $file, ?int $mode = null, bool $recursive = false): bool`.

**Аналог в WordPress**: `$wp_filesystem->chmod(string $file, ?int $mode = null, bool $recursive = false): bool`.

```php
$filePath = \Metapraxis\WPFileSystem\Facade\WPFS::getUploadsDirInfo()['basedir'] . '/secret.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($filePath, 'secret data');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::setPermissions($filePath, 0600);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `setCurrentDirectory()`

Изменяет текущую рабочую директорию для файловой системы.

**Метод фасада**: `WPFS::setCurrentDirectory(string $path): bool`.

**Аналог в WordPress**: `$wp_filesystem->chdir(string $path): bool`.

```php
\Metapraxis\WPFileSystem\Facade\WPFS::setCurrentDirectory( WP_PLUGIN_DIR );
$current = \Metapraxis\WPFileSystem\Facade\WPFS::getCurrentPath();
```

_Возврат значения из примера будет равен_: `/var/www/html/wordpress/wp-content/plugins`.

---

#### `invalidateOpCache()`

Сбрасывает (делает недействительным) OPcache для конкретного PHP-файла. Полезно после программного изменения файла,
чтобы PHP использовал новую версию.

**Метод фасада**: `WPFS::invalidateOpCache(string $filepath, bool $force = false): bool`.

**Аналог в WordPress**: `wp_opcache_invalidate(string $filepath, bool $force = false): bool`.

```php
$filePath = WP_PLUGIN_DIR . '/my-plugin/functions.php';
// ... здесь код, который изменяет файл $filePath ...
$result = \Metapraxis\WPFileSystem\Facade\WPFS::invalidateOpCache($filePath, true);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `invalidateDirectoryOpCache()`

Рекурсивно сбрасывает (делает недействительным) OPcache для всех PHP-файлов в указанной директории.

**Метод фасада**: `WPFS::invalidateDirectoryOpCache(string $dir): void`.

**Аналог в WordPress**: `wp_opcache_invalidate_directory(string $dir): void`.

```php
$pluginDir = WP_PLUGIN_DIR . '/my-plugin';
\Metapraxis\WPFileSystem\Facade\WPFS::invalidateDirectoryOpCache($pluginDir);
```

_Возврат значения из примера будет равен_: `void`.

---

### Методы для продвинутых операций (FSAdvanced)

Эта группа методов предоставляет высокоуровневые утилиты для работы с файлами и директориями, которые не входят
в стандартный набор WP_Filesystem, но часто бывают полезны в современной разработке.

---

#### `atomicWrite()`

Атомарно (безопасно) записывает данные в файл. Этот метод гарантирует, что файл не будет поврежден,
если запись прервется, так как сначала данные пишутся во временный файл, который затем переименовывается в основной.

Метод фасада: `WPFS::atomicWrite(string $path, string $content, ?int $mode = null): bool`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/config.json';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::atomicWrite($filePath, '{"key":"value"}');
```

_Возврат значения из примера будет равен_: `true`.

---

#### `append()`

Добавляет указанный контент в конец файла.

Метод фасада: `WPFS::append(string $path, string $content): bool`.

```php
$logFile = WP_CONTENT_DIR . '/debug.log';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($logFile, "Initial event.\n");
$result = \Metapraxis\WPFileSystem\Facade\WPFS::append($logFile, "Another event.\n");
```

_Возврат значения из примера будет равен_: `true`.

---

#### `prepend()`

Добавляет указанный контент в начало файла.

Метод фасада: `WPFS::prepend(string $path, string $content): bool`.

```php
$listFile = WP_CONTENT_DIR . '/list.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($listFile, "Item 2");
$result = \Metapraxis\WPFileSystem\Facade\WPFS::prepend($listFile, "Item 1\n");
```

_Возврат значения из примера будет равен_: `true`.

---

#### `replace()`

Находит и заменяет текст внутри файла.

Метод фасада: `WPFS::replace(string $path, string|array $search, string|array $replace): bool`.

```php
$configFile = WP_CONTENT_DIR . '/config.ini';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($configFile, 'mode = development');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::replace($configFile, 'development', 'production');
```

_Возврат значения из примера будет равен_: `true`.

---

#### `extension()`

Возвращает расширение файла из указанного пути.

Метод фасада: `WPFS::extension(string $path): string`.

```php
$extension = \Metapraxis\WPFileSystem\Facade\WPFS::extension('path/to/image.jpeg');
```

_Возврат значения из примера будет равен_: `jpeg`.

---

#### `filename()`

Возвращает имя файла из указанного пути без расширения.

Метод фасада: `WPFS::filename(string $path): string`.

```php
$name = \Metapraxis\WPFileSystem\Facade\WPFS::filename('path/to/archive.tar.gz');
```

_Возврат значения из примера будет равен_: `archive.tar`.

---

#### `dirname()`

Возвращает путь к родительской директории файла.

Метод фасада: `WPFS::dirname(string $path): string`.

```php
$dir = \Metapraxis\WPFileSystem\Facade\WPFS::dirname('/var/www/html/wp-content/plugins/my-plugin/main.php');
```

_Возврат значения из примера будет равен_: `/var/www/html/wp-content/plugins/my-plugin`.

---

#### `cleanDirectory()`

Удаляет все файлы и поддиректории внутри указанной директории, не удаляя саму директорию.

Метод фасада: `WPFS::cleanDirectory(string $directory): bool`.

```php
$cacheDir = WP_CONTENT_DIR . '/cache/my-plugin';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::cleanDirectory($cacheDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `isDirectoryEmpty()`

Проверяет, является ли директория пустой.

Метод фасада: `WPFS::isDirectoryEmpty(string $directory): bool`.

```php
$emptyDir = WP_CONTENT_DIR . '/uploads/empty-folder';
\Metapraxis\WPFileSystem\Facade\WPFS::createDirectory($emptyDir);
$isEmpty = \Metapraxis\WPFileSystem\Facade\WPFS::isDirectoryEmpty($emptyDir);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `getMimeType()`

Возвращает MIME-тип файла.

Метод фасада: `WPFS::getMimeType(string $path): string | false`.

```php
$filePath = WP_PLUGIN_DIR . '/hello.php';
$mime = \Metapraxis\WPFileSystem\Facade\WPFS::getMimeType($filePath);
```

_Возврат значения из примера будет равен_: `text/plain` (или `application/x-httpd-php` в зависимости от настроек сервера)..

---

#### `hash()`

Вычисляет хэш файла (по умолчанию используется алгоритм md5).

Метод фасада: `WPFS::hash(string $path, string $algorithm = 'md5'): string | false`.

```php
$filePath = WP_CONTENT_DIR . '/uploads/image.jpg';
$hash = \Metapraxis\WPFileSystem\Facade\WPFS::hash($filePath, 'sha256');
```

_Возврат значения из примера будет равен_: строка с SHA256-хэшем файла.

---

#### `filesEqual()`

Сравнивает два файла по их содержимому (через MD5-хэш) и возвращает true, если они идентичны.

Метод фасада: `WPFS::filesEqual(string $path1, string $path2): bool`.

```php
$file1 = WP_CONTENT_DIR . '/file1.txt';
$file2 = WP_CONTENT_DIR . '/file2.txt'; // Точная копия file1.txt
$areEqual = \Metapraxis\WPFileSystem\Facade\WPFS::filesEqual($file1, $file2);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `readJson()`

Читает и декодирует JSON-файл, возвращая результат в виде ассоциативного массива или объекта.

Метод фасада: `WPFS::readJson(string $path, bool $assoc = true): mixed`.

```php
$jsonPath = WP_CONTENT_DIR . '/data.json';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($jsonPath, '{"id": 1, "name": "Test"}');
$data = \Metapraxis\WPFileSystem\Facade\WPFS::readJson($jsonPath);
```

_Возврат значения из примера будет равен_: `['id' => 1, 'name' => 'Test']`.

---

#### `writeJson()`

Записывает массив или объект в файл в формате JSON. По умолчанию форматирует вывод для читаемости.

Метод фасада: `WPFS::writeJson(string $path, mixed $data, int $options = JSON_PRETTY_PRINT): bool`.

```php
$settingsPath = WP_CONTENT_DIR . '/settings.json';
$settings = ['theme' => 'dark', 'version' => '1.2.0'];
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeJson($settingsPath, $settings);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `readXml()`

Читает XML-файл и возвращает его содержимое в виде объекта SimpleXMLElement.

Метод фасада: `WPFS::readXml(string $path): SimpleXMLElement|false`.

```php
$xmlPath = WP_CONTENT_DIR . '/feed.xml';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($xmlPath, '<items><item>First</item></items>');
$xmlObject = \Metapraxis\WPFileSystem\Facade\WPFS::readXml($xmlPath);
```

_Возврат значения из примера будет равен_: `SimpleXMLElement`.

---

#### `writeXml()`

Записывает XML-данные (строку, SimpleXMLElement или DOMDocument) в файл.

Метод фасада: `WPFS::writeXml(string $path, DOMDocument|SimpleXMLElement|string $xml): bool`.

```php
$newXmlPath = WP_CONTENT_DIR . '/new_feed.xml';
$xmlString = '<products><product id="1">Book</product></products>';
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeXml($newXmlPath, $xmlString);
```

_Возврат значения из примера будет равен_: `true`.

---

#### `readDom()`

Читает XML-файл и возвращает его содержимое в виде объекта DOMDocument.

Метод фасада: `WPFS::readDom(string $path): DOMDocument | false`.

```php
$xmlPath = WP_CONTENT_DIR . '/sitemap.xml';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($xmlPath, '<urlset><url><loc>[https://example.com](https://example.com)</loc></url></urlset>');
$domObject = \Metapraxis\WPFileSystem\Facade\WPFS::readDom($xmlPath);
```

_Возврат значения из примера будет равен_: `DOMDocument`.

---

#### `writeDom()`

Записывает объект DOMDocument в XML-файл. Является синонимом метода writeXml().

Метод фасада: `WPFS::writeDom(string $path, DOMDocument|SimpleXMLElement|string $dom): bool`.

```php
$newXmlPath = WP_CONTENT_DIR . '/new_sitemap.xml';
$dom = new DOMDocument();
$dom->loadXML('<pages><page>Home</page></pages>');
$result = \Metapraxis\WPFileSystem\Facade\WPFS::writeDom($newXmlPath, $dom);
```

_Возврат значения из примера будет равен_: `true`.
