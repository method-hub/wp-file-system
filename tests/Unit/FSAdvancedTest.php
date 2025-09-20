<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use DOMDocument;
use Metapraxis\WPFileSystem\Adapters\FSAdvanced;
use phpmock\phpunit\PHPMock;
use SimpleXMLElement;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Adapters\FSAdvanced
 */
class FSAdvancedTest extends WP_UnitTestCase
{
    use PHPMock;

    // ... (существующие тесты остаются без изменений)

    public function testAtomicWrite(): void
    {
        $path = '/wp-content/uploads/config.json';
        $content = '{"setting":"value"}';
        $mode = 0644;

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($this->stringContains(basename($path)), $content)
            ->willReturn(true);
        $fsMock->expects($this->once())
            ->method('chmod')
            ->with($this->stringContains(basename($path)), $mode)
            ->willReturn(true);
        $fsMock->expects($this->once())
            ->method('move')
            ->with($this->stringContains(basename($path)), $path, true)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->atomicWrite($path, $content, $mode);

        $this->assertTrue($result);
    }

    public function testAppend(): void
    {
        $path = '/wp-content/debug.log';
        $oldContent = '[2025-08-01 10:00:00] Initial log.' . PHP_EOL;
        $newContent = '[2025-08-01 10:05:00] Appended log.' . PHP_EOL;
        $expected = $oldContent . $newContent;

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($oldContent);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($path, $expected)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->append($path, $newContent);

        $this->assertTrue($result);
    }

    public function testPrepend(): void
    {
        $path = '/.htaccess';
        $oldContent = '# END WordPress';
        $newContent = '# BEGIN WordPress' . PHP_EOL;
        $expected = $newContent . $oldContent;

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($oldContent);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($path, $expected)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->prepend($path, $newContent);

        $this->assertTrue($result);
    }

    public function testReplace(): void
    {
        $path = '/wp-config.php';
        $search = "define( 'WP_DEBUG', false );";
        $replace = "define( 'WP_DEBUG', true );";
        $originalContent = "<?php\n" . $search;
        $expectedContent = "<?php\n" . $replace;

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($originalContent);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($path, $expectedContent)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->replace($path, $search, $replace);

        $this->assertTrue($result);
    }

    public function testExtension(): void
    {
        $sut = new FSAdvanced($this->createMock(WP_Filesystem_Base::class));

        $result1 = $sut->extension('wp-content/themes/twentytwentyfive/style.css');
        $result2 = $sut->extension('/wp-content/plugins/my-plugin/readme.txt');
        $result3 = $sut->extension('file-without-extension');

        $this->assertEquals('css', $result1);
        $this->assertEquals('txt', $result2);
        $this->assertEquals('', $result3);
    }

    public function testFilename(): void
    {
        $sut = new FSAdvanced($this->createMock(WP_Filesystem_Base::class));

        $result1 = $sut->filename('index.php');
        $result2 = $sut->filename('/wp-content/plugins/my-plugin/main.php');

        $this->assertEquals('index', $result1);
        $this->assertEquals('main', $result2);
    }

    public function testDirname(): void
    {
        $sut = new FSAdvanced($this->createMock(WP_Filesystem_Base::class));

        $result1 = $sut->dirname('robots.txt');
        $result2 = $sut->dirname('/wp-content/themes/twentytwentyfive/functions.php');

        $this->assertEquals('.', $result1);
        $this->assertEquals('/wp-content/themes/twentytwentyfive', $result2);
    }

    public function testCleanDirectory(): void
    {
        $directory = '/wp-content/uploads/cache';
        $items = ['file1.tmp' => [], 'file2.tmp' => []];

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())->method('dirlist')->with($directory)->willReturn($items);
        $fsMock->method('delete')->willReturn(true);
        $fsMock->expects($this->exactly(2))->method('delete');
        $sut = new FSAdvanced($fsMock);

        $result = $sut->cleanDirectory($directory);

