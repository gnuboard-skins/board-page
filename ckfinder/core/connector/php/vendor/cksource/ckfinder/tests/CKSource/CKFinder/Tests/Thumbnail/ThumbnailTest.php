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

namespace CKSource\CKFinder\Tests\Thumbnail;

use CKSource\CKFinder\Thumbnail\Thumbnail;

/**
 * @internal
 * @coversNothing
 */
final class ThumbnailTest extends \PHPUnit\Framework\TestCase
{
    public function testThumbnailScalingAndPath()
    {
        $thumbnailRepoMock = $this->getMockBuilder('CKSource\CKFinder\Thumbnail\ThumbnailRepository')->disableOriginalConstructor()->getMock();
        $resourceTypeMock = $this->getMockBuilder('CKSource\CKFinder\ResourceType\ResourceType')->disableOriginalConstructor()->getMock();

        $allowedSizes = [
            ['width' => '150', 'height' => '150', 'quality' => 80],
            ['width' => '300', 'height' => '300', 'quality' => 80],
            ['width' => '500', 'height' => '500', 'quality' => 80],
        ];

        $thumbnailRepoMock->method('getAllowedSizes')->willReturn($allowedSizes);
        $resourceTypeMock->method('getName')->willReturn('Images');

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo', 20, 20);
        static::assertSame('foo__150x150', $thumbnail->getFileName());
        static::assertSame('Images/foo/foo__150x150', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo bar.jpg', 20, 20);
        static::assertSame('foo bar__150x150.jpg', $thumbnail->getFileName());
        static::assertSame('Images/foo bar.jpg/foo bar__150x150.jpg', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo.bar', 230, 230);
        static::assertSame('foo__300x300.bar', $thumbnail->getFileName());
        static::assertSame('Images/foo.bar/foo__300x300.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo.bar', 230, 300);
        static::assertSame('foo__300x300.bar', $thumbnail->getFileName());
        static::assertSame('Images/foo.bar/foo__300x300.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo.bar', 230, 301);
        static::assertSame('foo__500x500.bar', $thumbnail->getFileName());
        static::assertSame('Images/foo.bar/foo__500x500.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo.bar', 301, 300);
        static::assertSame('foo__500x500.bar', $thumbnail->getFileName());
        static::assertSame('Images/foo.bar/foo__500x500.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'foo.bar', 9000, 9000);
        static::assertSame('foo__500x500.bar', $thumbnail->getFileName());
        static::assertSame('Images/foo.bar/foo__500x500.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', 'baz.foo.bar', 9000, 9000);
        static::assertSame('baz.foo__500x500.bar', $thumbnail->getFileName());
        static::assertSame('Images/baz.foo.bar/baz.foo__500x500.bar', $thumbnail->getFilePath());

        $thumbnail = new Thumbnail($thumbnailRepoMock, $resourceTypeMock, '/', '繁體中文字.PNG', 301, 300);
        static::assertSame('繁體中文字__500x500.PNG', $thumbnail->getFileName());
        static::assertSame('Images/繁體中文字.PNG/繁體中文字__500x500.PNG', $thumbnail->getFilePath());
    }
}
