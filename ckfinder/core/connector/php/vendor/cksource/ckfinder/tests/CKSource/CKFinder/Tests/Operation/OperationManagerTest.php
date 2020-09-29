<?php

/*
 * CKFinder
 * ========
 * https://ckeditor.com/ckfinder/
 * Copyright (c) 2007-2020, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

namespace CKSource\CKFinder\Tests\Config;

use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Event\CKFinderEvent;
use CKSource\CKFinder\Operation\OperationManager;
use CKSource\CKFinder\Tests\Fixtures;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 * @coversNothing
 */
final class OperationManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var OperationManager
     */
    protected $operation;

    /**
     * @var CKFinder
     */
    protected $app;

    /**
     * @var vfsStreamDirectory
     */
    protected $tmpDir;

    protected function setUp()
    {
        $this->tmpDir = vfsStream::setup('temp');
        $config = Fixtures\Config::getArray();
        $config['tempDirectory'] = vfsStream::url('temp');
        $config['backends'][0]['root'] = vfsStream::url('temp');
        $this->app = new CKFinder($config);
        $this->operation = $this->app['operation'];
    }

    public function testStartOperation()
    {
        $operationId = 'foobarfoobarfoob';
        $this->setRequestQuery(['operationId' => $operationId]);
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
        static::assertTrue($this->operation->start());
        static::assertDirectoryExists($this->getOperationDir($operationId));
    }

    public function testStartOperationWithInvalidId()
    {
        $operationId = 'foobarfoobarfoobX';
        $this->setRequestQuery(['operationId' => $operationId]);
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
        static::assertFalse($this->operation->start());
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
    }

    public function testSettingOperationStatus()
    {
        $operationId = 'foobarfoobarfoob';
        $this->setRequestQuery(['operationId' => $operationId]);
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
        static::assertTrue($this->operation->start());
        static::assertDirectoryExists($this->getOperationDir($operationId));

        $testStatus = ['foo' => 'bar'];
        $this->operation->updateStatus($testStatus);
        static::assertFileExists($this->getOperationDir($operationId, 'status'));
        static::assertSame($testStatus, $this->operation->getStatus($operationId));
        static::assertSame($testStatus, unserialize(file_get_contents($this->getOperationDir($operationId, 'status'))));
    }

    public function testAbortingOperation()
    {
        $operationId = 'foobarfoobarfoob';
        $this->setRequestQuery(['operationId' => $operationId]);
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
        static::assertTrue($this->operation->start());
        static::assertDirectoryExists($this->getOperationDir($operationId));

        $testStatus = ['foo' => 'bar'];
        $this->operation->updateStatus($testStatus);
        static::assertFileExists($this->getOperationDir($operationId, 'status'));
        static::assertSame($testStatus, $this->operation->getStatus($operationId));
        static::assertSame($testStatus, unserialize(file_get_contents($this->getOperationDir($operationId, 'status'))));

        static::assertFalse($this->operation->isAborted());
        static::assertTrue($this->operation->abort($operationId));
        static::assertFileExists($this->getOperationDir($operationId, 'abort'));
        static::assertTrue($this->operation->isAborted());
    }

    public function testIfDestructorCleansTempFiles()
    {
        $operationId = 'foobarfoobarfoob';
        $this->testAbortingOperation();

        static::assertFileExists($this->getOperationDir($operationId));

        $this->operation->__destruct();

        static::assertFileNotExists($this->getOperationDir($operationId));
        static::assertDirectoryNotExists($this->getOperationDir($operationId));
        static::assertFileNotExists($this->getOperationDir($operationId, 'status'));
        static::assertFileNotExists($this->getOperationDir($operationId, 'abort'));
    }

    public function testIfInfoAboutAbortedOperationIsAppendedToTheResponse()
    {
        $operationId = 'foobarfoobarfoob';

        // Remove this listener as it closes all buffers before sending the response.
        // This causes complaints from PHPUnit and marks the test as risky.
        $this->app['dispatcher']->removeListener(KernelEvents::RESPONSE, [$this->app, 'afterCommand']);

        $this->app->on(CKFinderEvent::BEFORE_COMMAND_INIT, function () use ($operationId) {
            $this->operation->start();
            $this->operation->abort($operationId);
            $this->operation->addInfoToResponse();
        });

        $response = $this->app->handle(new Request(['command' => 'Init', 'operationId' => $operationId]));

        $responseJsonArr = json_decode($response->getContent(), true);
        static::assertInternalType('array', $responseJsonArr);
        static::assertArrayHasKey('aborted', $responseJsonArr);
        static::assertTrue($responseJsonArr['aborted']);
    }

    protected function setRequestQuery(array $query)
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request($query));
        $this->app['request_stack'] = $requestStack;
    }

    protected function getOperationDir($operationId, $file = null)
    {
        return vfsStream::url('temp/ckf-operation-'.$operationId.($file ? '/'.$file : ''));
    }
}
