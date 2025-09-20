<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSBaseReader;
use phpmock\phpunit\PHPMock;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSBaseReader
 */
class FSBaseReaderTest extends WP_UnitTestCase
{
    use PHPMock;

    /**  @group ignore-ci/cd */
    public function testGetHomePage(): void
    {
        $expected = '/www/html/wordpress/';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'get_home_path');
        $globalFnMock->expects($this->once())->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $homePath = $sut->getHomePath();

        $this->assertEquals($expected, $homePath);
    }

    public function testGetInstallationPath(): void
    {
        $expected = '/www/html/wordpress/';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('abspath')
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $installationPath = $sut->getInstallationPath();

        $this->assertEquals($expected, $installationPath);
    }

    public function testGetContentPath(): void
    {
        $expected = '/www/html/wordpress/wp-content';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('wp_content_dir')
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $contentPath = $sut->getContentPath();

        $this->assertEquals($expected, $contentPath);
    }

    public function testGetPluginsPath(): void
    {
        $expected = '/www/html/wordpress/wp-content/plugins';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('wp_plugins_dir')
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $pluginsPath = $sut->getPluginsPath();

        $this->assertEquals($expected, $pluginsPath);
    }

    public function testGetThemesPath(): void
    {
        $theme = 'twentytwentyone';
        $expected = '/www/html/wordpress/wp-content/themes/twentytwentyone';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('wp_themes_dir')
            ->with($theme)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $themesPath = $sut->getThemesPath($theme);

        $this->assertEquals($expected, $themesPath);
    }

    public function testGetLangPath(): void
    {
        $expected = '/www/html/wordpress/wp-content/languages';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('wp_lang_dir')
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $langPath = $sut->getLangPath();

        $this->assertEquals($expected, $langPath);
    }

    public function testGetHumanReadablePermissions(): void
    {
        $path = '/www/html/wordpress/wp-content/index.php';
        $expected = 'drwxr-xr-x';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('gethchmod')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $permissions = $sut->getHumanReadablePermissions($path);

        $this->assertEquals($expected, $permissions);
    }

    public function testGetPermissions(): void
    {
        $path = '/www/html/wordpress/wp-config.php';
        $expected = '0644';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('getchmod')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $permissions = $sut->getPermissions($path);

        $this->assertEquals($expected, $permissions);
    }

    public function testGetContents(): void
    {
        $path = '/www/html/wordpress/.htaccess';
        $expected = '# BEGIN WordPress';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $contents = $sut->getContents($path);

        $this->assertEquals($expected, $contents);
    }

    public function testGetContentsAsArray(): void
    {
        $path = '/www/html/wordpress/.htaccess';
        $expected = ['# BEGIN WordPress', '# END WordPress'];
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents_array')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $contents = $sut->getContentsAsArray($path);

        $this->assertEquals($expected, $contents);
    }

    public function testGetCurrentPath(): void
    {
        $expected = '/www/html/wordpress/wp-admin';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('cwd')
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $path = $sut->getCurrentPath();

        $this->assertEquals($expected, $path);
    }

    public function testGetOwner(): void
    {
        $path = '/www/html/wordpress/wp-config.php';
        $expected = 'www-data';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('owner')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $owner = $sut->getOwner($path);

        $this->assertEquals($expected, $owner);
    }

    public function testGetGroup(): void
    {
        $path = '/www/html/wordpress/wp-config.php';
        $expected = 'www-data';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('group')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $group = $sut->getGroup($path);

        $this->assertEquals($expected, $group);
    }

    public function testGetLastAccessedTime(): void
    {
        $path = '/www/html/wordpress/index.php';
        $expected = time();
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('atime')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $atime = $sut->getLastAccessedTime($path);

        $this->assertEquals($expected, $atime);
    }

    public function testGetLastModifiedTime(): void
    {
        $path = '/www/html/wordpress/index.php';
        $expected = time();
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('mtime')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $mtime = $sut->getLastModifiedTime($path);

        $this->assertEquals($expected, $mtime);
    }

    public function testGetFileSize(): void
    {
        $path = '/www/html/wordpress/wp-includes/js/jquery/jquery.js';
        $expected = 290193;
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('size')
            ->with($path)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $size = $sut->getFileSize($path);

        $this->assertEquals($expected, $size);
    }

    public function testGetPermissionsAsOctal(): void
    {
        $permissions = 'drwxr-xr-x';
        $expected = '0755';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('getnumchmodfromh')
            ->with($permissions)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $octal = $sut->getPermissionsAsOctal($permissions);

        $this->assertEquals($expected, $octal);
    }

    public function testGetDirectoryList(): void
    {
        $path = '/www/html/wordpress/wp-content/themes';
        $expected = [
            'index.php' => [],
            'twentytwentyfour' => [],
        ];
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('dirlist')
            ->with($path, true, false)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $dirList = $sut->getDirectoryList($path);

        $this->assertEquals($expected, $dirList);
    }

    /**  @group ignore-ci/cd */
    public function testGetFiles(): void
    {
        $path = '/www/html/wordpress/wp-includes/widgets';
        $levels = 100;
        $exclusions = [];
        $expected = [
            'class-wp-widget-archives.php',
            'class-wp-widget-calendar.php',
        ];
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'list_files');
        $globalFnMock->expects($this->once())
            ->with($path, $levels, $exclusions, false)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $files = $sut->getFiles($path, $levels, $exclusions);

        $this->assertEquals($expected, $files);
    }

    /**  @group ignore-ci/cd */
    public function testGetUploadsDirInfo(): void
    {
        $expected = [
            'path'    => '/www/html/wordpress/wp-content/uploads/2025/08',
            'url'     => 'https://example.com/wp-content/uploads/2025/08',
            'subdir'  => '/2025/08',
            'basedir' => '/www/html/wordpress/wp-content/uploads',
            'baseurl' => 'http://example.com/wp-content/uploads',
            'error'   => false,
        ];
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_upload_dir');
        $globalFnMock->expects($this->once())
            ->with(null, true, false)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $info = $sut->getUploadsDirInfo();

        $this->assertEquals($expected, $info);
    }

    /**  @group ignore-ci/cd */
    public function testGetTempDir(): void
    {
        $expected = '/tmp/';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'get_temp_dir');
        $globalFnMock->expects($this->once())->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $tempDir = $sut->getTempDir();

        $this->assertEquals($expected, $tempDir);
    }

    /**  @group ignore-ci/cd */
    public function testGetNormalizePath(): void
    {
        $path = 'C:\\www\\wordpress\\';
        $expected = 'C:/www/wordpress/';

        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_normalize_path');
        $globalFnMock->expects($this->once())
            ->with($path)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $normalized = $sut->getNormalizePath($path);

        $this->assertEquals($expected, $normalized);
    }

    /**  @group ignore-ci/cd */
    public function testGetSanitizeFilename(): void
    {
        $filename = 'A Crazy File Name?.zip';
        $expected = 'A-Crazy-File-Name.zip';
        $globalFnMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'sanitize_file_name');
        $globalFnMock->expects($this->once())
            ->with($filename)
            ->willReturn($expected);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSBaseReader($fsMock);

        $sanitized = $sut->getSanitizeFilename($filename);

        $this->assertEquals($expected, $sanitized);
    }

    public function testFindFolder(): void
    {
        $basePath = '/www/html/wordpress';
        $expected = '/www/html/wordpress/wp-content';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('find_folder')
            ->with($basePath)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $found = $sut->findFolder($basePath);

        $this->assertEquals($expected, $found);
    }

    public function testSearchForFolder(): void
    {
        $folder = 'uploads';
        $base = '.';
        $expected = '/www/html/wordpress/wp-content/uploads';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('search_for_folder')
            ->with($folder, $base, false)
            ->willReturn($expected);
        $sut = new FSBaseReader($fsMock);

        $found = $sut->searchForFolder($folder, $base);

        $this->assertEquals($expected, $found);
    }
}
