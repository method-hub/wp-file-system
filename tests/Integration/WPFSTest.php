<?php

namespace Metapraxis\WPFileSystem\Tests\Integration;

use Metapraxis\WPFileSystem\Facade\WPFS;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseReader
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseAction
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseAuditor
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseManager
 * @covers \Metapraxis\WPFileSystem\Adapters\FSAdvanced
 */
class WPFSTest extends TestCase
{
    /**
     * @covers ::getHomePath
     * @covers ::getInstallationPath
     * @covers ::getContentPath
     * @covers ::getPluginsPath
     * @covers ::getThemesPath
     * @covers ::getLangPath
     * @covers ::getCurrentPath
     * @covers ::getTempDir
     */
    public function testPathGettersReturnValidPaths(): void
    {
        $this->assertIsString(WPFS::getHomePath());
        $this->assertNotEmpty(WPFS::getHomePath());

        $this->assertIsString(WPFS::getInstallationPath());
        $this->assertNotEmpty(WPFS::getInstallationPath());

        $this->assertIsString(WPFS::getContentPath());
        $this->assertStringContainsString('wp-content', WPFS::getContentPath());

        $this->assertIsString(WPFS::getPluginsPath());
        $this->assertStringContainsString('plugins', WPFS::getPluginsPath());

        $this->assertIsString(WPFS::getThemesPath());
        $this->assertStringContainsString('themes', WPFS::getThemesPath());

        $this->assertIsString(WPFS::getLangPath());

        if (defined(WP_LANG_DIR)) {
            $this->assertStringContainsString('languages', WPFS::getLangPath());
        }

        $this->assertIsString(WPFS::getCurrentPath());
        $this->assertNotEmpty(WPFS::getCurrentPath());

        $this->assertIsString(WPFS::getTempDir());
        $this->assertTrue(is_dir(WPFS::getTempDir()));
    }

    /**
     * @covers ::getContents
     * @covers ::getContentsAsArray
     */
    public function testGetContentsMethods(): void
    {
        $path = __DIR__ . '/input/mock-data.txt';

        $contentString = WPFS::getContents($path);

        $this->assertIsString($contentString);
        $this->assertEquals($this->textDocument, $contentString);
        $contentArray = WPFS::getContentsAsArray($path);
        $this->assertIsArray($contentArray);
        // We compare it with the result obtained by the standard PHP function
        $this->assertEquals(file($path), $contentArray);
    }

    /**
     * @covers ::getPermissions
     * @covers ::getHumanReadablePermissions
     * @covers ::getPermissionsAsOctal
     */
    public function testPermissionGetters(): void
    {
        $path = __DIR__ . '/input/mock-data.txt';
        $permissions = WPFS::getPermissions($path);

        $this->assertIsString($permissions);
        // We compare it with the result obtained by the standard PHP function
        $this->assertEquals(substr(sprintf('%o', fileperms($path)), -3), $permissions);
        $this->assertIsString(WPFS::getHumanReadablePermissions($path));
        $this->assertEquals('0755', WPFS::getPermissionsAsOctal('drwxr-xr-x'));
    }

    /**
     * @covers ::getOwner
     * @covers ::getGroup
     */
    public function testOwnerAndGroupGetters(): void
    {
        $path = __DIR__ . '/input/mock-data.txt';

        // They depend on the environment, we only check the type
        $this->assertTrue(is_string(WPFS::getOwner($path)) || is_int(WPFS::getOwner($path)));
        $this->assertTrue(is_string(WPFS::getGroup($path)) || is_int(WPFS::getGroup($path)));
    }

    /**
     * @covers ::getLastAccessedTime
     * @covers ::getLastModifiedTime
     * @covers ::getFileSize
     */
    public function testFileMetadataGetters(): void
    {
        $path = __DIR__ . '/input/mock-data.txt';

        $this->assertEquals(fileatime($path), WPFS::getLastAccessedTime($path));
        $this->assertEquals(filemtime($path), WPFS::getLastModifiedTime($path));
        $this->assertEquals(filesize($path), WPFS::getFileSize($path));
    }

