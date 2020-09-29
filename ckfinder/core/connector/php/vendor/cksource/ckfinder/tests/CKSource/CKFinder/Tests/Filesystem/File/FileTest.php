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

namespace CKSource\CKFinder\Tests\Filesystem\Path;

use CKSource\CKFinder\Filesystem\File\File;

/**
 * @internal
 * @coversNothing
 */
final class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testFilenameIsValidName()
    {
        static::assertTrue(File::isValidName('foo.bar'));
        static::assertTrue(File::isValidName('foo bar.baz'));
        static::assertTrue(File::isValidName('zażó łć.bar'));
        static::assertTrue(File::isValidName("foo\x7ebar"));

        static::assertFalse(File::isValidName("\t\r"));
        static::assertFalse(File::isValidName('foobar.'));
        static::assertFalse(File::isValidName('/foo/../bar'));
        static::assertFalse(File::isValidName('/foo/*../bar'));
        static::assertFalse(File::isValidName('/foo/*/bar'));
        static::assertFalse(File::isValidName('/fo:o/bar'));
        static::assertFalse(File::isValidName('/foo/bar?/baz'));
        static::assertFalse(File::isValidName('/foo/*/../../bar'));
        static::assertFalse(File::isValidName('\foo/bar'));
        static::assertFalse(File::isValidName('foo\bar'));
        static::assertFalse(File::isValidName('foobar\\'));
        static::assertFalse(File::isValidName('foo*bar'));
        static::assertFalse(File::isValidName("foo\tbar"));
        static::assertFalse(File::isValidName("foo\fbar"));
        static::assertFalse(File::isValidName("foo\nbar"));
        static::assertFalse(File::isValidName("foo\x7fbar"));
    }
}
