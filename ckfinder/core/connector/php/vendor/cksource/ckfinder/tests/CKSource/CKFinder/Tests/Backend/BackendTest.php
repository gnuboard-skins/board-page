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

use CKSource\CKFinder\Backend\Backend;
use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Tests\Fixtures;
use League\Flysystem\Adapter;

/**
 * @internal
 * @coversNothing
 */
final class BackendTest extends \PHPUnit\Framework\TestCase
{
    protected $configArray;

    protected function setUp()
    {
        $this->configArray = Fixtures\Config::getArray();
    }

    public function testIsHiddenFile()
    {
        $this->configArray['hideFiles'] = [
            '.*',
            '*.log',
            'image?.jpg',
            'secret*',
        ];

        $app = new CKFinder($this->configArray);

        $backend = new Backend([], $app, new Adapter\Local(__DIR__));

        static::assertTrue($backend->isHiddenFile('.htaccess'));
        static::assertTrue($backend->isHiddenFile('apache.log'));
        static::assertTrue($backend->isHiddenFile('apache.old.log'));
        static::assertFalse($backend->isHiddenFile('apachelog'));
        static::assertFalse($backend->isHiddenFile('file.jpg'));
        static::assertTrue($backend->isHiddenFile('imageX.jpg'));
        static::assertTrue($backend->isHiddenFile('image1.jpg'));
        static::assertFalse($backend->isHiddenFile('image111.jpg'));
        static::assertTrue($backend->isHiddenFile('secret_stuff.pdf'));
        static::assertTrue($backend->isHiddenFile('secret'));
    }

    public function testIsHiddenFolder()
    {
        $this->configArray['hideFolders'] = [
            '.*',
            'CSV',
            'images.??.??.2014',
            'secret*',
            '*logs*',
            'hide?',
        ];

        $app = new CKFinder($this->configArray);

        $backend = new Backend([], $app, new Adapter\Local(__DIR__));

        static::assertTrue($backend->isHiddenFolder('.git'));
        static::assertTrue($backend->isHiddenFolder('logs'));
        static::assertTrue($backend->isHiddenFolder('app.logs'));
        static::assertTrue($backend->isHiddenFolder('app.logs.2014'));
        static::assertTrue($backend->isHiddenFolder('logs_2014'));
        static::assertTrue($backend->isHiddenFolder('secret'));
        static::assertTrue($backend->isHiddenFolder('secret_folder'));
        static::assertFalse($backend->isHiddenFolder('visible'));
        static::assertTrue($backend->isHiddenFolder('images.16.07.2014'));
        static::assertTrue($backend->isHiddenFolder('hideX'));
    }

    public function testIsHiddenPath()
    {
        $this->configArray['hideFolders'] = [
            '.*',
            'CSV',
            'images.??.??.2014',
            'secret*',
            '*logs*',
            'hide?',
        ];

        $app = new CKFinder($this->configArray);

        $backend = new Backend([], $app, new Adapter\Local(__DIR__));

        static::assertTrue($backend->isHiddenPath('/a/.git'));
        static::assertTrue($backend->isHiddenPath('/files/logs'));
        static::assertTrue($backend->isHiddenPath('/a/b/app.logs/z'));
        static::assertTrue($backend->isHiddenPath('/a/z/app.logs.2014'));
        static::assertTrue($backend->isHiddenPath('files/logs_2014'));
        static::assertTrue($backend->isHiddenPath('a/secret/b/c'));
        static::assertTrue($backend->isHiddenPath('files/secret_folder/a'));
        static::assertFalse($backend->isHiddenPath('files/visible'));
        static::assertTrue($backend->isHiddenPath('files/dir1/images.16.07.2014/abc'));
        static::assertTrue($backend->isHiddenPath('files/dir1/hideX'));
    }

    public function testAccessingBackendName()
    {
        $this->configArray['backends'][0]['root'] = __DIR__;

        $app = new CKFinder($this->configArray);

        $backend = new Backend(['name' => 'testing'], $app, new Adapter\Local(__DIR__));

        static::assertSame('testing', $backend->getName());

        $defaultBackend = $app->getBackendFactory()->getBackend('default');

        static::assertSame('default', $defaultBackend->getName());
    }
}
