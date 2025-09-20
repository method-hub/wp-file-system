<?php

namespace Metapraxis\WPFileSystem\Tests\Unit;

use Metapraxis\WPFileSystem\Adapters\FSAdvanced;
use Metapraxis\WPFileSystem\Adapters\FSBaseAction;
use Metapraxis\WPFileSystem\Adapters\FSBaseAuditor;
use Metapraxis\WPFileSystem\Adapters\FSBaseManager;
use Metapraxis\WPFileSystem\Adapters\FSBaseReader;
use Metapraxis\WPFileSystem\Decorator\FSDecorator;
use Metapraxis\WPFileSystem\Guarded\GuardedFSAdvanced;
use Metapraxis\WPFileSystem\Guarded\GuardedFSBaseAction;
use Metapraxis\WPFileSystem\Guarded\GuardedFSBaseAuditor;
use Metapraxis\WPFileSystem\Guarded\GuardedFSBaseManager;
use Metapraxis\WPFileSystem\Guarded\GuardedFSBaseReader;
use Metapraxis\WPFileSystem\Hooks\HookableFSAction;
use Metapraxis\WPFileSystem\Hooks\HookableFSAdvanced;
use Metapraxis\WPFileSystem\Hooks\HookableFSAuditor;
use Metapraxis\WPFileSystem\Hooks\HookableFSManager;
use Metapraxis\WPFileSystem\Hooks\HookableFSReader;
use ReflectionObject;
use WP_UnitTestCase;

class FSDecoratorTest extends WP_UnitTestCase
{
    public function testCreateStandardInstance()
    {
        $baseReader = FSDecorator::create(FSBaseReader::class, false, false);
        $baseManager = FSDecorator::create(FSBaseManager::class, false, false);
        $baseAuditor = FSDecorator::create(FSBaseAuditor::class, false, false);
        $baseAction = FSDecorator::create(FSBaseAction::class, false, false);
        $advanced = FSDecorator::create(FSAdvanced::class, false, false);

        $this->assertInstanceOf(FSBaseReader::class, $baseReader);
        $this->assertInstanceOf(FSBaseManager::class, $baseManager);
        $this->assertInstanceOf(FSBaseAuditor::class, $baseAuditor);
        $this->assertInstanceOf(FSBaseAction::class, $baseAction);
        $this->assertInstanceOf(FSAdvanced::class, $advanced);
    }

    public function testCreateHookableInstance()
    {
        $hookableReader = FSDecorator::create(FSBaseReader::class, false, true);
        $hookableManager = FSDecorator::create(FSBaseManager::class, false, true);
        $hookableAuditor = FSDecorator::create(FSBaseAuditor::class, false, true);
        $hookableAction = FSDecorator::create(FSBaseAction::class, false, true);
        $hookableAdvanced = FSDecorator::create(FSAdvanced::class, false, true);

        $this->assertInstanceOf(HookableFSReader::class, $hookableReader);
        $this->assertInstanceOf(HookableFSManager::class, $hookableManager);
        $this->assertInstanceOf(HookableFSAuditor::class, $hookableAuditor);
        $this->assertInstanceOf(HookableFSAction::class, $hookableAction);
        $this->assertInstanceOf(HookableFSAdvanced::class, $hookableAdvanced);
    }

    public function testCreateGuardedInstance()
    {
        $guardedReader = FSDecorator::create(FSBaseReader::class, true, false);
        $guardedManager = FSDecorator::create(FSBaseManager::class, true, false);
        $guardedAuditor = FSDecorator::create(FSBaseAuditor::class, true, false);
        $guardedAction = FSDecorator::create(FSBaseAction::class, true, false);
        $guardedAdvanced = FSDecorator::create(FSAdvanced::class, true, false);

        $this->assertInstanceOf(GuardedFSBaseReader::class, $guardedReader);
        $this->assertInstanceOf(GuardedFSBaseManager::class, $guardedManager);
        $this->assertInstanceOf(GuardedFSBaseAuditor::class, $guardedAuditor);
        $this->assertInstanceOf(GuardedFSBaseAction::class, $guardedAction);
        $this->assertInstanceOf(GuardedFSAdvanced::class, $guardedAdvanced);
    }

    public function testCreateInstance()
    {
        $reader = FSDecorator::create(FSBaseReader::class, true, true);
        $manager = FSDecorator::create(FSBaseManager::class, true, true);
        $auditor = FSDecorator::create(FSBaseAuditor::class, true, true);
        $action = FSDecorator::create(FSBaseAction::class, true, true);
        $advanced = FSDecorator::create(FSAdvanced::class, true, true);

        $this->assertInstanceOf(GuardedFSBaseReader::class, $reader);
        $this->assertInstanceOf(GuardedFSBaseManager::class, $manager);
        $this->assertInstanceOf(GuardedFSBaseAuditor::class, $auditor);
        $this->assertInstanceOf(GuardedFSBaseAction::class, $action);
        $this->assertInstanceOf(GuardedFSAdvanced::class, $advanced);

        $innerReader = $this->extractWrappedFs($reader);
        $innerManager = $this->extractWrappedFs($manager);
        $innerAuditor = $this->extractWrappedFs($auditor);
        $innerAction = $this->extractWrappedFs($action);
        $innerAdvanced = $this->extractWrappedFs($advanced);

        $this->assertInstanceOf(HookableFSReader::class, $innerReader);
        $this->assertInstanceOf(HookableFSManager::class, $innerManager);
        $this->assertInstanceOf(HookableFSAuditor::class, $innerAuditor);
        $this->assertInstanceOf(HookableFSAction::class, $innerAction);
        $this->assertInstanceOf(HookableFSAdvanced::class, $innerAdvanced);

        $baseReader = $this->extractWrappedFs($innerReader);
        $baseManager = $this->extractWrappedFs($innerManager);
        $baseAuditor = $this->extractWrappedFs($innerAuditor);
        $baseAction = $this->extractWrappedFs($innerAction);
        $baseAdvanced = $this->extractWrappedFs($innerAdvanced);

        $this->assertInstanceOf(FSBaseReader::class, $baseReader);
        $this->assertInstanceOf(FSBaseManager::class, $baseManager);
        $this->assertInstanceOf(FSBaseAuditor::class, $baseAuditor);
        $this->assertInstanceOf(FSBaseAction::class, $baseAction);
        $this->assertInstanceOf(FSAdvanced::class, $baseAdvanced);

        $this->assertNotInstanceOf(HookableFSReader::class, $reader);
        $this->assertNotInstanceOf(HookableFSManager::class, $manager);
        $this->assertNotInstanceOf(HookableFSAuditor::class, $auditor);
        $this->assertNotInstanceOf(HookableFSAction::class, $action);
        $this->assertNotInstanceOf(HookableFSAdvanced::class, $advanced);
    }

    private function extractWrappedFs(object $wrapper): object
    {
        $ref = new ReflectionObject($wrapper);

        while (!$ref->hasProperty('fs')) {
            $ref = $ref->getParentClass();
            if ($ref === false) {
                $this->fail('Property $fs not found in wrapper chain');
            }
        }

        $prop = $ref->getProperty('fs');
        $prop->setAccessible(true);

        $inner = $prop->getValue($wrapper);
        $this->assertIsObject($inner, 'Wrapped $fs must be an object');

        return $inner;
    }
}
