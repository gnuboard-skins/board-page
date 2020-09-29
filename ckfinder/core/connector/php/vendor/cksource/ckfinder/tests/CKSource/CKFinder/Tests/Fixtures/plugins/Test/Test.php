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

namespace CKSource\CKFinder\Plugin\Test;

use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Plugin\PluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 * @coversNothing
 */
final class Test implements PluginInterface, EventSubscriberInterface
{
    protected $state = 'default';

    public function setContainer(CKFinder $app)
    {
    }

    public function getJavaScript()
    {
    }

    public function getDefaultConfig()
    {
        return [
            'foo' => 'bar',
            'baz' => 100,
            'arrayOption' => [
                'first' => 1,
                'second' => 2,
            ],
        ];
    }

    public function getState()
    {
        return $this->state;
    }

    public function changeState()
    {
        $this->state = 'changed';
    }

    public static function getSubscribedEvents()
    {
        return ['event.foo' => 'changeState'];
    }
}
