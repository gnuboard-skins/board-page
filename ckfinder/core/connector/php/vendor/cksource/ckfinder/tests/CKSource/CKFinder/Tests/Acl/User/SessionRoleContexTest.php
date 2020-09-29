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

namespace CKSource\CKFinder\Tests\Acl\User;

use CKSource\CKFinder\Acl\User\SessionRoleContext;

/**
 * @internal
 * @coversNothing
 */
final class SessionRoleContexTest extends \PHPUnit\Framework\TestCase
{
    const SESSION_ROLE_FIELD = 'CKFinder_UserRole';
    const SESSION_ROLE_VALUE = 'dummy_user_role';

    protected $sessionRoleContext;

    protected function setUp()
    {
        $this->sessionRoleContext = new SessionRoleContext(self::SESSION_ROLE_FIELD);
    }

    public function testRoleReturned()
    {
        global $_SESSION;

        $_SESSION = [
            self::SESSION_ROLE_FIELD => self::SESSION_ROLE_VALUE,
        ];

        static::assertSame(self::SESSION_ROLE_VALUE, $this->sessionRoleContext->getRole());
    }
}
