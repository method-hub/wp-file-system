# Документация по API FSFactory

FSFactory — это фабрика для создания и управления экземплярами файловых системных сервисов. Этот класс реализует 
паттерн "Одиночка" (Singleton), чтобы гарантировать, что для каждого типа сервиса (Reader, Action и т.д.) 
и его конфигурации (с хуками или без) существует только один экземпляр за один запрос. Это предотвращает дублирование
объектов и обеспечивает предсказуемое состояние.

В большинстве случаев вам не нужно будет напрямую взаимодействовать с фабрикой, так как фасад WPFS делает это за вас.
Однако FSFactory полезна для внедрения зависимостей или когда вам нужен конкретный экземпляр сервиса с определенной 
конфигурацией декораторов.

## Содержание API


| Метод                                      | Краткое описание                                                     | Группа       |
|:-------------------------------------------|:---------------------------------------------------------------------|:-------------|
| [`FSFactory::create()`](#create)           | Универсальный метод для создания (или получения) экземпляра сервиса. | `FSReader`   |
| [`FSFactory::getReader()`](#getreader)     | Получает сервис для чтения данных из файловой системы.               | `FSReader`   |
| [`FSFactory::getAction()`](#getaction)     | Получает сервис для выполнения активных файловых операций.           | `FSReader`   |
| [`FSFactory::getManager()`](#getmanager)   | Получает сервис для управления файлами и директориями.               | `FSReader`   |
| [`FSFactory::getAuditor()`](#getauditor)   | Получает сервис для проверки и валидации состояния файловой системы. | `FSReader`   |
| [`FSFactory::getAdvanced()`](#getadvanced) | Получает сервис для продвинутых, высокоуровневых операций.           | `FSReader`   |

---

#### `create()`

Это основной фабричный метод, который содержит всю логику создания экземпляров. Он "ленивый", то есть создает объект
только при первом запросе, а при последующих запросах с той же конфигурацией возвращает уже созданный экземпляр.

**Сигнатура**: `FSFactory::create(string $className): FileSystem`

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

Удобный метод-ярлык для `create(FSBaseReader::class)`.

**Сигнатура**: `FSFactory::getReader(): \Metapraxis\WPFileSystem\Contracts\FSBaseReader`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$reader = FSFactory::getReader();
$contentPath = $reader->getContentPath();
```

---

#### `getAction()`

Удобный метод-ярлык для `create(FSBaseAction::class)`.

**Сигнатура**: `FSFactory::getAction(): \Metapraxis\WPFileSystem\Contracts\FSBaseAction`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$action = FSFactory::getAction();
$action->putContents(WP_CONTENT_DIR . '/uploads/new-file.txt', 'Hello!');
```

---

#### `getManager()`

Удобный метод-ярлык для create(FSBaseManager::class).

**Сигнатура**: `FSFactory::getManager(): \Metapraxis\WPFileSystem\Contracts\FSBaseManager`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$manager = FSFactory::getManager();
$safeFilename = $manager->ensureUniqueFilename(WP_CONTENT_DIR . '/uploads', 'image.jpg');
```

---

#### `getAuditor()`

Удобный метод-ярлык для create(FSBaseAuditor::class).

**Сигнатура**: `FSFactory::getAuditor(): \Metapraxis\WPFileSystem\Contracts\FSBaseAuditor`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$auditor = FSFactory::getAuditor();
if ($auditor->isWritable(WP_CONTENT_DIR . '/uploads')) {
    // Директория доступна для записи
}
```

---

#### `getAdvanced()`

Удобный метод-ярлык для `create(FSAdvanced::class)`.

**Сигнатура**: `FSFactory::getAdvanced(): \Metapraxis\WPFileSystem\Contracts\FSAdvanced`

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;

$advanced = FSFactory::getAdvanced();
$data = ['status' => 'ok'];
$advanced->writeJson(WP_CONTENT_DIR . '/data.json', $data);
```

### Взаимодействие с декораторами (Hooks и Guardians)

Фабрика автоматически учитывает состояние FSHooksProvider и FSGuardiansProvider. Когда эти провайдеры включены,
FSFactory будет возвращать "обернутые" (декорированные) экземпляры сервисов. Фабрика кеширует экземпляры для каждой 
уникальной комбинации состояний провайдеров.

```php
use Metapraxis\WPFileSystem\Factory\FSFactory;
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;

// 1. Провайдеры отключены (по умолчанию)
$reader = FSFactory::getReader();
// $reader - это экземпляр Metapraxis\WPFileSystem\Adapters\FSBaseReader

// 2. Включаем хуки
FSHooksProvider::setStatus(true);
$hookableReader = FSFactory::getReader();
// $hookableReader - это экземпляр Metapraxis\WPFileSystem\Hooks\HookableFSReader

// 3. Включаем "защиту" (исключения при ошибках)
FSGuardiansProvider::setStatus(true);
$guardedHookableReader = FSFactory::getReader();
// $guardedHookableReader - это экземпляр Metapraxis\WPFileSystem\Guarded\GuardedFSReader,
// который оборачивает HookableFSReader, который, в свою очередь, оборачивает FSBaseReader.

// 4. Отключаем хуки, но оставляем защиту
FSHooksProvider::setStatus(false);
$guardedReader = FSFactory::getReader();
// $guardedReader - это новый экземпляр Metapraxis\WPFileSystem\Guarded\GuardedFSReader,
// который оборачивает базовый FSBaseReader.
```
