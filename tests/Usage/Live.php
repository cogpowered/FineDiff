<?php

namespace rcrowe\Campfire\Tests\Transport;

use rcrowe\Campfire as Campfire;
// use rcrowe\Campfire\Facade as Campfire;

class Live extends \PHPUnit_Framework_TestCase
{
    protected $campfire;
    protected $config;
    protected $skip = TRUE;

    public function setUp()
    {
        $subdomain = (isset($_SERVER['CAMPFIRE_SUBDOMAIN'])) ? $_SERVER['CAMPFIRE_SUBDOMAIN'] : NULL;
        $room      = (isset($_SERVER['CAMPFIRE_ROOM'])) ? $_SERVER['CAMPFIRE_ROOM'] : NULL;
        $key       = (isset($_SERVER['CAMPFIRE_KEY'])) ? $_SERVER['CAMPFIRE_KEY'] : NULL;

        if (!$subdomain OR !$room OR !$key)
        {
            // $this->markTestSkipped('Live Campfire details not set');
            // Not marking as skipped, as normal practice is to not run these anyway
            return;
        }

        $this->config = array(
            'subdomain' => $subdomain,
            'room'      => $room,
            'key'       => $key,
        );

        $this->campfire = new Campfire($this->config);
        $this->skip     = FALSE;
    }

    public function testOneMessage()
    {
        if ($this->skip) return;

        $this->campfire->send('PHPUnit: Testing - testOneMessage');
    }

    public function testQueuedMessages()
    {
        if ($this->skip) return;

        $this->campfire->queue('PHPUnit: Testing - testQueuedMessages 1');
        $this->campfire->queue('PHPUnit: Testing - testQueuedMessages 2');
        $this->campfire->queue('PHPUnit: Testing - testQueuedMessages 3');

        $this->campfire->send();
    }

    public function testStaticOneMessage()
    {
        if ($this->skip) return;

        Campfire\Facade::init($this->config);
        Campfire\Facade::msg('PHPUnit: Testing static - testStaticOneMessage');
    }

    public function testStaticQueuedMessages()
    {
        if ($this->skip) return;

        Campfire\Facade::queue('PHPUnit: Testing static - testStaticQueuedMessages 1');
        Campfire\Facade::queue('PHPUnit: Testing static - testStaticQueuedMessages 2');
        Campfire\Facade::queue('PHPUnit: Testing static - testStaticQueuedMessages 3');

        Campfire\Facade::send();
    }
}