    /**
     * @covers ::getDirectoryList
     * @covers ::getFiles
     */
    public function testDirectoryListingMethods(): void
    {
        $path = __DIR__ . '/input';

        // getDirectoryList
        $dirList = WPFS::getDirectoryList($path);
        $this->assertIsArray($dirList);
        $this->assertArrayHasKey('mock-data.txt', $dirList);
        $this->assertArrayHasKey('mock-data.xml', $dirList);

        // getFiles
        $files = WPFS::getFiles($path);
        $this->assertIsArray($files);
        $this->assertContains($path . '/mock-data.txt', $files);
        $this->assertContains($path . '/mock-data.json', $files);
    }

    /**
     * @covers ::getUploadsDirInfo
     */
    public function testGetUploadsDirInfo(): void
    {
        $uploads = WPFS::getUploadsDirInfo();
        $this->assertIsArray($uploads);
        $this->assertArrayHasKey('path', $uploads);
        $this->assertArrayHasKey('url', $uploads);
        $this->assertArrayHasKey('basedir', $uploads);
        $this->assertArrayHasKey('baseurl', $uploads);
        $this->assertFalse($uploads['error']);
    }

    /**
     * @covers ::getNormalizePath
     * @covers ::getSanitizeFilename
     */
    public function testPathAndFilenameUtils(): void
    {
        $this->assertEquals(
            '/my/test/path',
            WPFS::getNormalizePath('\\my\\test\\path')
        );
        $this->assertEquals(
            'a-dirty-filename.txt',
            WPFS::getSanitizeFilename('a dirty file?name!.txt')
        );
    }

    /**
     * @covers ::findFolder
     * @covers ::searchForFolder
     */
    public function testFolderFinders(): void
    {
        $this->assertEquals(__DIR__ . '/input/', WPFS::findFolder(__DIR__ . '/input/'));
        $this->assertEquals(__DIR__ . '/input/', WPFS::searchForFolder('input', __DIR__ . '/'));
    }

    /**
     * @covers ::putContents
     */
    public function testPutContents(): void
    {
        $path = __DIR__ . '/output/mock-data.txt';
        $content = 'This is a brand new content.';

        $result = WPFS::putContents($path, $content);
        $this->assertTrue($result);

        $this->assertStringEqualsFile($path, $content);
    }

    /**
     * @covers ::copyFile
     */
    public function testCopyFile(): void
    {
        $source = __DIR__ . '/input/mock-data.txt';
        $destination = __DIR__ . '/output/copied-data.txt';

        $result = WPFS::copyFile($source, $destination);
        $this->assertTrue($result);

        $this->assertFileExists($destination);
        $this->assertFileEquals($source, $destination);

        // Clean up the created file
        WPFS::delete($destination);
    }

    /**
     * @covers ::copyDirectory
     */
    public function testCopyDirectory(): void
    {
        $source = __DIR__ . '/input';
        $destination = __DIR__ . '/output/copied-dir';

        // Ensure the destination does not exist before copying
        if (WPFS::exists($destination)) {
            WPFS::delete($destination, true);
        }

        $result = WPFS::copyDirectory($source, $destination);
        $this->assertTrue($result);

        $this->assertDirectoryExists($destination);
        $this->assertFileExists($destination . '/mock-data.txt');
        $this->assertFileExists($destination . '/mock-data.json');

        // Clean up the created directory
        WPFS::delete($destination, true);
    }

    /**
     * @covers ::moveFile
     */
    public function testMoveFile(): void
    {
        $source = __DIR__ . '/output/mock-data.txt';
        $destination = __DIR__ . '/output/moved-data.txt';
        $originalContent = $this->textDocument;

        $result = WPFS::moveFile($source, $destination);
        $this->assertTrue($result);

        $this->assertFileDoesNotExist($source);
        $this->assertFileExists($destination);
        $this->assertStringEqualsFile($destination, $originalContent);

        // Clean up the moved file
        WPFS::delete($destination);
    }

