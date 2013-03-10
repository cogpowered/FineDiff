<?php

namespace rcrowe\Campfire\Tests\Usage;

use rcrowe\Campfire as Campfire;
use rcrowe\Campfire\Facade as Facade;
use Guzzle\Http\Client as Http;
use Guzzle\Http\Exception\BadResponseException as HttpException;
use Mockery as m;

class Mocked extends \PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $this->config = array(
            'subdomain' => 'vexpress',
            'room'      => '10000',
            'key'       => 'abc123',
        );
    }

    public function testInstance()
    {
        $campfire = new Campfire($this->config);

        $refObj  = new \ReflectionObject($campfire);

        // config
        $refProp = $refObj->getProperty('config');
        $refProp->setAccessible(true);
        $config = $refProp->getValue($campfire);

        // queue
        $refProp = $refObj->getProperty('queue');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($campfire);

        // transport
        $refProp = $refObj->getProperty('transport');
        $refProp->setAccessible(true);
        $transport = $refProp->getValue($campfire);

        $this->assertEquals(get_class($config), 'rcrowe\Campfire\Config');
        $this->assertEquals(get_class($queue), 'rcrowe\Campfire\Queue');
        $this->assertEquals(get_class($transport), 'rcrowe\Campfire\Transport');
    }

    public function testSendNullMessage()
    {
        $campfire = new Campfire($this->config);

        try
        {
            $campfire->send(NULL);
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\TransportException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Queue is empty');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }

        try
        {
            $campfire->send(true);
            $this->assertFalse(true);
        }
        catch(\InvalidArgumentException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Can only add a string to the queue');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testSingleSuccessfulMessage()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $http->shouldReceive('post')->withAnyArgs()->andReturn($request);

        $campfire = new Campfire($this->config, $http);
        $this->assertTrue($campfire->send('Hello world'));
    }

    public function testMultipleSuccessfulMessages()
    {
        // times(4) didn't work with the mock object
        // I'll just do it manually for now
        $times_called = 0;

        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturnUsing(function() use(&$times_called) {
            $times_called++;
            return 201;
        });

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $http->shouldReceive('post')->withAnyArgs()->andReturn($request);

        $campfire = new Campfire($this->config, $http);

        $campfire->queue('Test 1');
        $campfire->queue('Test 2');
        $campfire->queue('Test 3');

        $this->assertTrue($campfire->send('Hello world'));
        $this->assertEquals($times_called, 4);
    }

    public function testStaticInitInstance()
    {
        $instance = Facade::init($this->config);

        $this->assertEquals(get_class($instance), 'rcrowe\Campfire');

        Facade::destroy();

        $this->assertEquals(Facade::instance(), NULL);
    }

    public function testStaticWithoutInit()
    {
        try
        {
            Facade::msg('test');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\FacadeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Facade::init(...) must be called first');
            $this->assertEquals(Facade::instance(), NULL);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }

        try
        {
            Facade::queue('test');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\FacadeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Facade::init(...) must be called first');
            $this->assertEquals(Facade::instance(), NULL);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }

        try
        {
            Facade::send();
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\FacadeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Facade::init(...) must be called first');
            $this->assertEquals(Facade::instance(), NULL);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }

        try
        {
            Facade::send('test');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\FacadeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Facade::init(...) must be called first');
            $this->assertEquals(Facade::instance(), NULL);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testStaticMsgNoParam()
    {
        Facade::init($this->config);

        try
        {
            Facade::msg();
            $this->assertFalse(true);
        }
        catch(\InvalidArgumentException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'No message was passed in as the first argument');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testStaticInstanceFunction()
    {
        Facade::destroy();

        $this->assertEquals(Facade::instance(), NULL);

        Facade::init($this->config);

        $this->assertEquals(get_class(Facade::instance()), 'rcrowe\Campfire');

        try
        {
            $this->assertFalse( Facade::instance()->thisShouldExist );
            $this->assertFalse(true);
        }
        catch(\Exception $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Undefined property: rcrowe\Campfire::$thisShouldExist');
        }

        $campfire = new Campfire($this->config);
        $campfire->thisShouldExist = TRUE;

        Facade::instance($campfire);

        $this->assertTrue( Facade::instance()->thisShouldExist );
    }

    public function testStaticMsgCallingLib()
    {
        $campfire = m::mock(new Campfire($this->config));

        $campfire->shouldReceive('send')->andReturnUsing(function() {
            throw new \Exception('Campfire::send called');
        });

        Facade::instance($campfire);

        try
        {
            Facade::msg('test');
            throw new \Exception('ERROR');
        }
        catch(\Exception $ex)
        {
            if ($ex->getMessage() === 'Campfire::send called')
            {
                $this->assertTrue(true);
            }
            else
            {
                $this->assertFalse(false);
            }
        }
    }

    public function testStaticQueueCallingLib()
    {
        $campfire = m::mock(new Campfire($this->config));

        $campfire->shouldReceive('queue')->andReturnUsing(function() {
            throw new \Exception('Campfire::queue called');
        });

        Facade::instance($campfire);

        try
        {
            Facade::queue('test');
            throw new \Exception('ERROR');
        }
        catch(\Exception $ex)
        {
            if ($ex->getMessage() === 'Campfire::queue called')
            {
                $this->assertTrue(true);
            }
            else
            {
                $this->assertFalse(true);
            }
        }
    }

    public function testStaticSendCallingLib()
    {
        // No arguments
        $campfire = m::mock(new Campfire($this->config));

        $campfire->shouldReceive('send')->with(NULL)->andReturnUsing(function() {
            throw new \Exception('Campfire::send called');
        });

        Facade::instance($campfire);

        try
        {
            Facade::send();
            throw new \Exception('ERROR');
        }
        catch(\Mockery\Exception $ex)
        {
            $this->assertFalse(true);
        }
        catch(\Exception $ex)
        {
            if ($ex->getMessage() === 'Campfire::send called')
            {
                $this->assertTrue(true);
            }
            else
            {
                $this->assertFalse(true);
            }
        }

        // Arguments
        $campfire = m::mock(new Campfire($this->config));

        $campfire->shouldReceive('send')->with('test')->andReturnUsing(function($args) {
            throw new \Exception('Campfire::send called');
        });

        Facade::instance($campfire);

        try
        {
            Facade::send('test');
            throw new \Exception('ERROR');
        }
        catch(\Mockery\Exception $ex)
        {
            $this->assertFalse(true);
        }
        catch(\Exception $ex)
        {
            if ($ex->getMessage() === 'Campfire::send called')
            {
                $this->assertTrue(true);
            }
            else
            {
                $this->assertFalse(true);
            }
        }
    }


    // queue works fine with mocked transport - post gets called 3 times
    // queue() x 3 then send($msg) - post gets called 4 times

    public function testStaticSingleMessageSuccess()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $self    = $this;
        $checked = FALSE;

        $http->shouldReceive('post')->withAnyArgs()->andReturnUsing(function($path, $headers, $json) use(&$self, &$checked, $request) {

            $self->assertEquals($path, 'speak.json');
            $self->assertEquals($headers['Accept'], 'application/json');
            $self->assertEquals($headers['Content-type'], 'application/json');
            $self->assertEquals($headers['User-Agent'], 'rcrowe/Campfire');
            $self->assertEquals($json, '{"message":{"type":"TextMessage","body":"This is an example message that I might send"}}');

            $checked = TRUE;

            return $request;
        });


        Facade::destroy();
        Facade::init($this->config, $http);

        Facade::msg('This is an example message that I might send');

        if (!$checked)
        {
            $this->assertFalse(true);
        }
    }

    public function testStaticQueuedMessagesSuccess()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $self  = $this;
        $times = 0;

        $http->shouldReceive('post')->withAnyArgs()->andReturnUsing(function($path, $headers, $json) use(&$self, &$times, $request) {

            $times++;

            $self->assertEquals($path, 'speak.json');
            $self->assertEquals($headers['Accept'], 'application/json');
            $self->assertEquals($headers['Content-type'], 'application/json');
            $self->assertEquals($headers['User-Agent'], 'rcrowe/Campfire');
            $self->assertEquals($json, '{"message":{"type":"TextMessage","body":"Test '.$times.'"}}');

            return $request;
        });


        Facade::destroy();
        Facade::init($this->config, $http);

        Facade::queue('Test 1');
        Facade::queue('Test 2');
        Facade::queue('Test 3');

        Facade::send();

        if ($times !== 3)
        {
            $this->assertFalse(true);
        }
    }

    public function testStaticQueuedMessagesSuccessWithSendMsg()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $self  = $this;
        $times = 0;

        $http->shouldReceive('post')->withAnyArgs()->andReturnUsing(function($path, $headers, $json) use(&$self, &$times, $request) {

            $times++;

            $self->assertEquals($path, 'speak.json');
            $self->assertEquals($headers['Accept'], 'application/json');
            $self->assertEquals($headers['Content-type'], 'application/json');
            $self->assertEquals($headers['User-Agent'], 'rcrowe/Campfire');
            $self->assertEquals($json, '{"message":{"type":"TextMessage","body":"Test '.$times.'"}}');

            return $request;
        });

        Facade::destroy();
        Facade::init($this->config, $http);

        Facade::queue('Test 1');
        Facade::queue('Test 2');
        Facade::queue('Test 3');

        Facade::send('Test 4');

        if ($times !== 4)
        {
            $this->assertFalse(true);
        }
    }

    public function testStaticEmptyQueueAfterSend()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config['subdomain'].'.campfirenow.com/room/'.$this->config['room']);
        $http = m::mock($http);

        $http->shouldReceive('post')->withAnyArgs()->andReturn($request);

        Facade::destroy();
        Facade::init($this->config, $http);

        Facade::queue('Test 1');
        Facade::queue('Test 2');
        Facade::queue('Test 3');

        Facade::send();

        // Make sure that the queue is now empty
        $campfire = Facade::instance();

        $refObj  = new \ReflectionObject($campfire);
        $refProp = $refObj->getProperty('queue');
        $refProp->setAccessible(true);
        $queue   = $refProp->getValue($campfire);

        $this->assertEquals(count($queue), 0);

        // Make sure we now get the empty queue exception
        try
        {
            Facade::send();
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\TransportException $ex)
        {
            $this->assertTrue(true);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }
}