<?php

namespace Metapraxis\WPFileSystem\Hooks;

use Metapraxis\WPFileSystem\Contracts\FSAdvanced;
use Metapraxis\WPFileSystem\Hooks\Collection\AdvancedHooks;

/**
 * @extends HookableFileSystem<FSAdvanced>
 */
class HookableFSAdvanced extends HookableFileSystem implements FSAdvanced
{
    use AdvancedHooks;

    public function atomicWrite(string $path, string $content, int $mode = null): bool
    {
        do_action(self::$BEFORE_ATOMIC_WRITE_ACTION, $path, $content, $mode);
        $result = $this->fs->atomicWrite($path, $content, $mode);
        do_action(self::$AFTER_ATOMIC_WRITE_ACTION, $result, $path, $content, $mode);

        return $result;
    }

    public function append(string $path, string $content): bool
    {
        do_action(self::$BEFORE_APPEND_ACTION, $path, $content);
        $result = $this->fs->append($path, $content);
        do_action(self::$AFTER_APPEND_ACTION, $result, $path, $content);

        return $result;
    }

    public function prepend(string $path, string $content): bool
    {
        do_action(self::$BEFORE_PREPEND_ACTION, $path, $content);
        $result = $this->fs->prepend($path, $content);
        do_action(self::$AFTER_PREPEND_ACTION, $result, $path, $content);

        return $result;
    }

    public function replace(string $path, $search, $replace): bool
    {
        do_action(self::$BEFORE_REPLACE_ACTION, $path, $search, $replace);
        $result = $this->fs->replace($path, $search, $replace);
        do_action(self::$AFTER_REPLACE_ACTION, $result, $path, $search, $replace);

        return $result;
    }

    public function extension(string $path): string
    {
        do_action(self::$BEFORE_EXTENSION_ACTION, $path);
        $result = $this->fs->extension($path);
        $result = apply_filters(self::$EXTENSION_FILTER, $result, $path);
        do_action(self::$AFTER_EXTENSION_ACTION, $result, $path);

        return $result;
    }

    public function filename(string $path): string
    {
        do_action(self::$BEFORE_FILENAME_ACTION, $path);
        $result = $this->fs->filename($path);
        $result = apply_filters(self::$FILENAME_FILTER, $result, $path);
        do_action(self::$AFTER_FILENAME_ACTION, $result, $path);

        return $result;
    }

    public function dirname(string $path): string
    {
        do_action(self::$BEFORE_DIRNAME_ACTION, $path);
        $result = $this->fs->dirname($path);
        $result = apply_filters(self::$DIRNAME_FILTER, $result, $path);
        do_action(self::$AFTER_DIRNAME_ACTION, $result, $path);

        return $result;
    }

    public function cleanDirectory(string $directory): bool
    {
        do_action(self::$BEFORE_CLEAN_DIRECTORY_ACTION, $directory);
        $result = $this->fs->cleanDirectory($directory);
        do_action(self::$AFTER_CLEAN_DIRECTORY_ACTION, $result, $directory);

        return $result;
    }

    public function isDirectoryEmpty(string $directory): bool
    {
        do_action(self::$BEFORE_IS_DIRECTORY_EMPTY_ACTION, $directory);
        $result = $this->fs->isDirectoryEmpty($directory);
        do_action(self::$AFTER_IS_DIRECTORY_EMPTY_ACTION, $result, $directory);

        return $result;
    }

    public function getMimeType(string $path)
    {
        do_action(self::$BEFORE_GET_MIME_TYPE_ACTION, $path);
        $result = $this->fs->getMimeType($path);
        $result = apply_filters(self::$GET_MIME_TYPE_FILTER, $result, $path);
        do_action(self::$AFTER_GET_MIME_TYPE_ACTION, $result, $path);

        return $result;
    }

    public function hash(string $path, string $algorithm = 'md5')
    {
        do_action(self::$BEFORE_HASH_ACTION, $path, $algorithm);
        $result = $this->fs->hash($path, $algorithm);
        $result = apply_filters(self::$HASH_FILTER, $result, $path, $algorithm);
        do_action(self::$AFTER_HASH_ACTION, $result, $path, $algorithm);

        return $result;
    }

    public function filesEqual(string $path1, string $path2): bool
    {
        do_action(self::$BEFORE_FILES_EQUAL_ACTION, $path1, $path2);
        $result = $this->fs->filesEqual($path1, $path2);
        do_action(self::$AFTER_FILES_EQUAL_ACTION, $result, $path1, $path2);

        return $result;
    }

    public function readJson(string $path, bool $assoc = true)
    {
        do_action(self::$BEFORE_READ_JSON_ACTION, $path, $assoc);
        $result = $this->fs->readJson($path, $assoc);
        $result = apply_filters(self::$READ_JSON_FILTER, $result, $path, $assoc);
        do_action(self::$AFTER_READ_JSON_ACTION, $result, $path, $assoc);

        return $result;
    }

    public function writeJson(string $path, $data, int $options = JSON_PRETTY_PRINT): bool
    {
        do_action(self::$BEFORE_WRITE_JSON_ACTION, $path, $data, $options);
        $result = $this->fs->writeJson($path, $data, $options);
        do_action(self::$AFTER_WRITE_JSON_ACTION, $result, $path, $data, $options);

        return $result;
    }

    public function readXml(string $path)
    {
        do_action(self::$BEFORE_READ_XML_ACTION, $path);
        $result = $this->fs->readXml($path);
        $result = apply_filters(self::$READ_XML_FILTER, $result, $path);
        do_action(self::$AFTER_READ_XML_ACTION, $result, $path);

        return $result;
    }

    public function writeXml(string $path, $xml): bool
    {
        do_action(self::$BEFORE_WRITE_XML_ACTION, $path, $xml);
        $result = $this->fs->writeXml($path, $xml);
        do_action(self::$AFTER_WRITE_XML_ACTION, $result, $path, $xml);

        return $result;
    }

    public function readDom(string $path)
    {
        do_action(self::$BEFORE_READ_DOM_ACTION, $path);
        $result = $this->fs->readDom($path);
        $result = apply_filters(self::$READ_DOM_FILTER, $result, $path);
        do_action(self::$AFTER_READ_DOM_ACTION, $result, $path);

        return $result;
    }

    public function writeDom(string $path, $dom): bool
    {
        do_action(self::$BEFORE_WRITE_DOM_ACTION, $path, $dom);
        $result = $this->fs->writeDom($path, $dom);
        do_action(self::$AFTER_WRITE_DOM_ACTION, $result, $path, $dom);

        return $result;
    }
}