    /**
     * @covers ::moveDirectory
     */
    public function testMoveDirectory(): void
    {
        $source = __DIR__ . '/output/input-to-move';
        $destination = __DIR__ . '/output/moved-dir';

        // Setup a directory to move
        WPFS::copyDirectory(__DIR__ . '/input', $source);
        $this->assertDirectoryExists($source);

        $result = WPFS::moveDirectory($source, $destination);
        $this->assertTrue($result);

        $this->assertDirectoryDoesNotExist($source);
        $this->assertDirectoryExists($destination);
        $this->assertFileExists($destination . '/mock-data.txt');

        // Clean up
        WPFS::delete($destination, true);
    }

    /**
     * @covers ::delete
     * @covers ::deleteDirectory
     */
    public function testDeleteFileAndDirectory(): void
    {
        $filePath = __DIR__ . '/output/mock-data.txt';
        $dirPath = __DIR__ . '/output/dir-to-delete';

        // Test file deletion
        $this->assertFileExists($filePath);
        $resultFile = WPFS::delete($filePath);
        $this->assertTrue($resultFile);
        $this->assertFileDoesNotExist($filePath);

        // Test directory deletion
        WPFS::createDirectory($dirPath);
        $this->assertDirectoryExists($dirPath);
        $resultDir = WPFS::deleteDirectory($dirPath);
        $this->assertTrue($resultDir);
        $this->assertDirectoryDoesNotExist($dirPath);
    }

    /**
     * @covers ::touch
     */
    public function testTouch(): void
    {
        $path = __DIR__ . '/output/touched-file.txt';
        $mtime = time() - 3600; // one hour ago
        $atime = time() - 1800; // 30 minutes ago

        $result = WPFS::touch($path, $mtime, $atime);
        $this->assertTrue($result);

        $this->assertFileExists($path);
        $this->assertEquals($mtime, WPFS::getLastModifiedTime($path));
        // Note: atime checking can be unreliable on some filesystems.
        // We will just check if the file was created.

        WPFS::delete($path);
    }

    /**
     * @covers ::createDirectory
     */
    public function testCreateDirectory(): void
    {
        $path = __DIR__ . '/output/new-directory';
        $nestedPath = __DIR__ . '/output/new-directory/nested/path';

        // Test recursive creation
        $result = WPFS::createDirectory($path);
        $this->assertTrue($result);
        $this->assertDirectoryExists($path);

        // Nested will be ignored
        $this->assertFalse(WPFS::createDirectory($nestedPath));

        // Clean up
        WPFS::delete(__DIR__ . '/output/new-directory', true);
    }

    /**
     * @covers ::createTempFile
     */
    public function testCreateTempFile(): void
    {
        $dir = __DIR__ . '/output';
        $tempFile = WPFS::createTempFile('test_', $dir);

        $this->assertIsString($tempFile);
        $this->assertFileExists($tempFile);
        $this->assertStringContainsString($dir, $tempFile);
        $this->assertStringContainsString('test_', basename($tempFile));

        // Clean up
        WPFS::delete($tempFile);
    }

    /**
     * @covers ::exists
     * @covers ::isFile
     * @covers ::isDirectory
     */
    public function testExistsAndTypeChecks(): void
    {
        $filePath = __DIR__ . '/input/mock-data.txt';
        $dirPath = __DIR__ . '/input';
        $nonExistentPath = __DIR__ . '/input/non-existent-file.txt';

        // exists
        $this->assertTrue(WPFS::exists($filePath));
        $this->assertTrue(WPFS::exists($dirPath));
        $this->assertFalse(WPFS::exists($nonExistentPath));

        // isFile
        $this->assertTrue(WPFS::isFile($filePath));
        $this->assertFalse(WPFS::isFile($dirPath));
        $this->assertFalse(WPFS::isFile($nonExistentPath));

        // isDirectory
        $this->assertTrue(WPFS::isDirectory($dirPath));
        $this->assertFalse(WPFS::isDirectory($filePath));
        $this->assertFalse(WPFS::isDirectory($nonExistentPath));
    }

