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

// ============================ PHP Error Reporting ====================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/debugging.html

// Production
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// ============================ General Settings =======================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config = [];

// ============================ Enable PHP Connector HERE ==============================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_authentication

$config['authentication'] = function () {
    return true;
};

// ============================ License Key ============================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_licenseKey

$config['licenseName'] = '';
$config['licenseKey'] = '';

// ============================ CKFinder Internal Directory ============================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_privateDir

$config['privateDir'] = [
    'backend' => 'default',
    'tags' => '.ckfinder/tags',
    'logs' => '.ckfinder/logs',
    'cache' => '.ckfinder/cache',
    'thumbs' => '.ckfinder/cache/thumbs',
];

// ============================ Images and Thumbnails ==================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_images

$config['images'] = [
    'maxWidth' => 1600,
    'maxHeight' => 1200,
    'quality' => 80,
    'sizes' => [
        'small' => ['width' => 480, 'height' => 320, 'quality' => 80],
        'medium' => ['width' => 600, 'height' => 480, 'quality' => 80],
        'large' => ['width' => 800, 'height' => 600, 'quality' => 80],
    ],
];

// =================================== Backends ========================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

$config['backends'][] = [
    'name' => 'default',
    'adapter' => 'local',
    'baseUrl' => '/ckfinder/userfiles/',
    //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
    'chmodFiles' => 0777,
    'chmodFolders' => 0755,
    'filesystemEncoding' => 'UTF-8',
];

// ================================ Resource Types =====================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

$config['defaultResourceTypes'] = '';

$config['resourceTypes'][] = [
    'name' => 'Files', // Single quotes not allowed.
    'directory' => 'files',
    'maxSize' => 0,
    'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
    'deniedExtensions' => '',
    'backend' => 'default',
];

$config['resourceTypes'][] = [
    'name' => 'Images',
    'directory' => 'images',
    'maxSize' => 0,
    'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
    'deniedExtensions' => '',
    'backend' => 'default',
];

// ================================ Access Control =====================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

$config['roleSessionVar'] = 'CKFinder_UserRole';

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
$config['accessControl'] = [
    [
        'role' => '*',
        'resourceType' => '*',
        'folder' => '/',

        'FOLDER_VIEW' => true,
        'FOLDER_CREATE' => true,
        'FOLDER_RENAME' => true,
        'FOLDER_DELETE' => true,

        'FILE_VIEW' => true,
        'FILE_CREATE' => true,
        'FILE_RENAME' => true,
        'FILE_DELETE' => true,
    ],
    [
        'role' => '*',
        'resourceType' => 'Images',
        'folder' => '/',

        'FOLDER_DELETE' => false,
    ],
    [
        'role' => '*',
        'resourceType' => 'Images',
        'folder' => '/images_subfolder_no_upload',

        'FILE_CREATE' => false,
    ],
    [
        'role' => 'test_role',
        'resourceType' => 'Images',
        'folder' => '/images_subfolder_no_upload',

        'FOLDER_DELETE' => true,

        'FILE_CREATE' => true,
    ],
    [
        'role' => '*',
        'resourceType' => 'Images',
        'folder' => '/images_subfolder_no_upload/sub_allow_upload',

        'FILE_CREATE' => true,
    ],
    [
        'role' => 'test_role',
        'resourceType' => 'Images',
        'folder' => '/images_subfolder_no_upload/sub_allow_upload/for_test_role',

        'FOLDER_VIEW' => true,
        'FOLDER_CREATE' => true,
        'FOLDER_RENAME' => true,
        'FOLDER_DELETE' => true,

        'FILE_VIEW' => false,
        'FILE_CREATE' => false,
        'FILE_RENAME' => false,
        'FILE_DELETE' => false,
    ],
];

// ================================ Other Settings =====================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config['overwriteOnUpload'] = false;
$config['checkDoubleExtension'] = true;
$config['disallowUnsafeCharacters'] = false;
$config['secureImageUploads'] = true;
$config['checkSizeAfterScaling'] = true;
$config['htmlExtensions'] = ['html', 'htm', 'xml', 'js'];
$config['hideFolders'] = ['.*', 'CVS', '__thumbs'];
$config['hideFiles'] = ['.*'];
$config['forceAscii'] = false;
$config['xSendfile'] = false;

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
$config['debug'] = false;

// ==================================== Plugins ========================================
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

$config['pluginsDirectory'] = __DIR__.'/plugins';
$config['plugins'] = [];

// ==================================== End of Configuration ===========================

// Config must be returned - do not change it.
return $config;
