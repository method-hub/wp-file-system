# WP File System

`WP File System` — это плагин-библиотека, созданный для всего сообщества разработчиков WordPress. Он будет незаменим не
только для авторов плагинов и тем, но и для технических специалистов, которые занимаются развертыванием, поддержкой и
кастомизации сайтов, а также для всех, кто активно работает с файловой системой WordPress в режиме разработки.

Его философия заключается в решении одной из самых частых и сложных проблем в экосистеме WordPress: **надежной и
безопасной работы с файлами.** Эта проблема имеет два аспекта:

1.  **Прямое использование PHP-функций:** Такие функции, как `file_put_contents`, `mkdir` или `unlink`, являются
    источником нестабильности. Код, написанный с их помощью, становится хрупким и зависимым от окружения, часто приводя к
    фатальным ошибкам из-за неверных прав доступа или специфичных настроек безопасности хостинга.

2.  **Использование сторонних библиотек:** Многие разработчики пытаются решить эту проблему, интегрируя популярные пакеты,
    такие как `symfony/filesystem` или компоненты Laravel. Несмотря на их высокое качество, в контексте WordPress они становятся
    "чужеродным элементом". Эти библиотеки **не осведомлены о существовании WordPress Filesystem API**. Они работают в обход
    встроенных механизмов WordPress, игнорируя необходимый метод доступа к файлам (`direct`, `ftp`, `ssh2`), который WordPress
    выбирает для обеспечения безопасности и совместимости с сервером. Это приводит к тем же проблемам с правами доступа и делает
    невозможным безопасное взаимодействие с ядром WordPress, например, при установке или обновлении расширений.

`WP File System` решает обе эти проблемы. Он предоставляет простой, унифицированный и объектно-ориентированный интерфейс,
который служит удобной оболочкой для нативного `WP_Filesystem` API. Библиотека берет на себя всю сложность: автоматически
инициализирует файловую систему, определяет корректный метод доступа, обрабатывает учетные данные и следит за правильной
установкой прав, освобождая разработчика от этой головной боли.

Использование `WP File System` гарантирует, что ваш код будет надежным, безопасным и по-настоящему портируемым между
различными хостинг-окружениями, поскольку он работает **в полном соответствии с принципами и механизмами ядра WordPress.**

## Getting started

Установите проект из репозитория плагинов WordPress или используйте следующую команду для установки через composer:

```shell
  composer require metapraxis/wp-file-system
```

### Примеры использования

Для работы с файловой системой WordPress вам будет доступен фасад `WPFS`. Это предпочтительный метод использования
библиотеки, но не единственный.

```php
require_once '../wp-content/plugins/metapraxis-wp-file-system/src/Facade/WPFS.php';

use Metapraxis\WPFileSystem\Facade\WPFS;

$reportsDir = WP_CONTENT_DIR . '/reports';
$reportFile = $reportsDir . '/daily_report.txt';
$archiveFile = $reportsDir . '/archive/report_' . date('Y-m-d') . '.txt';

// 1. Атомарно создаем или перезаписываем отчет с JSON-данными с последующим наполнением.
WPFS::writeJson($reportFile, ['status' => 'pending', 'entries' => 100]);
WPFS::prepend($reportFile, "# Ежедневный отчет " . date('Y-m-d') . "\n");
WPFS::append($reportFile, "\n-- Проверка завершена --");
WPFS::replace($reportFile, '"status":"pending"', '"status":"complete"');

// 2. Создаем резервную копию, директорию для архива и перемещаем наш файл.
WPFS::copyFile($reportFile, $reportFile . '.bak');
WPFS::createDirectory(dirname($archiveFile));
WPFS::moveFile($reportFile, $archiveFile, true);

echo "Хэш архива: " . WPFS::hash($archiveFile);
```

Используйте построитель запросов к файловой системе `FSBuilder`, если вы работаете в рамках одного файла.

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Builder\FSBuilder;

global $wp_filesystem;

$reportPath = WP_CONTENT_DIR . '/uploads/daily_report.txt';
$archivePath = WP_CONTENT_DIR . '/archives/report_' . date('Y-m-d') . '.txt';

// 1. Создаем директорию для архивов, если ее нет
WPFS::createDirectory(dirname($archivePath));

// 2. Используем элегантный "Строитель" для создания и модификации отчета
FSBuilder::create($reportPath, $wp_filesystem)
    ->setContent("Строка 2: Основные данные.\n")
    ->prepend("Строка 1: Заголовок отчета\n")
    ->append("Строка 3: Итоги.")
    ->replace('Строка', 'Отчет - Строка')
    ->when(WP_DEBUG, fn($builder) => $builder->append("\n\n-- РЕЖИМ ОТЛАДКИ АКТИВЕН --"))
    ->transform('mb_strtoupper')
    ->backup('.bak')
    ->save();

// 3. Перемещаем финальный отчет в архив с новой датой
WPFS::moveFile($reportPath, $archivePath, true);
```

Используйте фабрику для создания нужного для работы экземпляра.

```php
require_once __DIR__ . '/vendor/autoload.php';

use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;

$reader = FSFactory::create(FSBaseReader::class);

$log = $reader->getContents(__DIR__ . '/log.txt');
```

Используйте провайдеры данных для изменения поведения при работе с файловой системой.

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

// или установить выброс исключений

FSGuardiansProvider::setStatus(true);

try {
    WPFS::exists(__DIR__ . '/file.txt.tmp');
    WPFS::append(__DIR__ . '/file.txt.tmp', 'Text content');
    // ...
} catch (FSPathNotFoundException $exception) {
    echo $exception->getMessage();
}
```

_Во всех прочих примерах в рамках документации больше не будет использован механизм загрузки классов, 
а все классы будут вызываться со своим пространством имён_.

*   [**Документация по фасаду WPFS**](./WPFS.md)
*   [**Документация по FSBuilder**](./BUILDER.md)
*   [**Документация по FSFactory**](./FACTORY.md)
*   [**Документация по продвинутому использованию**](./ADVANCED.md)
