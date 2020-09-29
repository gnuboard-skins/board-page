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

namespace CKSource\CKFinder\Tests\Filesystem;

use CKSource\CKFinder\Filesystem\Path;

/**
 * @internal
 * @coversNothing
 */
final class PathTest extends \PHPUnit\Framework\TestCase
{
    public function testPathIsValid()
    {
        static::assertTrue(Path::isValid('/foo/bar'));
        static::assertTrue(Path::isValid('/foo/bar baz/'));
        static::assertTrue(Path::isValid('/foo/zażó łć/'));
        static::assertTrue(Path::isValid("foo\x7ebar"));

        static::assertFalse(Path::isValid('/foo/../bar'));
        static::assertFalse(Path::isValid('/foo/*../bar'));
        static::assertFalse(Path::isValid('/foo/*/bar'));
        static::assertFalse(Path::isValid('/fo:o/bar'));
        static::assertFalse(Path::isValid('/foo/bar?/baz'));
        static::assertFalse(Path::isValid('/foo/*/../../bar'));
        static::assertFalse(Path::isValid('\foo/bar'));
        static::assertFalse(Path::isValid('foo\bar'));
        static::assertFalse(Path::isValid('foo*bar'));
        static::assertFalse(Path::isValid('foo:bar'));
        static::assertFalse(Path::isValid("foo\tbar"));
        static::assertFalse(Path::isValid("foo\fbar"));
        static::assertFalse(Path::isValid("foo\nbar"));
        static::assertFalse(Path::isValid("foo\x7fbar"));
    }
}
