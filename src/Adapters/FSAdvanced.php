<?php

namespace Metapraxis\WPFileSystem\Adapters;

use Closure;
use Metapraxis\WPFileSystem\Contracts\FSAdvanced as Advanced;
use DOMDocument;
use SimpleXMLElement;

/**
 * @see tests/Unit/FSAdvancedTest.php
 */
final class FSAdvanced extends FileSystem implements Advanced
{
    public function atomicWrite(string $path, string $content, int $mode = null): bool
    {
        $tempPath = dirname($path) . '/' . uniqid(basename($path));

        if (!$this->wp_filesystem->put_contents($tempPath, $content)) {
            return false;
        }

        if ($mode !== null) {
            $this->wp_filesystem->chmod($tempPath, $mode);
        }

        if (!$this->wp_filesystem->move($tempPath, $path, true)) {
            $this->wp_filesystem->delete($tempPath);
            return false;
        }

        return true;
    }

    public function append(string $path, string $content): bool
    {
        $oldContent = $this->wp_filesystem->get_contents($path);

        return $oldContent !== false && $this->wp_filesystem->put_contents($path, $oldContent . $content);
    }

    public function prepend(string $path, string $content): bool
    {
        $oldContent = $this->wp_filesystem->get_contents($path);

        return $oldContent !== false && $this->wp_filesystem->put_contents($path, $content . $oldContent);
    }

    public function replace(string $path, $search, $replace): bool
    {
        $oldContent = $this->wp_filesystem->get_contents($path);

        return $oldContent !== false &&
            $this->wp_filesystem->put_contents($path, str_replace($search, $replace, $oldContent));
    }

    public function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function filename(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public function dirname(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    public function cleanDirectory(string $directory): bool
    {
        $items = $this->wp_filesystem->dirlist($directory);

        if (empty($items)) {
            return true;
        }

        foreach (array_keys($items) as $item) {
            if (!$this->wp_filesystem->delete($directory . "/" . $item, true)) {
                return false;
            }
        }

        return true;
    }

    public function isDirectoryEmpty(string $directory): bool
    {
        if (!$this->wp_filesystem->is_dir($directory)) {
            return false;
        }

        $list = $this->wp_filesystem->dirlist($directory);

        return empty($list);
    }

    public function getMimeType(string $path)
    {
        if (!$this->wp_filesystem->exists($path)) {
            return false;
        }

        return wp_check_filetype($path)["type"];
    }

    public function hash(string $path, string $algorithm = 'md5')
    {
        $content = $this->wp_filesystem->get_contents($path);

        return ($content !== false) ? hash($algorithm, $content) : false;
    }

    public function filesEqual(string $path1, string $path2): bool
    {
        if (!$this->wp_filesystem->exists($path1) || !$this->wp_filesystem->exists($path2)) {
            return false;
        }

        return $this->hash($path1) === $this->hash($path2);
    }

    public function readJson(string $path, bool $assoc = true)
    {
        $content = $this->wp_filesystem->get_contents($path);

        if ($content === false) {
            return false;
        }

        $data = json_decode($content, $assoc);

        return json_last_error() === JSON_ERROR_NONE ? $data : false;
    }

    public function writeJson(string $path, $data, int $options = JSON_PRETTY_PRINT): bool
    {
        $content = json_encode($data, $options);

        if ($content === false) {
            return false;
        }

        return $this->wp_filesystem->put_contents($path, $content);
    }

    public function readXml(string $path)
    {
        $content = $this->wp_filesystem->get_contents($path);
        if ($content === false) {
            return false;
        }

        return $this->withInternalXmlErrors(static function () use ($content) {
            return simplexml_load_string(
                $content,
                SimpleXMLElement::class,
                LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING
            );
        });
    }

    public function writeXml(string $path, $xml): bool
    {
        $content = $this->convertXmlToString($xml);

        if ($content === false) {
            return false;
        }

        return $this->wp_filesystem->put_contents($path, $content);
    }

    public function readDom(string $path)
    {
        $content = $this->wp_filesystem->get_contents($path);

        if ($content === false) {
            return false;
        }

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = false;

        $loaded = $this->withInternalXmlErrors(static function () use ($dom, $content) {
            return $dom->loadXML($content, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        });

        return $loaded ? $dom : false;
    }

    public function writeDom(string $path, $dom): bool
    {
        return $this->writeXml($path, $dom);
    }

    private function convertXmlToString($data)
    {
        if ($data instanceof SimpleXMLElement) {
            return $data->asXML();
        }

        if ($data instanceof DOMDocument) {
            return $data->saveXML();
        }

        if (is_string($data)) {
            $doc = new DOMDocument();
            $loaded = $this->withInternalXmlErrors(static function () use ($doc, $data) {
                return $doc->loadXML($data, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
            });

            return $loaded ? $doc->saveXML() : false;
        }

        return false;
    }

    private function withInternalXmlErrors(Closure $callback)
    {
        $previous = libxml_use_internal_errors(true);
        $result = $callback();
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $result;
    }
}
