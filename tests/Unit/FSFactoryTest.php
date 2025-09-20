<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSAdvanced;
use Metapraxis\WPFileSystem\Adapters\FSBaseAction;
use Metapraxis\WPFileSystem\Adapters\FSBaseAuditor;
use Metapraxis\WPFileSystem\Adapters\FSBaseManager;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;
use Metapraxis\WPFileSystem\Exceptions\FSInstanceTypeException;
use Metapraxis\WPFileSystem\Factory\FSFactory;
use WP_UnitTestCase;

/**
 * @covers \Metapraxis\WPFileSystem\Factory\FSFactory
 */
class FSFactoryTest extends WP_UnitTestCase
{
    /**
     * @throws FSInstanceTypeException
     */
    public function testCreateFSBaseReaderInstance()
    {
        $firstInstance = FSFactory::create(FSBaseReader::class);
        $secondInstance = FSFactory::create(FSBaseReader::class);
        $baseReader = FSFactory::getReader();

        $this->assertSame($firstInstance, $secondInstance);
        $this->assertInstanceOf(FSBaseReader::class, $baseReader);
    }

    /**
     * @throws FSInstanceTypeException
     */
    public function testCreateFSBaseManagerInstance()
    {
        $firstInstance = FSFactory::create(FSBaseManager::class);
        $secondInstance = FSFactory::create(FSBaseManager::class);
        $baseReader = FSFactory::getManager();

        $this->assertSame($firstInstance, $secondInstance);
        $this->assertInstanceOf(FSBaseManager::class, $baseReader);
    }

    /**
     * @throws FSInstanceTypeException
     */
    public function testCreateFSBaseAuditorInstance()
    {
        $firstInstance = FSFactory::create(FSBaseAuditor::class);
        $secondInstance = FSFactory::create(FSBaseAuditor::class);
        $baseReader = FSFactory::getAuditor();

        $this->assertSame($firstInstance, $secondInstance);
        $this->assertInstanceOf(FSBaseAuditor::class, $baseReader);
    }

    /**
     * @throws FSInstanceTypeException
     */
    public function testCreateFSBaseActionInstance()
    {
        $firstInstance = FSFactory::create(FSBaseAction::class);
        $secondInstance = FSFactory::create(FSBaseAction::class);
        $baseReader = FSFactory::getAction();

        $this->assertSame($firstInstance, $secondInstance);
        $this->assertInstanceOf(FSBaseAction::class, $baseReader);
    }

    /**
     * @throws FSInstanceTypeException
     */
    public function testCreateFSAdvancedInstance()
    {
        $firstInstance = FSFactory::create(FSAdvanced::class);
        $secondInstance = FSFactory::create(FSAdvanced::class);
        $baseReader = FSFactory::getAdvanced();

        $this->assertSame($firstInstance, $secondInstance);
        $this->assertInstanceOf(FSAdvanced::class, $baseReader);
    }
}
