<?php

namespace Metapraxis\WPFileSystem\Tests\Integration;

use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Facade\WPFS;
use Metapraxis\WPFileSystem\Hooks\Collection\HookCollection;
use Metapraxis\WPFileSystem\Provider\FSGuardiansProvider;
use Metapraxis\WPFileSystem\Provider\FSHooksProvider;

/**
 * @covers \Metapraxis\WPFileSystem\Provider\FSGuardiansProvider
 * @covers \Metapraxis\WPFileSystem\Provider\FSHooksProvider
 */
class ProvidersTest extends TestCase
{
    private array $hookFlags = [];

    public function recordHookExecution(string $hookName): void
    {
        $this->hookFlags[$hookName] = true;
    }

    public function testGuardedProviderThrowsExceptionOnError(): void
    {
        $nonExistentPath = __DIR__ . '/output/non-existent-file.txt';

        // 1. Check in the disabled state
        FSGuardiansProvider::setStatus(false);
        $this->assertFalse(WPFS::getContents($nonExistentPath));

        // 2. Check in the enabled state
        FSGuardiansProvider::setStatus(true);
        $this->expectException(FSPathNotFoundException::class);
        WPFS::getContents($nonExistentPath);
    }

    public function testHookableProviderExecutesHooks(): void
    {
        $testFile = __DIR__ . '/output/hooks-test.txt';
        $this->hookFlags = []; // Reset flags before the test

        // Add hooks that will set flags when called
        add_action(HookCollection::$BEFORE_PUT_CONTENTS_ACTION, function () {
            $this->recordHookExecution('before_put_contents');
        });
        add_action(HookCollection::$AFTER_PUT_CONTENTS_ACTION, function () {
            $this->recordHookExecution('after_put_contents');
        });

        // 1. Call the method with hooks disabled
        FSHooksProvider::setStatus(false);
        WPFS::putContents($testFile, 'initial');
        $this->assertFileExists($testFile);
        $this->assertArrayNotHasKey('before_put_contents', $this->hookFlags);
        $this->assertArrayNotHasKey('after_put_contents', $this->hookFlags);

        // 2. Enable hooks and call the method again
        FSHooksProvider::setStatus(true);
        WPFS::putContents($testFile, 'with hooks');

        // 3. Check that the flags were set
        $this->assertTrue($this->hookFlags['before_put_contents']);
        $this->assertTrue($this->hookFlags['after_put_contents']);

        // 4. Clean up and reset state
        WPFS::delete($testFile);
        remove_all_actions(HookCollection::$BEFORE_PUT_CONTENTS_ACTION);
        remove_all_actions(HookCollection::$AFTER_PUT_CONTENTS_ACTION);
    }

    public function testAllProvidersEnabled(): void
    {
        $testFile = __DIR__ . '/output/all-providers-test.txt';
        $nonExistentPath = __DIR__ . '/output/non-existent-file.txt';
        $this->hookFlags = []; // Reset flags

        // Enable both providers
        FSHooksProvider::setStatus(true);
        FSGuardiansProvider::setStatus(true);

        // Add a hook for verification
        add_action(HookCollection::$AFTER_PUT_CONTENTS_ACTION, function () {
            $this->recordHookExecution('after_put_contents');
        });

        // --- Test successful execution ---
        WPFS::putContents($testFile, 'test content');
        $this->assertFileExists($testFile);
        $this->assertTrue($this->hookFlags['after_put_contents']);
        WPFS::delete($testFile);

        // --- Test exception throwing ---
        $this->expectException(FSPathNotFoundException::class);
        WPFS::getContents($nonExistentPath);

        // --- Reset to the initial state ---
        remove_all_actions(HookCollection::$AFTER_PUT_CONTENTS_ACTION);
    }
}