        $this->assertTrue($result);
    }

    public function testIsDirectoryEmpty(): void
    {
        $directory = '/wp-content/uploads/cache';
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSAdvanced($fsMock);
        $fsMock->expects($this->once())->method('is_dir')->with($directory)->willReturn(true);
        $fsMock->expects($this->once())->method('dirlist')->with($directory)->willReturn([]);

        $result = $sut->isDirectoryEmpty($directory);

        $this->assertTrue($result);
    }

    /**  @group ignore-ci/cd */
    public function testGetMimeType(): void
    {
        $path = '/wp-content/uploads/2025/08/image.jpg';
        $expected = 'image/jpeg';

        $filetypeMock = $this->getFunctionMock('Metapraxis\WPFileSystem\Adapters', 'wp_check_filetype');
        $filetypeMock->expects($this->once())->with($path)->willReturn(['type' => $expected]);
        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())->method('exists')->with($path)->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->getMimeType($path);

        $this->assertEquals($expected, $result);
    }

    public function testHash(): void
    {
        $path = '/index.php';
        $content = '<?php // TestCase index file';
        $expected = md5($content);

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())->method('get_contents')->with($path)->willReturn($content);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->hash($path);

        $this->assertEquals($expected, $result);
    }

    public function testFilesEqual(): void
    {
        $path1 = '/wp-content/themes/twentytwentyfive/style.css';
        $path2 = '/wp-content/themes/twentytwentyfive/style.min.css';
        $content = '/* This content is identical. */';

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $sut = new FSAdvanced($fsMock);
        $fsMock->method('exists')->willReturnMap([
            [$path1, true],
            [$path2, true],
        ]);
        $fsMock->method('get_contents')->willReturnMap([
            [$path1, $content],
            [$path2, $content],
        ]);
        $fsMock->expects($this->exactly(2))->method('exists');
        $fsMock->expects($this->exactly(2))->method('get_contents');

        $result = $sut->filesEqual($path1, $path2);

        $this->assertTrue($result);
    }

    public function testReadJson(): void
    {
        $path = '/wp-content/themes/twentytwentyfive/theme.json';
        $content = '{"version": 2, "settings": {}}';
        $expected = ['version' => 2, 'settings' => []];

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())->method('get_contents')->with($path)->willReturn($content);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->readJson($path);

        $this->assertEquals($expected, $result);
    }

    public function testWriteJson(): void
    {
        $path = '/wp-content/uploads/export.json';
        $data = ['status' => 'ok', 'data' => [1, 2, 3]];
        $expected = json_encode($data, JSON_PRETTY_PRINT);

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())->method('put_contents')->with($path, $expected)->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->writeJson($path, $data);

        $this->assertTrue($result);
    }

    public function testReadXml(): void
    {
        $path = '/sitemap.xml';
        $content = '<?xml version="1.0"?><urlset><url><loc>https://example.com/</loc></url></urlset>';

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($content);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->readXml($path);

        $this->assertInstanceOf(SimpleXMLElement::class, $result);
        $this->assertEquals('https://example.com/', (string)$result->url->loc);
    }

    public function testWriteXml(): void
    {
        $path = '/output.xml';
        $inputString = '<data><item>value</item></data>';
        $expectedContent = '<?xml version="1.0"?>' . PHP_EOL . $inputString . PHP_EOL;

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($path, $expectedContent)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->writeXml($path, $inputString);

        $this->assertTrue($result);
    }

    public function testReadDom(): void
    {
        $path = '/feed.xml';
        $content = '<?xml version="1.0"?><rss><channel><title>My Feed</title></channel></rss>';

        $fsMock = $this->createMock(WP_Filesystem_Base::class);
        $fsMock->expects($this->once())
            ->method('get_contents')
            ->with($path)
            ->willReturn($content);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->readDom($path);

        $this->assertInstanceOf(DOMDocument::class, $result);
        $this->assertEquals('rss', $result->documentElement->tagName);
    }

    public function testWriteDom(): void
    {
        $path = '/dom_output.xml';
        $inputData = '<html lang=""></html>';

        $expectedContent = '<?xml version="1.0"?>' . PHP_EOL . '<html lang=""/>' . PHP_EOL;
        $fsMock = $this->createMock(WP_Filesystem_Base::class);

        $fsMock->expects($this->once())
            ->method('put_contents')
            ->with($path, $expectedContent)
            ->willReturn(true);
        $sut = new FSAdvanced($fsMock);

        $result = $sut->writeDom($path, $inputData);

        $this->assertTrue($result);
    }
}
