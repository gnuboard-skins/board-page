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

namespace CKSource\CKFinder\Tests\ResizedImage;

use CKSource\CKFinder\ResizedImage\ResizedImage;

/**
 * @internal
 * @coversNothing
 */
final class ScaledImageTest extends \PHPUnit\Framework\TestCase
{
    public function testExtractingSizeFromImageName()
    {
        static::assertSame(['width' => 800, 'height' => 678], ResizedImage::getSizeFromFilename('foo__800x678.jpg'));
        static::assertSame(['width' => 800, 'height' => 8], ResizedImage::getSizeFromFilename('foo__800x8'));
        static::assertSame(['width' => 800, 'height' => 8], ResizedImage::getSizeFromFilename('foo.pdf__800x8.jpg'));
    }
}
