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

namespace CKSource\CKFinder\Tests\Cache;

use CKSource\CKFinder\Cache\Adapter\BackendAdapter;
use CKSource\CKFinder\Cache\CacheManager;
use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Tests\Fixtures\Config;

/**
 * @internal
 * @coversNothing
 */
final class CacheTest extends \PHPUnit\Framework\TestCase
{
    protected $backend;

    protected function setUp()
    {
        $config = Config::getArray();

        $config['backends'][0] = [
            'name' => 'default',
            'adapter' => 'local',
            'baseUrl' => '/ckfinder/userfiles',
            'root' => __DIR__.'/cache',
            'chmodFiles' => 0777,
            'chmodFolders' => 0777,
            'filesystemEncoding' => 'UTF-8',
        ];

        $app = new CKFinder($config);
        $this->backend = $app['backend_factory']->getBackend('default');
    }

    public function testInstantiation()
    {
        $cache = new CacheManager(new BackendAdapter($this->backend, 'cache_sub'));
        static::assertTrue($cache instanceof CacheManager);
    }

    public function testSetingGettingDeletingFromCache()
    {
        $cache = new CacheManager(new BackendAdapter($this->backend, 'cache_sub'));

        $cache->set('foo', ['bar']);
        static::assertSame(['bar'], $cache->get('foo'));

        $imgData = [
            'size' => [
                'width' => 800,
                'height' => 600,
            ],
        ];

        $path = 'images/sub1/big.jpg';

        $cache->set($path, $imgData);
        static::assertSame($imgData, $cache->get($path));

        static::assertNull($cache->get('non-existing'));

        $cache->delete($path);
        static::assertNull($cache->get($path));

        $cache->set('foo', $imgData);
        static::assertSame($imgData, $cache->get('foo'));

        $cache->delete('foo');
        static::assertNull($cache->get('foo'));
    }
}
