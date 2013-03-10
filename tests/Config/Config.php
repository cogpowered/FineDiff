<?php

namespace rcrowe\Campfire\Tests\Config;

use rcrowe\Campfire as Campfire;

class Config extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array Config options passed to Campfire instance.
     */
    protected $config = array();

    public function setUp()
    {
        $this->config = array(
            'subdomain' => 'vexpress',
            'room'      => 'Notifications',
            'key'       => '123',
        );
    }

    public function testNoConfigInstance()
    {
        // Loop over each config option
        foreach ($this->config as $key => $val)
        {
            try
            {
                // Remove the option we are looking for
                // should force an exception that this config option is missing
                $config = $this->config;
                unset($config[$key]);

                $config = new Campfire\Config($config);
                $this->assertFalse(true, 'Exception not thrown for `'.$key.'`');
            }
            catch(Campfire\Exceptions\ConfigException $ex)
            {
                $this->assertEquals($ex->getMessage(), 'Unable to find config item: '.$key);
            }
            catch(\Exception $ex)
            {
                $this->assertFalse(true, 'Incorrect exception type caught: ' . $ex->getMessage());
            }
        }
    }

    public function testNewInstance()
    {
        $config = new Campfire\Config($this->config);
        $config = $config->getConfig();

        $this->assertEquals(count($config), 3);

        foreach ($this->config as $key => $val)
        {
            $this->assertEquals($config[$key], $this->config[$key]);
        }
    }

    public function testGetItem()
    {
        $config = new Campfire\Config($this->config);

        foreach ($this->config as $key => $val)
        {
            $this->assertEquals($config->get($key), $this->config[$key]);
        }
    }

    public function testExtraConfig()
    {
        $data = array_merge($this->config, array(
            'scheme' => 'http',
        ));

        $config = new Campfire\Config($data);
        $config = $config->getConfig();

        foreach($data as $key => $val)
        {
            $this->assertTrue(isset($config[$key]));
        }
    }
}