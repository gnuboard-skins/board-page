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

namespace CKSource\CKFinder\Tests\Backend;

use CKSource\CKFinder\Backend\BackendFactory;
use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Tests\Fixtures;
use League\Flysystem\Adapter\NullAdapter;

/**
 * @internal
 * @coversNothing
 */
final class BackendFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $app;
    protected $configArray;
    /**
     * @var BackendFactory
     */
    protected $backendFactory;

    protected function setUp()
    {
        $this->configArray = Fixtures\Config::getArray();

        $this->configArray['backends'][] = [
            'name' => 'customBackend',
            'adapter' => 'customAdapter',
        ];

        $this->app = new CKFinder($this->configArray);
        $this->backendFactory = new BackendFactory($this->app);
    }

    public function testNewAdapterRegistration()
    {
        $this->backendFactory->registerAdapter('customAdapter', function ($backendConfig) {
            return $this->backendFactory->createBackend($backendConfig, new NullAdapter());
        });

        $backend = $this->backendFactory->getBackend('customBackend');

        static::assertInstanceOf('CKSource\CKFinder\Backend\Backend', $backend);
        static::assertInstanceOf('\League\Flysystem\Adapter\NullAdapter', $backend->getBaseAdapter());
    }

    public function testInstantationCallbackNotReturningBackend()
    {
        $this->expectException(\CKSource\CKFinder\Exception\CKFinderException::class);
        $this->expectExceptionMessage('The instantiation callback for adapter "customAdapter" didn\'t return a valid Backend object');

        $this->backendFactory->registerAdapter('customAdapter', function ($backendConfig) {
            $o = new \stdClass();
            $o->foo = 'bar';

            return $o;
        });

        $backend = $this->backendFactory->getBackend('customBackend');
    }

    public function testGettingNonexistingBackend()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Backend nonexistingBackend not found. Please check configuration file');

        $backend = $this->backendFactory->getBackend('nonexistingBackend');
    }
}
