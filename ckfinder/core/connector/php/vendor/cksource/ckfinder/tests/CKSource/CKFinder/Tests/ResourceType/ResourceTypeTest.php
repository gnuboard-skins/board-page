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

namespace CKSource\CKFinder\Tests\ResourceType;

use CKSource\CKFinder\ResourceType\ResourceType;

/**
 * @internal
 * @coversNothing
 */
final class ResourceTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testAllowedExtensions()
    {
        $backendMock = $this->getMockBuilder('CKSource\CKFinder\Backend\Backend')->disableOriginalConstructor()->getMock();
        $thumbnailRepoMock = $this->getMockBuilder('CKSource\CKFinder\Thumbnail\ThumbnailRepository')->disableOriginalConstructor()->getMock();
        $resizedImageRepoMock = $this->getMockBuilder('CKSource\CKFinder\ResizedImage\ResizedImageRepository')->disableOriginalConstructor()->getMock();

        $resourceTypeConfig = [
            'allowedExtensions' => ['jpg', 'bmp', 'png', 'gif'],
            'deniedExtensions' => ['jpg', 'exe'],
        ];

        $resourceType = new ResourceType('test', $resourceTypeConfig, $backendMock, $thumbnailRepoMock, $resizedImageRepoMock);

        static::assertTrue($resourceType->isAllowedExtension('bmp'));
        static::assertFalse($resourceType->isAllowedExtension('jpg'));
        static::assertFalse($resourceType->isAllowedExtension('exe'));

        $resourceTypeConfig = [
            'allowedExtensions' => [],
            'deniedExtensions' => [],
        ];

        $resourceType = new ResourceType('test', $resourceTypeConfig, $backendMock, $thumbnailRepoMock, $resizedImageRepoMock);

        static::assertTrue($resourceType->isAllowedExtension('bmp'));
        static::assertTrue($resourceType->isAllowedExtension('obj'));
    }
}
