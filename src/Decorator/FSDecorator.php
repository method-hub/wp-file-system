<?php

namespace Metapraxis\WPFileSystem\Decorator;

use Metapraxis\WPFileSystem\Contracts\FileSystem;

class FSDecorator
{
    /**
     * @template T of FileSystem
     * @param class-string<T> $instance class of base service (for example, FSBaseReader::class).
     * @return T
     */
    public static function create(string $instance, bool $guardian, bool $hookable): FileSystem
    {
        global $wp_filesystem;

        $service = new $instance($wp_filesystem);
        $short = self::classShortName($instance);

        if ($hookable) {
            $hookableShort = 'Hookable' . str_replace('FSBase', 'FS', $short);
            $hookableInstance  = 'Metapraxis\\WPFileSystem\\Hooks\\' . $hookableShort;

            if (class_exists($hookableInstance)) {
                $service = new $hookableInstance($service);
            }
        }

        if ($guardian) {
            $guardedShort = 'Guarded' . $short;
            $guardedInstance  = 'Metapraxis\\WPFileSystem\\Guarded\\' . $guardedShort;

            if (class_exists($guardedInstance)) {
                $service = new $guardedInstance($service);
            }
        }

        return $service;
    }

    private static function classShortName(string $instance): string
    {
        $pos = strrpos($instance, '\\');

        return $pos === false ? $instance : substr($instance, $pos + 1);
    }
}
