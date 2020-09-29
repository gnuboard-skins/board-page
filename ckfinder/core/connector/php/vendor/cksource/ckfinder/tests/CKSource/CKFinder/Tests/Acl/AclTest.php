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

use CKSource\CKFinder\Acl\Acl;
use CKSource\CKFinder\Acl\Permission;
use CKSource\CKFinder\Tests\Fixtures;
use CKSource\CKFinder\Tests\Mocks\Acl\User\MockRoleContext;

/**
 * @internal
 * @coversNothing
 */
final class AclTest extends \PHPUnit\Framework\TestCase
{
    const ROLE = 'test_role';
    const ALL_PERMISSIONS = 255;
    protected $acl;
    protected $roleContext;

    protected function setUp()
    {
        $this->roleContext = new MockRoleContext(static::ROLE);

        $this->acl = new Acl($this->roleContext);

        $config = Fixtures\Config::getAclNodes();

        $this->acl->setRules($config);
    }

    public function checkMaskForImagesRoot()
    {
        $this->checkMask('Images', '/', static::ALL_PERMISSIONS);
    }

    public function checkPermissions($resourceType, $folderPath, $expectedPermissions = [])
    {
        foreach ($expectedPermissions as $permission => $expectedResult) {
            static::assertSame($expectedResult, $this->acl->isAllowed($resourceType, $folderPath, $permission));
        }
    }

    public function testCheckPermissionForImagesSubfolderWithoutUploadForAllRoles()
    {
        $this->roleContext->setRole(null); // no role set

        $resourceType = 'Images';

        $folderPath = 'images_subfolder_no_upload/blah';

        $this->checkMask($resourceType, $folderPath, static::ALL_PERMISSIONS & ~(Permission::FILE_CREATE | Permission::FOLDER_DELETE));

        $this->checkPermissions($resourceType, $folderPath, [
            Permission::FOLDER_VIEW => true,
            Permission::FOLDER_CREATE => true,
            Permission::FOLDER_RENAME => true,
            Permission::FOLDER_DELETE => false,

            Permission::FILE_VIEW => true,
            Permission::FILE_CREATE => false,
            Permission::FILE_RENAME => true,
            Permission::FILE_DELETE => true,
        ]);
    }

    public function testCheckPermissionForImagesSubfolderWithoutUploadForTestRole()
    {
        $this->checkMask('Images', 'images_subfolder_no_upload', static::ALL_PERMISSIONS);
    }

    public function testCheckPermissionForImagesSubSubfolderWithUploadForAllRoles()
    {
        $this->roleContext->setRole(null); // no role set

        $resourceType = 'Images';

        $folderPath = 'images_subfolder_no_upload/sub_allow_upload';

        $this->checkMask($resourceType, $folderPath, static::ALL_PERMISSIONS & ~(Permission::FOLDER_DELETE));

        $this->checkPermissions($resourceType, $folderPath, [
            Permission::FOLDER_VIEW => true,
            Permission::FOLDER_CREATE => true,
            Permission::FOLDER_RENAME => true,
            Permission::FOLDER_DELETE => false,

            Permission::FILE_VIEW => true,
            Permission::FILE_CREATE => true,
            Permission::FILE_RENAME => true,
            Permission::FILE_DELETE => true,
        ]);
    }

    public function testCheckPermissionForImagesSubSubfolderWithUploadForTestRole()
    {
        $resourceType = 'Images';

        $folderPath = 'images_subfolder_no_upload/sub_allow_upload/for_test_role';

        $this->checkPermissions($resourceType, $folderPath, [
            Permission::FOLDER_VIEW => true,
            Permission::FOLDER_CREATE => true,
            Permission::FOLDER_RENAME => true,
            Permission::FOLDER_DELETE => true,

            Permission::FILE_VIEW => false,
            Permission::FILE_CREATE => false,
            Permission::FILE_RENAME => false,
            Permission::FILE_DELETE => false,
        ]);
    }

    protected function checkMask($resourceType, $folderPath, $expectedMask)
    {
        $mask = $this->acl->getComputedMask($resourceType, $folderPath);

        static::assertSame($expectedMask, $mask);
    }
}
