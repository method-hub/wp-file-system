<?php

namespace Metapraxis\WPFileSystem\Tests\Integration;

use Metapraxis\WPFileSystem\Builder\FSBuilder;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use Metapraxis\WPFileSystem\Facade\WPFS;

/**
 * @covers \Metapraxis\WPFileSystem\Builder\FSBuilder
 */
class FSBuilderTest extends TestCase
{
    /**
     * A comprehensive test that checks the entire chain of FSBuilder methods.
     * @throws FSPathNotFoundException
     */
    public function testBuilderChainModifiesAndSavesFile(): void
    {
        global $wp_filesystem;

        $path = __DIR__ . '/output/builder-test.txt';
        $backupPath = $path . '.bak';
        $movedPath = __DIR__ . '/output/builder-moved.txt';

        // 1. Create a builder and build the chain
        $builder = FSBuilder::create($path, $wp_filesystem)
            ->setContent('Line 2')
            ->prepend("Line 1\n")
            ->append("\nLine 3")
            ->replace('Line', 'L')
            ->replaceRegex('/L (\d)/', 'Line $1')
            ->transform('strtoupper')
            ->when(true, function (FSBuilder $b) {
                $b->append("\n---\nWHEN_TRUE");
            })
            ->unless(false, function (FSBuilder $b) {
                $b->append("\nUNLESS_FALSE");
            })
            ->withPermissions(0777);

        // 2. Save the file
        $saveResult = $builder->save();
        $this->assertTrue($saveResult, 'Builder should save the file successfully.');
        $this->assertFileExists($path);

        // 3. Make a backup
        $builder->backup();
        $this->assertFileExists($backupPath, 'Backup file should be created.');

        // 4. Check the content
        $expectedContent = "LINE 1\nLINE 2\nLINE 3\n---\nWHEN_TRUE\nUNLESS_FALSE";
        $this->assertEquals($expectedContent, WPFS::getContents($path));

        // 5. Check the permissions
        $this->assertEquals('777', substr(sprintf('%o', fileperms($path)), -3));

        // 6. Check the move
        $this->assertTrue($builder->move($movedPath));
        $this->assertFileDoesNotExist($path);
        $this->assertFileExists($movedPath);

        // 7. Check the deletion
        $finalBuilder = FSBuilder::from($movedPath, $wp_filesystem);
        $this->assertTrue($finalBuilder->delete());
        $this->assertFileDoesNotExist($movedPath);

        // Clean up the backup
        WPFS::delete($backupPath);
    }

    /**
     * TestCase that the `from` method correctly loads content and throws an exception.
     * @throws FSPathNotFoundException
     */
    public function testFromMethod(): void
    {
        global $wp_filesystem;
        $path = __DIR__ . '/input/mock-data.txt';

        // Successful read
        $builder = FSBuilder::from($path, $wp_filesystem);
        $this->assertEquals($this->textDocument, $builder->get());

        // Failure when reading a non-existent file
        $this->expectException(FSPathNotFoundException::class);
        FSBuilder::from(__DIR__ . '/non-existent-file.txt', $wp_filesystem);
    }
}
