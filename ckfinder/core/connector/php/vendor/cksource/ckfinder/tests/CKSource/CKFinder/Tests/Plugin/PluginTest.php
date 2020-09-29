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
use CKSource\CKFinder\Error;
use CKSource\CKFinder\Exception\InvalidPluginException;
use CKSource\CKFinder\Tests\Fixtures;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class PluginTest extends \PHPUnit\Framework\TestCase
{
    protected $configArray;
    protected $requestMock;

    protected function setUp()
    {
        $this->configArray = Fixtures\Config::getArray();
        $this->requestMock = new Request();
    }

    public function testNonexistingPluginUsageInConfig()
    {
        $this->expectException(InvalidPluginException::class);
        $this->expectExceptionMessage('CKFinder plugin "Dummy" not found');
        $this->expectExceptionCode(Error::INVALID_PLUGIN);

        $this->configArray['plugins'] = ['Dummy'];
        $ckfinder = new CKFinder($this->configArray);
        $ckfinder->boot($this->requestMock);

        static::assertTrue($ckfinder instanceof CKFinder);
    }

    public function testExistingPluginUsageInConfig()
    {
        require_once __DIR__.'/DummyPlugin.php';

        $this->configArray['plugins'] = ['Dummy'];
        $ckfinder = new CKFinder($this->configArray);
        $ckfinder->boot($this->requestMock);

        static::assertTrue($ckfinder instanceof CKFinder);
    }

    public function testPluginDefaultConfig()
    {
        require_once __DIR__.'/DummyPlugin.php';

        $this->configArray['plugins'] = ['Dummy'];
        $app = new CKFinder($this->configArray);
        $app->boot($this->requestMock);

        $expected = [
            'foo' => 'bar',
            'baz' => 100,
            'arrayOption' => [
                'first' => 1,
                'second' => 2,
            ],
        ];

        static::assertSame($expected, $app['config']->get('Dummy'));
    }

    public function testPluginExtendingConfig()
    {
        require_once __DIR__.'/DummyPlugin.php';

        $this->configArray['plugins'] = ['Dummy'];
        $this->configArray['Dummy'] = [
            'foo' => 'changed',
            'arrayOption' => [
                'first' => 'one',
            ],
        ];
        $app = new CKFinder($this->configArray);
        $app->boot($this->requestMock);

        $expected = [
            'foo' => 'changed',
            'baz' => 100,
            'arrayOption' => [
                'first' => 'one',
                'second' => 2,
            ],
        ];

        static::assertSame($expected, $app['config']->get('Dummy'));
    }

    public function testPluginEventSubscribing()
    {
        require_once __DIR__.'/DummyPlugin.php';

        $this->configArray['plugins'] = ['Dummy'];
        $app = new CKFinder($this->configArray);
        $app->boot(Request::createFromGlobals());

        $dummyPlugin = $app->getPlugin('Dummy');
        static::assertSame('default', $dummyPlugin->getState());

        // Dispatch event.foo and check plugin state again
        $app['dispatcher']->dispatch(new GenericEvent(), 'event.foo');
        static::assertSame('changed', $dummyPlugin->getState());
    }

    public function testPluginAutoloadingFromPluginsDirectory()
    {
        $this->configArray['plugins'] = ['Test'];
        $app = new CKFinder($this->configArray);
        $app->boot($this->requestMock);

        static::assertTrue($app instanceof CKFinder);
    }

    public function testPluginAutoloadingFromPath()
    {
        $this->configArray['plugins'] = [
            'Test',
            [
                'name' => 'Dummy',
                'path' => __DIR__.'/DummyPlugin.php',
            ],
        ];
        $app = new CKFinder($this->configArray);
        $app->boot($this->requestMock);

        static::assertTrue($app instanceof CKFinder);
    }
}