    /**
     * @covers ::isReadable
     * @covers ::isWritable
     */
    public function testPermissionChecks(): void
    {
        $readableFile = __DIR__ . '/input/mock-data.txt';
        $writableFile = __DIR__ . '/output/mock-data.txt';

        // isReadable
        $this->assertTrue(WPFS::isReadable($readableFile));

        // isWritable
        $this->assertTrue(WPFS::isWritable($writableFile));
    }

    /**
     * @covers ::isBinary
     */
    public function testIsBinary(): void
    {
        $textContent = 'This is a simple text.';
        $binaryContent = "This contains a null byte \x00 which makes it binary.";

        $this->assertFalse(WPFS::isBinary($textContent));
        $this->assertTrue(WPFS::isBinary($binaryContent));
    }

    /**
     * @covers ::verifyMd5
     */
    public function testVerifyMd5(): void
    {
        $path = __DIR__ . '/input/mock-data.txt';
        $correctMd5 = md5_file($path);
        $incorrectMd5 = 'incorrect_md5_hash';

        $this->assertTrue(WPFS::verifyMd5($path, $correctMd5));
        $this->assertFalse(WPFS::verifyMd5($path, $incorrectMd5));
    }

    /**
     * @covers ::connect
     */
    public function testConnect(): void
    {
        // This method's outcome depends entirely on the test environment setup (direct, ftp, etc.).
        // In a standard 'direct' setup, it should always return true.
        $this->assertTrue(WPFS::connect());
    }

    /**
     * @covers ::ensureUniqueFilename
     */
    public function testEnsureUniqueFilename(): void
    {
        $dir = __DIR__ . '/output';
        $filename = 'mock-data.txt'; // This file already exists

        $uniqueFilename = WPFS::ensureUniqueFilename($dir, $filename);

        $this->assertNotEquals($filename, $uniqueFilename);
        $this->assertStringContainsString('mock-data-', $uniqueFilename);
    }

    /**
     * @covers ::setPermissions
     */
    public function testSetPermissions(): void
    {
        $path = __DIR__ . '/output/mock-data.txt';
        $mode = 0777;

        $result = WPFS::setPermissions($path, $mode);
        $this->assertTrue($result);

        // Get permissions and compare the last 3 digits
        $actualPermissions = substr(sprintf('%o', fileperms($path)), -3);
        $this->assertEquals('777', $actualPermissions);

        // Reset permissions for consistency
        WPFS::setPermissions($path, 0644);
    }

    /**
     * @covers ::setOwner
     * @covers ::setGroup
     */
    public function testSetOwnerAndGroup(): void
    {
        // These tests can only run if the user has the appropriate permissions (e.g., running as root)
        if (!function_exists('posix_getuid') || posix_getuid() !== 0) {
            $this->markTestSkipped('Cannot test changing file ownership unless running as root.');
        }

        $path = __DIR__ . '/output/mock-data.txt';
        $currentUser = posix_getlogin();
        $currentGroup = posix_getgrgid(posix_getgid())['name'];

        $this->assertTrue(WPFS::setOwner($path, $currentUser));
        $this->assertTrue(WPFS::setGroup($path, $currentGroup));
    }

    /**
     * @covers ::setCurrentDirectory
     */
    public function testSetCurrentDirectory(): void
    {
        $originalDir = WPFS::getCurrentPath();
        $newDir = __DIR__ . '/output';

        $result = WPFS::setCurrentDirectory($newDir);
        $this->assertTrue($result);
        $this->assertEquals(realpath($newDir), WPFS::getCurrentPath());

        // Restore original directory
        WPFS::setCurrentDirectory($originalDir);
    }

