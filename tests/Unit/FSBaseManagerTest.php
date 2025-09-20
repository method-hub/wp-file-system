<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSBaseManager;
use phpmock\phpunit\PHPMock;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseManager
 */
class FSBaseManagerTest extends WP_UnitTestCase
{
    use PHPMock;

    /**  @group ignore-ci/cd */
    public function testEnsureUniqueFilename(): void
    {
        $directory = '/www/html/wordpress';
        $filename = 'index.php';
        $expected = 'index-1.php';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_unique_filename');
        $globalFnMock->expects($this->once())
            ->with($directory, $filename, null)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->ensureUniqueFilename($directory, $filename);

        $this->assertEquals($expected, $result);
    }

    public function testSetOwner(): void
    {
        $path = '/www/html/wordpress/wp-content/uploads';
        $owner = 'www-data';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('chown')
            ->with($path, $owner, false)
            ->willReturn(true);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->setOwner($path, $owner, false);

        $this->assertTrue($result);
    }

    public function testSetGroup(): void
    {
        $path = '/www/html/wordpress/wp-content/plugins';
        $group = 'www-data';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('chgrp')
            ->with($path, $group, false)
            ->willReturn(true);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->setGroup($path, $group);

        $this->assertTrue($result);
    }

    public function testSetPermissions(): void
    {
        $path = '/www/html/wordpress/wp-config.php';
        $permissions = 0644;
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('chmod')
            ->with($path, $permissions, true)
            ->willReturn(true);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->setPermissions($path, $permissions, true);

        $this->assertTrue($result);
    }

    public function testSetCurrentDirectory(): void
    {
        $path = '/www/html/wordpress/wp-content/uploads';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('chdir')
            ->with($path)
            ->willReturn(true);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->setCurrentDirectory($path);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testInvalidateOpCache(): void
    {
        $path = '/www/html/wordpress/wp-includes/functions.php';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_opcache_invalidate');
        $globalFnMock->expects($this->once())
            ->with($path, true)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseManager($fsMock);

        $result = $sut->invalidateOpCache($path, true);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testInvalidateDirectoryOpCache(): void
    {
        $path = '/www/html/wordpress/wp-content/upgrade';
        $globalFnMock = $this->getFunctionMock(
            'Metapraxis\WPFileSystem\Adapters',
            'wp_opcache_invalidate_directory'
        );
        $globalFnMock->expects($this->once())
            ->with($path);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseManager($fsMock);

        $sut->invalidateDirectoryOpCache($path);

        // Assert
        // No assertion needed as the method does not return a value.
        // The test passes if the mocked function is called as expected.
    }
}
