<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSBaseAction;
use phpmock\phpunit\PHPMock;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseAction
 */
class FSBaseActionTest extends WP_UnitTestCase
{
    use PHPMock;

    public function testPutContents(): void
    {
        $file = '/www/html/wordpress/wp-content/uploads/my-file.log';
        $contents = 'Error log message';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($file, $contents, FS_CHMOD_FILE)
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->putContents($file, $contents, FS_CHMOD_FILE);

        $this->assertTrue($result);
    }

    public function testCopyFile(): void
    {
        $source = '/www/html/wordpress/wp-config-sample.php';
        $destination = '/www/html/wordpress/wp-config.php';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('copy')
            ->with($source, $destination, true, FS_CHMOD_FILE)
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->copyFile($source, $destination, true, FS_CHMOD_FILE);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testCopyDirectory(): void
    {
        $from = '/www/html/wordpress/wp-content/themes/twentytwentyfour';
        $to = '/www/html/wordpress/wp-content/themes/my-child-theme';
        $skipList = ['.git', 'node_modules'];
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'copy_dir');
        $globalFnMock->expects($this->once())
            ->with($from, $to, $skipList)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->copyDirectory($from, $to, $skipList);

        $this->assertTrue($result);
    }

    public function testMoveFile(): void
    {
        $source = '/www/html/wordpress/wp-content/uploads/output.tmp';
        $destination = '/www/html/wordpress/wp-content/uploads/2025/08/permanent.jpg';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('move')
            ->with($source, $destination, true)
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->moveFile($source, $destination, true);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testMoveDirectory(): void
    {
        $from = '/www/html/wordpress/wp-content/upgrade/my-plugin';
        $to = '/www/html/wordpress/wp-content/plugins/my-plugin';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'move_dir');
        $globalFnMock->expects($this->once())
            ->with($from, $to, false)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->moveDirectory($from, $to);

        $this->assertTrue($result);
    }

    public function testDelete(): void
    {
        $path = '/www/html/wordpress/wp-content/themes/my-theme/legacy.css';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('delete')
            ->with($path, false, 'f')
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->delete($path, false, 'f');

        $this->assertTrue($result);
    }

    public function testTouch(): void
    {
        $file = '/www/html/wordpress/wp-content/object-cache.php';
        $time = time();
        $atime = time() - 3600;
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('touch')
            ->with($file, $time, $atime)
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->touch($file, $time, $atime);

        $this->assertTrue($result);
    }

    public function testCreateDirectory(): void
    {
        $path = '/www/html/wordpress/wp-content/mu-plugins/custom';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('mkdir')
            ->with($path, FS_CHMOD_DIR, 'www-data', 'www-data')
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->createDirectory($path, FS_CHMOD_DIR, 'www-data', 'www-data');

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testCreateTempFile(): void
    {
        $dir = '/www/html/wordpress/wp-content/upgrade';
        $expected = '/www/html/wordpress/wp-content/upgrade/plugin_upgrade-[random-bytes].tmp';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_tempnam');
        $globalFnMock->expects($this->once())
            ->with('plugin_upgrade_', $dir)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->createTempFile('plugin_upgrade_', $dir);

        $this->assertEquals($expected, $result);
    }

    public function testDeleteDirectory(): void
    {
        $path = '/www/html/wordpress/wp-content/themes/old-unused-theme';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('rmdir')
            ->with($path, true)
            ->willReturn(true);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->deleteDirectory($path, true);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testHandleUpload(): void
    {
        $file = ['name' => 'header.png', 'tmp_name' => '/tmp/phpABC123'];
        $overrides = ['test_form' => false];
        $time = '2025-08-01';
        $expected = [
            'file' => '/www/html/wordpress/wp-content/uploads/2025/08/header.png',
            'url'  => 'https://example.com/wp-content/uploads/2025/08/header.png',
        ];
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_handle_upload');
        $globalFnMock->expects($this->once())
            ->with($this->equalTo($file), $this->equalTo($overrides), $this->equalTo($time))
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->handleUpload($file, $overrides, $time);

        $this->assertEquals($expected, $result);
    }

    /**  @group ignore-ci/cd */
    public function testHandleSideload(): void
    {
        $file = ['name' => 'avatar.jpg', 'tmp_name' => '/tmp/phpXYZ789'];
        $overrides = ['test_form' => false];
        $time = '2025-08-01';
        $expected = [
            'file' => '/www/html/wordpress/wp-content/uploads/2025/08/avatar.jpg',
            'url'  => 'https://example.com/wp-content/uploads/2025/08/avatar.jpg',
        ];
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_handle_sideload');
        $globalFnMock->expects($this->once())
            ->with($this->equalTo($file), $this->equalTo($overrides), $this->equalTo($time))
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->handleSideload($file, $overrides, $time);

        $this->assertEquals($expected, $result);
    }

    /**  @group ignore-ci/cd */
    public function testDownloadFromUrl(): void
    {
        $url = 'https://downloads.wordpress.org/plugin/akismet.latest.zip';
        $expected = '/www/html/wordpress/wp-content/upgrade/akismet.latest.zip.tmp';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'download_url');
        $globalFnMock->expects($this->once())
            ->with($url, 300, false)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->downloadFromUrl($url);

        $this->assertEquals($expected, $result);
    }

    /**  @group ignore-ci/cd */
    public function testUnzip(): void
    {
        $file = '/www/html/wordpress/wp-content/upgrade/akismet.latest.zip';
        $to = '/www/html/wordpress/wp-content/plugins/';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'unzip_file');
        $globalFnMock->expects($this->once())
            ->with($file, $to)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAction($fsMock);

        $result = $sut->unzip($file, $to);

        $this->assertTrue($result);
    }
}
