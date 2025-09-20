<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Builder\FSBuilder;
use Metapraxis\WPFileSystem\Exceptions\FSPathNotFoundException;
use WP_Filesystem_Base;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Builder\FSBuilder
 */
class FSBuilderTest extends WP_UnitTestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = '/path/to/file.txt';
    }

    /**
     * @throws FSPathNotFoundException
     */
    public function testFromLoadsContentAndReturnsBuilder()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $fs->method('exists')->with($this->path)->willReturn(true);
        $fs->method('get_contents')->with($this->path)->willReturn('initial');

        $builder = FSBuilder::from($this->path, $fs);

        $this->assertInstanceOf(FSBuilder::class, $builder);
        $this->assertSame('initial', $builder->get());
    }

    public function testFromThrowsWhenFileNotFound()
    {
        $this->expectException(FSPathNotFoundException::class);

        $fs = $this->createMock(WP_Filesystem_Base::class);
        $fs->method('exists')->with($this->path)->willReturn(false);

        FSBuilder::from($this->path, $fs);
    }

    public function testCreateReturnsEmptyBuffer()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $builder = FSBuilder::create($this->path, $fs);

        $this->assertInstanceOf(FSBuilder::class, $builder);
        $this->assertNull($builder->get());
    }

    public function testSetContentSetsBufferAndIsChainable()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs);

        $returned = $builder->setContent('hello');

        $this->assertSame($builder, $returned);
        $this->assertSame('hello', $builder->get());
    }

    public function testAppendAppendsToBufferFromNull()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs);

        $builder->append('A')->append('B');

        $this->assertSame('AB', $builder->get());
    }

    public function testPrependPrependsToBuffer()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('middle');

        $builder->prepend('start-');

        $this->assertSame('start-middle', $builder->get());
    }

    public function testReplaceReplacesContent()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('hello world');

        $builder->replace('world', 'WP');

        $this->assertSame('hello WP', $builder->get());
    }

    public function testReplaceRegexReplacesWithPattern()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('user-123');

        $builder->replaceRegex('/user-\d+/', 'user-ID');

        $this->assertSame('user-ID', $builder->get());
    }

    public function testTransformAppliesCallback()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('abc');

        $builder->transform('strtoupper');

        $this->assertSame('ABC', $builder->get());
    }

    public function testWhenWithBoolTrueExecutesCallback()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('');

        $called = false;
        $builder->when(true, function (FSBuilder $b) use (&$called) {
            $called = true;
            $b->append('X');
        });

        $this->assertTrue($called);
        $this->assertSame('X', $builder->get());
    }

    public function testWhenWithBoolFalseSkipsCallback()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('a');

        $called = false;
        $builder->when(false, function () use (&$called) {
            $called = true;
        });

        $this->assertFalse($called);
        $this->assertSame('a', $builder->get());
    }

    public function testWhenWithCallableCondition()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('');

        $builder->when(function (FSBuilder $b) {
            return $b->get() === '';
        }, function (FSBuilder $b) {
            $b->append('C');
        });

        $this->assertSame('C', $builder->get());
    }

    public function testUnlessWithBoolTrueSkipsCallback()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('a');

        $called = false;
        $builder->unless(true, function () use (&$called) {
            $called = true;
        });

        $this->assertFalse($called);
        $this->assertSame('a', $builder->get());
    }

    public function testUnlessWithBoolFalseExecutesCallback()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('');

        $called = false;
        $builder->unless(false, function (FSBuilder $b) use (&$called) {
            $called = true;
            $b->append('Y');
        });

        $this->assertTrue($called);
        $this->assertSame('Y', $builder->get());
    }

    public function testWithPermissionsAffectsSave()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'content', 0600)
            ->willReturn(true);

        $fs->expects($this->never())->method('chown');
        $fs->expects($this->never())->method('chgrp');

        $builder = FSBuilder::create($this->path, $fs)
            ->withPermissions(0600)
            ->setContent('content');

        $this->assertTrue($builder->save());
    }

    public function testSaveUsesDefaultPermissionsWhenNotSet()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'content', FS_CHMOD_FILE)
            ->willReturn(true);

        $builder = FSBuilder::create($this->path, $fs)->setContent('content');

        $this->assertTrue($builder->save());
    }

    public function testWithOwnerOnlySetsChownOnSave()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'data', FS_CHMOD_FILE)
            ->willReturn(true);

        $fs->expects($this->once())
            ->method('chown')
            ->with($this->path, 'www-data');

        $fs->expects($this->never())->method('chgrp');

        $builder = FSBuilder::create($this->path, $fs)
            ->withOwner('www-data')
            ->setContent('data');

        $this->assertTrue($builder->save());
    }

    public function testWithGroupOnlySetsChgrpOnSave()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'data', FS_CHMOD_FILE)
            ->willReturn(true);

        $fs->expects($this->never())->method('chown');

        $fs->expects($this->once())
            ->method('chgrp')
            ->with($this->path, 'www-group');

        $builder = FSBuilder::create($this->path, $fs)
            ->withOwner(null, 'www-group')
            ->setContent('data');

        $this->assertTrue($builder->save());
    }

    public function testWithOwnerAndGroupBothSetOnSave()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'data', FS_CHMOD_FILE)
            ->willReturn(true);

        $fs->expects($this->once())->method('chown')->with($this->path, 'user');
        $fs->expects($this->once())->method('chgrp')->with($this->path, 'group');

        $builder = FSBuilder::create($this->path, $fs)
            ->withOwner('user', 'group')
            ->setContent('data');

        $this->assertTrue($builder->save());
    }

    public function testSaveReturnsFalseAndSkipsChownChgrpOnFailure()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('put_contents')
            ->with($this->path, 'data', FS_CHMOD_FILE)
            ->willReturn(false);

        $fs->expects($this->never())->method('chown');
        $fs->expects($this->never())->method('chgrp');

        $builder = FSBuilder::create($this->path, $fs)
            ->withOwner('user', 'group')
            ->setContent('data');

        $this->assertFalse($builder->save());
    }

    public function testBackupDefaultSuffix()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('copy')
            ->with($this->path, $this->path . '.bak', true);

        $builder = FSBuilder::create($this->path, $fs);

        $returned = $builder->backup();
        $this->assertSame($builder, $returned);
    }

    public function testBackupCustomSuffix()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('copy')
            ->with($this->path, $this->path . '.backup', true);

        $builder = FSBuilder::create($this->path, $fs);

        $this->assertSame($builder, $builder->backup('.backup'));
    }

    public function testGetReturnsCurrentBuffer()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $builder = FSBuilder::create($this->path, $fs)->setContent('x');

        $this->assertSame('x', $builder->get());
    }

    public function testMoveProxiesToFilesystemDefault()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $newPath = '/new/path/file.txt';

        $fs->expects($this->once())
            ->method('move')
            ->with($this->path, $newPath, false)
            ->willReturn(true);

        $builder = FSBuilder::create($this->path, $fs);

        $this->assertTrue($builder->move($newPath));
    }

    public function testMoveProxiesToFilesystemOverwrite()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);
        $newPath = '/new/path/file.txt';

        $fs->expects($this->once())
            ->method('move')
            ->with($this->path, $newPath, true)
            ->willReturn(true);

        $builder = FSBuilder::create($this->path, $fs);

        $this->assertTrue($builder->move($newPath, true));
    }

    public function testDeleteProxiesToFilesystem()
    {
        $fs = $this->createMock(WP_Filesystem_Base::class);

        $fs->expects($this->once())
            ->method('delete')
            ->with($this->path)
            ->willReturn(true);

        $builder = FSBuilder::create($this->path, $fs);

        $this->assertTrue($builder->delete());
    }
}
