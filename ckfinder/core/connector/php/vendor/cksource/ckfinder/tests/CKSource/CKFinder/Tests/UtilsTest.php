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

namespace CKSource\CKFinder\Tests;

use CKSource\CKFinder\Filesystem\Path;
use CKSource\CKFinder\Utils;

/**
 * @internal
 * @coversNothing
 */
final class UtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testReturnBytes()
    {
        static::assertSame(524288, Utils::returnBytes('512K'));
        static::assertSame(8388608, Utils::returnBytes('8M'));
        static::assertSame(4294967296, Utils::returnBytes('4G'));
    }

    public function testNormalizePath()
    {
        static::assertSame('/', Path::normalize(null));
        static::assertSame('/', Path::normalize(''));
        static::assertSame('/', Path::normalize('/'));
        static::assertSame('/foo/bar/', Path::normalize('/foo/bar'));
        static::assertSame('/foo/bar/', Path::normalize('foo/bar/'));
        static::assertSame('/foo/bar/', Path::normalize('/foo/bar/'));
        static::assertSame('/foo/bar baz/', Path::normalize('foo/bar baz'));
    }

    public function testCombinePaths()
    {
        static::assertSame('/foo/bar/baz', Path::combine('/foo/bar', '/baz'));
        static::assertSame('foo/bar/b az', Path::combine('foo/bar/', 'b az'));
        static::assertSame('/foo/bar/baz', Path::combine('/foo/bar/', '/baz'));
        static::assertSame('/foo/bar/baz', Path::combine('/foo', '/foo/bar/baz'));
        static::assertSame('/foo/bar/', Path::combine('/foo/bar', ''));
        static::assertSame('/foo/bar/', Path::combine('/foo/bar', null));
        static::assertSame('/foo/bar', Path::combine('', '/foo/bar'));
        static::assertSame('/foo/bar', Path::combine(null, '/foo/bar'));
        static::assertSame('/foo/bar/baz', Path::combine('/foo/', '/bar/', '/baz'));
        static::assertSame('/foo/bar/baz', Path::combine('/foo/', '/bar', '/baz'));
        static::assertSame('/foo/bar/baz', Path::combine('/foo', 'bar/', 'baz'));
    }

    public function testEncodeDecodeUrl()
    {
        $samples = [
            "żółta84 user#files !*'(1)",
            "żółta84/user#files/!*'\\(1)",
        ];

        foreach ($samples as $sample) {
            $encoded = Utils::encodeURLComponent($sample);
            static::assertSame($sample, Utils::decodeURLComponent($encoded));
        }
    }

    public function testEncodeDecodeUrlParts()
    {
        $samples = [
            "żółta84/user#files/!*'\\(1)" => "%C5%BC%C3%B3%C5%82ta84/user%23files/!*'%5C(1)",
            "żółta84/user#files/!*'\\(1)/foo/" => "%C5%BC%C3%B3%C5%82ta84/user%23files/!*'%5C(1)/foo/",
            "/żółta84/user#files/!*'\\(1)/" => "/%C5%BC%C3%B3%C5%82ta84/user%23files/!*'%5C(1)/",
            "/żółta84/user#files/!*'\\(1)" => "/%C5%BC%C3%B3%C5%82ta84/user%23files/!*'%5C(1)",
        ];

        foreach ($samples as $sample => $expected) {
            static::assertSame($expected, Utils::encodeURLParts($sample));
            static::assertSame($sample, Utils::decodeURLParts(Utils::encodeURLParts($sample)));
        }
    }
}
