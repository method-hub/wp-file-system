# Документация по API FSBuilder

FSBuilder предоставляет текучий (fluent) интерфейс для создания и управления файлами. Он позволяет объединять сложные 
файловые операции в последовательность вызовов методов, делая код более читаемым и выразительным. Работа начинается со 
статических методов `from()` (для существующих файлов) или `create()` (для новых), после чего применяются 
методы-модификаторы и, наконец, вызывается терминальный метод (например, `save()`).

Смотрите [примеры использования](#examples) построителя запросов.

## Содержание API

| Метод                                   | Краткое описание                                                              | Группа       |
|:----------------------------------------|:------------------------------------------------------------------------------|:-------------|
| **Методы создания**                     |                                                                               |              |
| [`FSBuilder::from()`](#from)            | Начинает цепочку с существующего файла, загружая его содержимое.              | `FSReader`   |
| [`FSBuilder::create()`](#create)        | Начинает цепочку для нового файла (или для полной перезаписи).                | `FSReader`   |
| **Методы модификации контента**         |                                                                               |              |
| [`->setContent`](#setcontent)           | Полностью заменяет содержимое в буфере.                                       | `FSReader`   |
| [`->append`](#append)                   | Добавляет строку в конец содержимого буфера.                                  | `FSReader`   |
| [`->prepend`](#prepend)                 | Добавляет строку в начало содержимого буфера.                                 | `FSReader`   |
| [`->replace`](#replace)                 | Выполняет поиск и замену в содержимом буфера.                                 | `FSReader`   |
| [`->replaceRegex`](#replaceregex)       | Выполняет поиск и замену по регулярному выражению.                            | `FSReader`   |
| [`->transform`](#transform)             | Применяет пользовательскую функцию обратного вызова к содержимому.            | `FSReader`   |
| **Условные методы**                     |                                                                               |              |
| [`->when`](#when)                       | Выполняет обратный вызов, если условие истинно.                               | `FSReader`   |
| [`->unless`](#unless)                   | Выполняет обратный вызов, если условие ложно.                                 | `FSReader`   |
| **Методы атрибутов**                    |                                                                               |              |
| [`->withPermissions`](#withpermissions) | Устанавливает права доступа к файлу, которые будут применены при сохранении.  | `FSReader`   |
| [`->withOwner`](#withowner)             | Устанавливает владельца и/или группу, которые будут применены при сохранении. | `FSReader`   |
| [`->backup`](#backup)                   | Создает резервную копию исходного файла.                                      | `FSReader`   |
| **Терминальные методы**                 |                                                                               |              |
| [`->save`](#save)                       | Сохраняет содержимое буфера в целевой файл.                                   | `FSReader`   |
| [`->get`](#get)                         | Возвращает содержимое из буфера без сохранения на диск.                       | `FSReader`   |
| [`->move`](#move)                       | Перемещает исходный файл в новое место.                                       | `FSReader`   |
| [`->delete`](#delete)                   | Удаляет исходный файл.                                                        | `FSReader`   |


### Методы создания

Эти статические методы используются для инициализации экземпляра FSBuilder.

---

#### `from()`

Начинает цепочку вызовов для существующего файла, загружая его содержимое в буфер.

**Сигнатура**: `FSBuilder::from(string $path, WP_Filesystem_Base $fs): self`

**Исключения**: `FSPathNotFoundException` - если файл по указанному пути не найден.

```php
global $wp_filesystem;

$path = WP_CONTENT_DIR . '/uploads/my-file.txt';
// Предполагается, что файл существует и содержит 'initial content'
$builder = \Metapraxis\WPFileSystem\Builder\FSBuilder::from($path, $wp_filesystem);

echo $builder->get();
```

_Возврат значения из примера будет равен_: `initial content`.

---

#### `create()`

Начинает цепочку вызовов для нового файла или для полной перезаписи существующего. Буфер содержимого изначально пуст.

**Сигнатура**: `FSBuilder::create(string $path, WP_Filesystem_Base $fs): self`

```php
global $wp_filesystem;

$path = WP_CONTENT_DIR . '/uploads/new-log.txt';
$builder = \Metapraxis\WPFileSystem\Builder\FSBuilder::create($path, $wp_filesystem);

// На данный момент файл еще не создан на диске
var_dump($builder->get());
```

_Возврат значения из примера будет равен_: `NULL`.

---

### Методы модификации контента

Эти методы изменяют содержимое внутреннего буфера FSBuilder.

---

#### `setContent()`

Полностью заменяет содержимое в буфере.

**Сигнатура**: `->setContent(string $content): self`

```php
$builder->setContent("First line.\n");
$builder->setContent("This will overwrite the first line.");
```

_Возврат значения из примера будет равен_: `This will overwrite the first line.`.

---

#### `append()`

Добавляет строку в конец содержимого буфера.

**Сигнатура**: `->append(string $data): self`

```php
$builder->setContent("First line.")->append(" Second part.");
```

_Возврат значения из примера будет равен_: `First line. Second part.`.

---

#### `prepend()`

Добавляет строку в начало содержимого буфера.

**Сигнатура**: `->prepend(string $data): self`

```php
$builder->setContent("Second part.")->prepend("First part. ");
```

_Возврат значения из примера будет равен_: `First part. Second part.`.

---

#### `replace()`

Выполняет поиск и замену подстроки в содержимом буфера.

**Сигнатура**: `->replace(string|string[] $search, string|string[] $replace): self`

```php
$builder->setContent("Hello world!")->replace("world", "WordPress");
```

_Возврат значения из примера будет равен_: `Hello WordPress!`.

---

#### `replaceRegex()`

Выполняет поиск и замену по регулярному выражению в содержимом буфера.

**Сигнатура**: `->replaceRegex(string $pattern, string|callable $replacement): self`

```php
$builder->setContent("Error on line 42.")->replaceRegex('/[0-9]+/', '100');
```

_Возврат значения из примера будет равен_: `Error on line 100.`.

---

#### `transform()`

Применяет пользовательскую функцию обратного вызова к содержимому буфера.

**Сигнатура**: `->transform(callable $callback): self`

```php
$builder->setContent("some text")->transform('strtoupper');
```

_Возврат значения из примера будет равен_: `SOME TEXT`.

---

### Условные методы

Эти методы позволяют выполнять операции в цепочке только при соблюдении определенных условий.

---

#### `when()`

Выполняет переданную функцию обратного вызова, если условие истинно.

**Сигнатура**: `->when(bool|callable $condition, callable $callback): self`

```php
$builder->setContent("User: Guest")
        ->when(true, function($builder) {
            $builder->replace("Guest", "Admin");
        });
```

_Возврат значения из примера будет равен_: `User: Admin`.

---

#### `unless()`

Выполняет переданную функцию обратного вызова, если условие ложно.

**Сигнатура**: `->unless(bool|callable $condition, callable $callback): self`

```php
$isDebug = false;
$builder->setContent("Log:")
        ->unless($isDebug, function($builder) {
            $builder->append(" Production mode.");
        });
```

_Возврат значения из примера будет равен_: `Log: Production mode.`.

### Методы атрибутов

Эти методы позволяют управлять метаданными и состоянием файла.

---

#### `withPermissions()`

Устанавливает права доступа к файлу (в восьмеричном формате), которые будут применены при вызове метода `save()`.

**Сигнатура**: `->withPermissions(int $mode): self`

```php
$builder->setContent("secret data")->withPermissions(0600)->save();
```

_Возврат значения из примера будет равен_: `0600`.

---

#### `withOwner()`

Устанавливает владельца и/или группу файла, которые будут применены при вызове метода `save()`.

**Сигнатура**: `->withOwner(string|int|null $user, string|int|null $group = null): self`

```php
$builder->setContent("content")->withOwner('www-data', 'www-data')->save();
```

_Возврат значения из примера будет равен_: `www-data`.

---

#### `backup()`

Создает резервную копию текущего файла перед его изменением.

**Сигнатура**: `->backup(string $suffix = '.bak'): self`

```php
global $wp_filesystem;
$path = WP_CONTENT_DIR . '/uploads/config.txt';
\Metapraxis\WPFileSystem\Facade\WPFS::putContents($path, 'version=1');

\Metapraxis\WPFileSystem\Builder\FSBuilder::from($path, $wp_filesystem)
    ->backup()
    ->setContent('version=2')
    ->save();
```

**Действие**: Будет создан файл config.txt.bak с содержимым version=1, а config.txt будет содержать version=2.

---

### Терминальные методы

Эти методы завершают цепочку вызовов, выполняя финальное действие.

---

#### `save()`

Сохраняет содержимое буфера в целевой файл на диске.

**Сигнатура**: `->save(): bool`

```php
$success = $builder->setContent("Final content.")->save();
```

_Возврат значения из примера будет равен_: `true`.

---

#### `get()`

Возвращает текущее содержимое буфера без сохранения на диск.

**Сигнатура**: `->get(): ?string`

```php
$content = $builder->setContent("temporary data")->get();
```

_Возврат значения из примера будет равен_: `temporary data`.

---

#### `move()`

Перемещает исходный файл в новое место.

**Сигнатура**: `->move(string $newPath, bool $overwrite = false): bool`

```php
$wasMoved = $builder->move(WP_CONTENT_DIR . '/uploads/archive/config.txt');
```

**Действие**: Перемещает файл, с которым был инициализирован строитель.
_Возврат значения из примера будет равен_: `true`, если перемещение прошло успешно.

---

#### `delete()`

Удаляет исходный файл.

**Сигнатура**: `->delete(): bool`

```php
$wasDeleted = $builder->delete();
```

**Действие**: Удаляет файл, с которым был инициализирован строитель.
_Возврат значения из примера будет равен_: `true`, если удаление прошло успешно.

---

#### Examples

_Пример 1: Создание и ротация файла логов с условиями_

Этот пример демонстрирует, как можно создать ежедневный лог, добавить в него информацию в зависимости от окружения, 
а затем архивировать старый лог.

```php
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$logDir = WP_CONTENT_DIR . '/logs';
$logFile = $logDir . '/debug.log';
$archiveFile = $logDir . '/archives/debug-' . date('Y-m-d') . '.log.bak';

// Используем фасад WPFS для подготовки директорий
if (!WPFS::isDirectory($logDir)) {
    WPFS::createDirectory($logDir);
}
if (!WPFS::isDirectory(dirname($archiveFile))) {
    WPFS::createDirectory(dirname($archiveFile));
}

// Архивируем вчерашний лог, если он существует
if (WPFS::exists($logFile)) {
    FSBuilder::from($logFile, $wp_filesystem)
        ->move($archiveFile, true); // Перемещаем с перезаписью, если архив за сегодня уже есть
}

// Создаем новый лог-файл с помощью строителя
FSBuilder::create($logFile, $wp_filesystem)
    ->append("Log started at: " . date('H:i:s') . "\n")
    ->append("Operation: Critical Task\n")
    ->when(defined('WP_DEBUG') && WP_DEBUG, function ($builder) {
        // Добавляем отладочную информацию, только если WP_DEBUG включен
        $builder->append("DEBUG MODE: Verbose logging enabled.\n");
        $builder->append("Memory usage: " . memory_get_usage() . "\n");
    })
    ->unless(is_user_logged_in(), function ($builder) {
        $builder->append("WARNING: Operation performed by an anonymous user.\n");
    })
    ->prepend("--- Daily Log for " . date('Y-m-d') . " ---\n")
    ->save();
```

**Что здесь происходит**:

1. Архивация: Если старый файл `debug.log` существует, мы используем `FSBuilder::from()` для его "захвата", 
а затем немедленно вызываем `->move()`, чтобы переместить его в архив с датой в названии.
2. Создание: `FSBuilder::create()` начинает создание нового лог-файла.
3. Наполнение: Методы `append()` и `prepend()` последовательно наполняют буфер содержимым.
4. Условная логика: `when(WP_DEBUG, ...)` добавляет подробную отладочную информацию только в том случае, если в
`wp-config.php` определена константа `WP_DEBUG` и она равна `true`. `unless(is_user_logged_in(), ...)` добавляет 
предупреждение, если действие выполнено неавторизованным пользователем.
5. Сохранение: `->save()` атомарно записывает все собранное содержимое в новый `debug.log`.

_Пример 2: Безопасное обновление конфигурационного файла_

Этот пример показывает, как безопасно изменить значение константы в файле `wp-config.php`, предварительно создав 
резервную копию и сохранив исходные права доступа.

```php
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$configFile = WPFS::getInstallationPath() . 'wp-config.php';

// Проверяем, существует ли файл и доступен ли он для записи
if (!WPFS::isWritable($configFile)) {
    // В реальном приложении здесь можно было бы выбросить исключение или записать ошибку
    return;
}

// Получаем текущие права доступа, чтобы сохранить их
$originalPermissions = (int) octdec(WPFS::getPermissions($configFile));

FSBuilder::from($configFile, $wp_filesystem)
    ->backup('.bak-' . time()) // Создаем уникальную резервную копию с таймстемпом
    ->replaceRegex(
        "/define\(\s*'WP_DEBUG',\s*false\s*\);/i", // Шаблон для поиска define('WP_DEBUG', false);
        "define('WP_DEBUG', true);"                // На что заменяем
    )
    ->withPermissions($originalPermissions) // Устанавливаем исходные права доступа
    ->save();
```

**Что здесь происходит**:

1. Безопасность: Сначала мы проверяем, доступен ли файл `wp-config.php` для записи, и получаем его текущие права доступа,
чтобы не изменить их случайно.
2. Чтение и бэкап: `FSBuilder::from()` читает содержимое файла. `->backup('.bak-' . time())` немедленно создает его 
резервную копию с уникальным именем, содержащим текущее время.
3. Надежная замена: `->replaceRegex()` используется для точечной замены. В отличие от простого `replace()`, 
регулярное выражение позволяет нам найти определение константы `WP_DEBUG` вне зависимости от пробелов и регистра символов
и заменить `false` на `true`, не затрагивая другие части файла.
4. Сохранение атрибутов: `->withPermissions($originalPermissions)` гарантирует, что после сохранения файла его права
доступа останутся такими же, какими были до редактирования. Это критически важно для таких файлов, как `wp-config.php`.
5. Сохранение: `->save()` применяет все изменения.
