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

namespace CKSource\CKFinder\Tests\Fixtures;

use CKSource\CKFinder\Response\Node\ArrayNode;
use CKSource\CKFinder\Response\Node\Node;

class ResponseNode
{
    public static function getInitCommandNodes()
    {
        return [
            Node::create('connectorInfo', [
                'enabled' => true,
                's' => 'localhost',
                'c' => 'NNSEELIVOE',
                'thumbsEnabled' => true,
                'thumbsUrl' => '/userfiles/_thumbs/',
                'thumbsDirectAccess' => false,
                'thumbsWidth' => 330,
                'thumbsHeight' => 330,
                'imgWidth' => 1600,
                'imgHeight' => 1600,
                'uploadMaxSize' => 536870912,
                'uploadCheckImages' => false,
                'plugins' => 'imageeditor,imageresize,imagepreview',
                'someSpecialChars' => 'ĄŻŚĆĘÓŁążśęćół©',
            ]),
            ArrayNode::create('resourceTypes')
                ->addChildNode(
                Node::create('resourceType')->setValues([
                    'name' => 'Files',
                    'url' => '/userfiles/files/',
                    'allowedExtensions' => 'webm,7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
                    'deniedExtensions' => null,
                    'hash' => '0e34a82662b18572',
                    'hasChildren' => false,
                    'acl' => 255,
                    'maxSize' => 209715200,
                ])
            )
                ->addChildNode(
                Node::create('resourceType')->setValues([
                    'name' => 'Images',
                    'url' => '/userfiles/images/',
                    'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
                    'deniedExtensions' => null,
                    'hash' => '6a6980c2d9392463',
                    'hasChildren' => false,
                    'acl' => 255,
                    'maxSize' => 1048576,
                ])
            )
                ->addChildNode(
                Node::create('resourceType')->setValues([
                    'name' => 'Flash',
                    'url' => '/userfiles/flash/',
                    'allowedExtensions' => 'swf,flv',
                    'deniedExtensions' => null,
                    'hash' => '72d3c5bf58715473',
                    'hasChildren' => false,
                    'acl' => 255,
                    'maxSize' => 1048576,
                ])
            ),
            Node::create('pluginsInfo')
                ->addChildNode(
                Node::create('imageresize', [
                    'smallThumb' => '90x90',
                    'mediumThumb' => '120x120',
                    'largeThumb' => '180x180',
                ])
            ),
        ];
    }

    public static function getTestedNodeTree()
    {
        $rootNode = Node::create('connector')
            ->addChildNode(Node::create('error', ['number' => 0]))
        ;

        foreach (static::getInitCommandNodes() as $node) {
            $rootNode->addChildNode($node);
        }

        return $rootNode;
    }

    public static function getExpectedJson()
    {
        return file_get_contents(__DIR__.'/init_test.json');
    }

    public static function getExpectedXml()
    {
        return file_get_contents(__DIR__.'/init_test.xml');
    }

    public static function getTestedNestedArrayNode()
    {
        return Node::create('root', ['rootvalue' => true])
            ->addChildNode(
                ArrayNode::create('level1')
                    ->addChildNode(
                        ArrayNode::create('level2')
                            ->addChildNode(Node::create('level3')->setValues(['level23value' => false]))
                            ->addChildNode(Node::create('level3')->setValues(['level23value' => true]))
                            ->addChildNode(Node::create('level3')->setValues(['level23value' => 1337]))
                    )
                    ->addChildNode(
                        ArrayNode::create('level2')
                            ->addChildNode(Node::create('level3')->setValues(['level223value' => false]))
                            ->addChildNode(Node::create('level3')->setValues(['level223value' => true]))
                            ->addChildNode(Node::create('level3')->setValues(['level223value' => 1337]))
                    )
            )
            ;
    }
}
