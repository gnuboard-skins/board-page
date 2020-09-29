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

use CKSource\CKFinder\CommandResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class CommandResolverTest extends \PHPUnit\Framework\TestCase
{
    protected $commandResolver;

    protected function setUp()
    {
        $ckfinder = $this->getMockBuilder('CKSource\CKFinder\CKFinder')->disableOriginalConstructor()->getMock();

        $this->commandResolver = new CommandResolver($ckfinder);
        $this->commandResolver->setCommandsNamespace('CKSource\CKFinder\Tests\Mocks\Command\\');
    }

    public function testInvalidCommand()
    {
        $this->expectException(\CKSource\CKFinder\Exception\InvalidCommandException::class);
        $this->expectExceptionCode(\CKSource\CKFinder\Error::INVALID_COMMAND);

        $request = Request::create('', 'GET', ['command' => 'nonExisting']);

        $this->commandResolver->getController($request);
    }
}
