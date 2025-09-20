<?php

namespace Metapraxis\WPFileSystem\Builder;

use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use WP_Filesystem_Base;

/**
 * Provides a fluent interface for creating and manipulating files.
 *
 * FSBuilder allows for complex file operations to be chained together
 * in a sequence of method calls, making the code more readable and expressive.
 * Work begins with the static methods `from()` (for existing files)
 * or `create()` (for new ones), after which modifier methods are applied,
 * and finally, a terminal method (like `save()`) is called.
 *
 * @package Metapraxis\WPFileSystem\Builder
 */
final class FSBuilder
{
    /**
     * @var WP_Filesystem_Base The WordPress filesystem instance.
     */
    private WP_Filesystem_Base $fs;

    /**
     * @var string The target file path.
     */
    private string $path;

    /**
     * @var string|null The current content of the file in the buffer.
     */
    private ?string $content = null;

    /**
     * @var int|null The permissions to be set upon saving.
     */
    private ?int $permissions = null;

    /**
     * @var string|int|null The owner to be set upon saving.
     */
    private $owner = null;

    /**
     * @var string|int|null The group to be set upon saving.
     */
    private $group = null;


    /**
     * The constructor is private to force instantiation via static methods.
     *
     * @param WP_Filesystem_Base $fs The filesystem instance.
     * @param string $path The file path.
     */
    private function __construct(WP_Filesystem_Base $fs, string $path)
    {
        $this->fs = $fs;
        $this->path = $path;
    }

    /**
     * Starts a chain from an existing file, loading its content into the buffer.
     *
     * @param string $path The path to the file.
     * @param WP_Filesystem_Base $fs The filesystem instance.
     *
     * @return self A new builder instance.
     * @throws FSPathNotFoundException If the file at the specified path is not found.
     * @example $builder = FSBuilder::from("/var/www/config.txt", $fs);
     */
    public static function from(string $path, WP_Filesystem_Base $fs): self
    {
        if (!$fs->exists($path)) {
            throw new FSPathNotFoundException("File not found: {$path}");
        }

        $builder = new self($fs, $path);
        $builder->content = $fs->get_contents($path);

        return $builder;
    }

    /**
     * Starts a chain for a new file (or to completely overwrite an existing one).
     *
     * @param string $path The path to the file.
     * @param WP_Filesystem_Base $fs The filesystem instance.
     *
     * @return self A new builder instance with an empty buffer.
     * @example $builder = FSBuilder::create("/var/www/new-file.log", $fs);
     */
    public static function create(string $path, WP_Filesystem_Base $fs): self
    {
        return new self($fs, $path);
    }

    /**
     * Completely replaces the content in the buffer.
     *
     * @param string $content The new content.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->setContent("New line of text.");
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Appends a string to the end of the buffer"s content.
     *
     * @param string $data The string to append.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->append("...and another line.");
     */
    public function append(string $data): self
    {
        $this->content .= $data;

        return $this;
    }

    /**
     * Prepends a string to the beginning of the buffer"s content.
     *
     * @param string $data The string to prepend.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->prepend("Start of the file: ");
     */
    public function prepend(string $data): self
    {
        $this->content = $data . $this->content;

        return $this;
    }

    /**
     * Performs a search and replace on the buffer"s content.
     *
     * @param string|string[] $search The string or array of strings to search for.
     * @param string|string[] $replace The string or array of strings to replace with.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->replace("wordpress", "WordPress");
     */
    public function replace($search, $replace): self
    {
        $this->content = str_replace($search, $replace, $this->content);

        return $this;
    }

    /**
     * Performs a regular expression search and replace on the buffer"s content.
     *
     * @param string $pattern The regular expression pattern.
     * @param string|callable $replacement The replacement string or a callback.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->replaceRegex("/user-\d+/", "user-id");
     */
    public function replaceRegex(string $pattern, $replacement): self
    {
        $this->content = preg_replace($pattern, $replacement, $this->content);

        return $this;
    }