    /**
     * @covers ::invalidateOpCache
     * @covers ::invalidateDirectoryOpCache
     */
    public function testInvalidateOpCache(): void
    {
        if (!function_exists('opcache_invalidate') || !ini_get('opcache.enable_cli')) {
            $this->markTestSkipped('Opcache is not enabled for CLI, cannot test invalidation.');
        }

        $path = __DIR__ . '/output/mock-data-opcache.php';
        WPFS::putContents($path, '<?php echo "opcache test";');
        $compiled = opcache_compile_file($path);

        $this->assertTrue($compiled, 'Failed to compile the test file for Opcache.');
        $this->assertTrue(opcache_is_script_cached($path), 'File should be cached after compilation.');

        $result = WPFS::invalidateOpCache($path);

        $this->assertTrue($result, 'invalidateOpCache should return true.');
        $this->assertFalse(opcache_is_script_cached($path), 'File should be invalidated from Opcache.');

        WPFS::delete($path);
    }

    /**
     * @covers ::atomicWrite
     */
    public function testAtomicWrite(): void
    {
        $path = __DIR__ . '/output/atomic.txt';
        $content = 'This write should be atomic.';

        $result = WPFS::atomicWrite($path, $content);
        $this->assertTrue($result);
        $this->assertFileExists($path);
        $this->assertStringEqualsFile($path, $content);

        WPFS::delete($path);
    }

    /**
     * @covers ::append
     * @covers ::prepend
     * @covers ::replace
     */
    public function testFileContentManipulation(): void
    {
        $path = __DIR__ . '/output/mock-data.txt';
        $originalContent = WPFS::getContents($path);

        // Test append
        WPFS::append($path, ' Appended.');
        $this->assertStringEndsWith(' Appended.', WPFS::getContents($path));

        // Test prepend
        WPFS::prepend($path, 'Prepended. ');
        $this->assertStringStartsWith('Prepended. ', WPFS::getContents($path));

        // Test replace
        WPFS::replace($path, 'simple mock text', 'complex mock text');
        $this->assertStringContainsString('complex mock text', WPFS::getContents($path));
    }

    /**
     * @covers ::extension
     * @covers ::filename
     * @covers ::dirname
     */
    public function testPathInfoMethods(): void
    {
        $path = __DIR__ . '/input/mock-data.xml';

        $this->assertEquals('xml', WPFS::extension($path));
        $this->assertEquals('mock-data', WPFS::filename($path));
        $this->assertEquals(realpath(__DIR__ . '/input'), realpath(WPFS::dirname($path)));
    }

    /**
     * @covers ::cleanDirectory
     * @covers ::isDirectoryEmpty
     */
    public function testDirectoryCleaningAndEmptyCheck(): void
    {
        $dir = __DIR__ . '/output/test-dir';
        WPFS::createDirectory($dir);
        WPFS::putContents($dir . '/file1.txt', 'data');
        WPFS::putContents($dir . '/file2.txt', 'data');

        $this->assertFalse(WPFS::isDirectoryEmpty($dir));

        $result = WPFS::cleanDirectory($dir);
        $this->assertTrue($result);
        $this->assertTrue(WPFS::isDirectoryEmpty($dir));

        WPFS::delete($dir);
    }

    /**
     * @covers ::getMimeType
     */
    public function testGetMimeType(): void
    {
        // MIME types can vary slightly by system, we check for common values.
        $this->assertContains(WPFS::getMimeType(__DIR__ . '/input/mock-data.txt'), ['text/plain', 'inode/x-empty']);
        $this->assertEquals('text/html', WPFS::getMimeType(__DIR__ . '/input/mock-data.html'));
        $this->assertEquals('application/json', WPFS::getMimeType(__DIR__ . '/input/mock-data.json'));
    }

    /**
     * @covers ::hash
     * @covers ::filesEqual
     */
    public function testHashingAndFileEquality(): void
    {
        $path1 = __DIR__ . '/input/mock-data.txt';
        $path2 = __DIR__ . '/output/mock-data.txt'; // A copy of path1
        $path3 = __DIR__ . '/input/mock-data.json';

        // hash
        $hash = WPFS::hash($path1);
        $this->assertEquals(md5_file($path1), $hash);

        // filesEqual
        $this->assertTrue(WPFS::filesEqual($path1, $path2));
        $this->assertFalse(WPFS::filesEqual($path1, $path3));
    }
}
