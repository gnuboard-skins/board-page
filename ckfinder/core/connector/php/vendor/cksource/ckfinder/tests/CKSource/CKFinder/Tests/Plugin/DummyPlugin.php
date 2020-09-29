<?php

namespace CKSource\CKFinder\Plugin\Dummy;

use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Plugin\PluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Dummy implements PluginInterface, EventSubscriberInterface
{
    public function setContainer(CKFinder $app)
    {

    }

    public function getJavaScript()
    {

    }

    public function getDefaultConfig()
    {
        return array(
            'foo' => 'bar',
            'baz' => 100,
            'arrayOption' => array(
                'first'  => 1,
                'second' => 2
            )
        );
    }

    protected $state = 'default';

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
        return array('event.foo' => 'changeState');
    }
}