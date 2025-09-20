<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSBaseAuditor;
use phpmock\phpunit\PHPMock;
use WP_Error;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseAuditor
 */
class FSBaseAuditorTest extends WP_UnitTestCase
{
    use PHPMock;

    public function testIsBinary(): void
    {
        $binaryContent = 'This contains binary content\x01';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('is_binary')
            ->with($binaryContent)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isBinary($binaryContent);

        $this->assertTrue($result);
    }

    public function testIsFile(): void
    {
        $file = '/www/html/wordpress/wp-config.php';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('is_file')
            ->with($file)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isFile($file);

        $this->assertTrue($result);
    }

    public function testIsDirectory(): void
    {
        $directory = '/www/html/wordpress/wp-content';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('is_dir')
            ->with($directory)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isDirectory($directory);

        $this->assertTrue($result);
    }

    public function testIsReadable(): void
    {
        $file = '/www/html/wordpress/wp-config.php';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('is_readable')
            ->with($file)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isReadable($file);

        $this->assertTrue($result);
    }

    public function testIsWritable(): void
    {
        $directory = '/www/html/wordpress/wp-content/uploads';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('is_writable')
            ->with($directory)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isWritable($directory);

        $this->assertTrue($result);
    }

    public function testExists(): void
    {
        $file = '/www/html/wordpress/wp-config.php';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('exists')
            ->with($file)
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->exists($file);

        $this->assertTrue($result);
    }

    public function testConnect(): void
    {
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('connect')
            ->willReturn(true);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->connect();

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testVerifyMd5(): void
    {
        $file = '/www/html/wordpress/wp-admin/install.php';
        $md5 = 'd41d8cd98f00b204e9800998ecf8427e';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'verify_file_md5');
        $globalFnMock->expects($this->once())
            ->with($file, $md5)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->verifyMd5($file, $md5);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testVerifyMd5ReturnsFalseOnWpError(): void
    {
        $file = '/www/html/wordpress/wp-admin/install.php';
        $md5 = 'd41d8cd98f00b204e9800998ecf8427e';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'verify_file_md5');
        $globalFnMock->expects($this->once())
            ->with($file, $md5)
            ->willReturn(new WP_Error());
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->verifyMd5($file, $md5);

        $this->assertFalse($result);
    }

    /**  @group ignore-ci/cd */
    public function testVerifySignature(): void
    {
        $file = '/www/html/wordpress/wp-content/plugins/akismet/akismet.php';
        $signatures = ['some_dummy_signature_string'];
        $filenameForErrors = 'akismet.php';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'verify_file_signature');
        $globalFnMock->expects($this->once())
            ->with($file, $signatures, $filenameForErrors)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->verifySignature($file, $signatures, $filenameForErrors);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testIsZipFile(): void
    {
        $zipFile = '/www/html/wordpress/wp-content/upgrade/akismet.latest.zip';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_zip_file_is_valid');
        $globalFnMock->expects($this->once())
            ->with($zipFile)
            ->willReturn(true);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseAuditor($fsMock);

        $result = $sut->isZipFile($zipFile);

        $this->assertTrue($result);
    }
}
