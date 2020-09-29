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

namespace CKSource\CKFinder\Tests\Acl;

use CKSource\CKFinder\Acl\Permission;

/**
 * @internal
 * @coversNothing
 */
final class PermissionTest extends \PHPUnit\Framework\TestCase
{
    public function testPermissionByNameWithValidName()
    {
        static::assertSame(Permission::FOLDER_VIEW, Permission::byName('FOLDER_VIEW'));
        static::assertSame(Permission::FOLDER_CREATE, Permission::byName('FOLDER_CREATE'));
        static::assertSame(Permission::FOLDER_RENAME, Permission::byName('FOLDER_RENAME'));
        static::assertSame(Permission::FOLDER_DELETE, Permission::byName('FOLDER_DELETE'));
        static::assertSame(Permission::FILE_VIEW, Permission::byName('FILE_VIEW'));
        static::assertSame(Permission::FILE_CREATE, Permission::byName('FILE_CREATE'));
        static::assertSame(Permission::FILE_RENAME, Permission::byName('FILE_RENAME'));
        static::assertSame(Permission::FILE_DELETE, Permission::byName('FILE_DELETE'));
    }

    public function testPermissionByNameWithInvalidName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The permission "nonExisting" doesn\'t exist');

        Permission::byName('nonExisting');
    }
}
