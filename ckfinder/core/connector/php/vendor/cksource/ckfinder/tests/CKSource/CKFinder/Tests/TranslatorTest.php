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

use CKSource\CKFinder\Error;
use CKSource\CKFinder\Translator;

/**
 * @internal
 * @coversNothing
 */
final class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultLangMessages()
    {
        $translator = new Translator();

        static::assertSame('Invalid command.', $translator->translateErrorMessage(Error::INVALID_COMMAND));
        static::assertSame('Invalid request.', $translator->translateErrorMessage(Error::INVALID_REQUEST));
    }

    public function testPlLangMessages()
    {
        $translator = new Translator('pl');

        static::assertSame('Nieprawidłowe polecenie (command).', $translator->translateErrorMessage(Error::INVALID_COMMAND));
        static::assertSame('Nieprawidłowe żądanie.', $translator->translateErrorMessage(Error::INVALID_REQUEST));
    }

    public function testReplacingPlaceholdersInMessages()
    {
        $translator = new Translator();

        static::assertSame('It was not possible to complete the request. (Error 1337)', $translator->translateErrorMessage(1337));
        static::assertSame(
            'A file with the same name already exists. The uploaded file was renamed to "foo"bar.txt".',
            $translator->translateErrorMessage(Error::UPLOADED_FILE_RENAMED, ['name' => 'foo"bar.txt'])
        );
        static::assertSame(
            'The uploaded file was renamed to "foo"bar.txt".',
            $translator->translateErrorMessage(Error::UPLOADED_INVALID_NAME_RENAMED, ['name' => 'foo"bar.txt'])
        );
        static::assertSame(
            'Invalid connector plugin: FooBar.',
            $translator->translateErrorMessage(Error::INVALID_PLUGIN, ['pluginName' => 'FooBar'])
        );
    }
}