    /**
     * Applies a custom callback function to the buffer"s content.
     *
     * @param callable $callback The function that takes the current content and returns the modified content.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->transform("strtoupper");
     */
    public function transform(callable $callback): self
    {
        $this->content = $callback($this->content);

        return $this;
    }

    /**
     * Executes a callback if a given condition is true. Allows for conditional logic within a chain.
     *
     * @param bool|callable $condition The condition to check.
     * @param callable $callback The function to execute, which receives the current builder as an argument.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->when(is_production(), fn($b) => $b->append("PROD_ENV"));
     */
    public function when($condition, callable $callback): self
    {
        $conditionResult = is_callable($condition) ? $condition($this) : $condition;

        if ($conditionResult) {
            $callback($this);
        }

        return $this;
    }

    /**
     * Executes a callback if a given condition is false.
     *
     * @param bool|callable $condition The condition to check.
     * @param callable $callback The function to execute, which receives the current builder as an argument.
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->unless(is_debug_mode(), fn($b) => $b->setContent(''));
     */
    public function unless($condition, callable $callback): self
    {
        $conditionResult = is_callable($condition) ? $condition($this) : $condition;

        if (!$conditionResult) {
            $callback($this);
        }

        return $this;
    }

    /**
     * Sets the file permissions to be applied when saving the file.
     *
     * @param int $mode The permissions in octal format (e.g., 0644).
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->withPermissions(0600);
     */
    public function withPermissions(int $mode): self
    {
        $this->permissions = $mode;

        return $this;
    }

    /**
     * Sets the owner and/or group to be applied when saving the file.
     *
     * @param string|int|null $user The owner (name or ID).
     * @param string|int|null $group The group (name or ID).
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->withOwner("www-data", "www-data");
     */
    public function withOwner($user, $group = null): self
    {
        if ($user) {
            $this->owner = $user;
        }

        if ($group) {
            $this->group = $group;
        }

        return $this;
    }

    /**
     * Creates a backup of the original file before performing other operations.
     *
     * @param string $suffix The suffix for the backup file name (e.g., ".bak").
     *
     * @return $this Returns the current instance for method chaining.
     * @example $builder->backup(".bak");
     */
    public function backup(string $suffix = '.bak'): self
    {
        $backupPath = $this->path . $suffix;
        $this->fs->copy($this->path, $backupPath, true);

        return $this;
    }

    /**
     * Saves the buffer"s content to the target file.
     *
     * @return bool True on success, false on failure.
     * @example $success = $builder->save();
     */
    public function save(): bool
    {
        // Default to the permissions set via withPermissions(), or the WP standard.
        $mode = $this->permissions ?? FS_CHMOD_FILE;

        if (!$this->fs->put_contents($this->path, $this->content, $mode)) {
            return false;
        }

        if ($this->owner) {
            $this->fs->chown($this->path, $this->owner);
        }

        if ($this->group) {
            $this->fs->chgrp($this->path, $this->group);
        }

        return true;
    }

    /**
     * Returns the content from the buffer without saving it to disk.
     *
     * @return string|null The current buffer content.
     * @example $currentContent = $builder->get();
     */
    public function get(): ?string
    {
        return $this->content;
    }

    /**
     * Moves the original file to a new location.
     *
     * @param string $newPath The new file path.
     * @param bool $overwrite Whether to overwrite the destination file if it exists.
     *
     * @return bool True on success, false on failure.
     * @example $moved = $builder->move("/archive/old-file.txt");
     */
    public function move(string $newPath, bool $overwrite = false): bool
    {
        return $this->fs->move($this->path, $newPath, $overwrite);
    }

    /**
     * Deletes the original file.
     *
     * @return bool True on success, false on failure.
     * @example $deleted = $builder->delete();
     */
    public function delete(): bool
    {
        return $this->fs->delete($this->path);
    }
}
