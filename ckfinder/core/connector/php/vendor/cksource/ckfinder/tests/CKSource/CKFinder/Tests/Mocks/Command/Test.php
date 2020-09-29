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

namespace CKSource\CKFinder\Tests\Mocks\Command;

use CKSource\CKFinder\Command\CommandAbstract;

/**
 * @internal
 * @coversNothing
 */
final class Test extends CommandAbstract
{
    public function execute()
    {
        return \Symfony\Component\HttpFoundation\Response::create(__METHOD__);
    }
}
