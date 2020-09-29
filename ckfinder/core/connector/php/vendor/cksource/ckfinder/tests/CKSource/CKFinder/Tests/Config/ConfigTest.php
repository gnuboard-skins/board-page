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

namespace CKSource\CKFinder\Tests\Config;

use CKSource\CKFinder\Config;
use CKSource\CKFinder\Tests\Fixtures;

/**
 * @internal
 * @coversNothing
 */
final class ConfigTest extends \PHPUnit\Framework\TestCase
{
    protected $configArray;

    protected function setUp()
    {
        $this->configArray = Fixtures\Config::getArray();
    }

    public function testConfigUndefinedBackend()
    {
        $this->expectException(\CKSource\CKFinder\Exception\InvalidConfigException::class);
        $this->expectExceptionMessage('Backend \'undefined_backend\' is not defined: [resourceTypes][0]');
        $this->expectExceptionCode(\CKSource\CKFinder\Error::INVALID_CONFIG);

        $this->configArray['resourceTypes'][0]['backend'] = 'undefined_backend';
        new Config($this->configArray);
    }

    public function testConfigAuthenticationNotCallable()
    {
        $this->expectException(\CKSource\CKFinder\Exception\InvalidConfigException::class);
        $this->expectExceptionMessage('CKFinder Authentication config field must be a PHP callable');
        $this->expectExceptionCode(\CKSource\CKFinder\Error::INVALID_CONFIG);

        $this->configArray['authentication'] = true;
        new Config($this->configArray);
    }

    public function testConfigGetResourceTypeNode()
    {
        $config = new Config($this->configArray);

        foreach ($this->configArray['resourceTypes'] as $resourceType) {
            static::assertInternalType('array', $config->getResourceTypeNode($resourceType['name']));
        }
    }

    public function testConfigGetNonExistingResourceTypeNode()
    {
        $this->expectException(\CKSource\CKFinder\Exception\InvalidResourceTypeException::class);
        $this->expectExceptionMessage('Invalid resource type: nonExisting');
        $this->expectExceptionCode(\CKSource\CKFinder\Error::INVALID_TYPE);

        $config = new Config($this->configArray);
        $config->getResourceTypeNode('nonExisting');
    }

    public function testExtendingWithCustomThumbnailsConfig()
    {
        $config = new Config($this->configArray);

        $expectedThumbnailsConfig = [
            'enabled' => true,
            'sizes' => [
                ['width' => '150', 'height' => '150', 'quality' => 80],
                ['width' => '300', 'height' => '300', 'quality' => 80],
                ['width' => '500', 'height' => '500', 'quality' => 80],
            ],
            'bmpSupported' => true,
        ];

        static::assertSame($expectedThumbnailsConfig, $config->get('thumbnails'));

        //////////////////////////////////////////////////////////////////////////
        // Change some values in config array - added in config file

        $this->configArray['thumbnails'] = [
            'enabled' => false,
            'sizes' => [
                ['width' => '1300', 'height' => '1300', 'quality' => 80],
                ['width' => '1500', 'height' => '1500', 'quality' => 80],
            ],
            'bmpSupported' => false,
        ];

        $config = new Config($this->configArray);

        $expectedThumbnailsConfig = [
            'enabled' => false,
            'sizes' => [
                ['width' => '1300', 'height' => '1300', 'quality' => 80],
                ['width' => '1500', 'height' => '1500', 'quality' => 80],
            ],
            'bmpSupported' => false,
        ];

        static::assertSame($expectedThumbnailsConfig, $config->get('thumbnails'));

        //////////////////////////////////////////////////////////////////////////
        // Change some values in config array - added in config file

        $this->configArray['thumbnails'] = [
            'sizes' => [
                ['width' => '1500', 'height' => '1500', 'quality' => 80],
            ],
        ];

        $config = new Config($this->configArray);

        $expectedThumbnailsConfig = [
            'enabled' => true,
            'sizes' => [
                ['width' => '1500', 'height' => '1500', 'quality' => 80],
            ],
            'bmpSupported' => true,
        ];

        static::assertSame($expectedThumbnailsConfig, $config->get('thumbnails'));
    }

    public function testEnablingDebugLoggers()
    {
        // By default all three loggers should be enabled
        $config = new Config([]);
        static::assertSame(['ckfinder_log', 'error_log', 'firephp'], $config->get('debugLoggers'));
        static::assertTrue($config->isDebugLoggerEnabled('ckfinder_log'));
        static::assertTrue($config->isDebugLoggerEnabled('error_log'));
        static::assertTrue($config->isDebugLoggerEnabled('firephp'));

        $this->configArray['debug_loggers'] = ['ckfinder_log', 'firephp'];
        $config = new Config($this->configArray);
        static::assertSame(['ckfinder_log', 'firephp'], $config->get('debugLoggers'));
        static::assertTrue($config->isDebugLoggerEnabled('ckfinder_log'));
        static::assertTrue($config->isDebugLoggerEnabled('firephp'));
        static::assertFalse($config->isDebugLoggerEnabled('error_log'));
        static::assertFalse($config->isDebugLoggerEnabled('foo'));
    }

    public function testDefaultPrivateTempDir()
    {
        $config = new Config([]);

        // By default it should point to sys_temp_dir
        static::assertSame(sys_get_temp_dir(), $config->get('tempDirectory'));
    }

    public function testSettingCustomPrivateTempDir()
    {
        $customTmpDirPath = __DIR__.'/temp';

        $oldUmask = umask(0);
        mkdir($customTmpDirPath, 0777);
        umask($oldUmask);

        $config = new Config([
            'tempDirectory' => $customTmpDirPath,
        ]);

        static::assertSame($customTmpDirPath, $config->get('tempDirectory'));

        rmdir($customTmpDirPath);
    }

    public function testSettingCustomPrivateTempDirToNonexisting()
    {
        $this->expectException(\CKSource\CKFinder\Exception\InvalidConfigException::class);
        $this->expectExceptionMessage('The temporary folder is not writable for CKFinder');
        $this->expectExceptionCode(\CKSource\CKFinder\Error::INVALID_CONFIG);

        $customTmpDirPath = '/does/not/exist';

        new Config([
            'tempDirectory' => $customTmpDirPath,
        ]);
    }

    public function testCacheSettings()
    {
        $config = new Config([
            'cache' => [
                'proxyCommand' => 1000,
            ],
        ]);

        static::assertSame(86400, $config->get('cache.imagePreview')); // Default value should be used
        static::assertSame(1000, $config->get('cache.proxyCommand'));
    }
}
