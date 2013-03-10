<?php

namespace rcrowe\Campfire\Tests\Transport;

use rcrowe\Campfire as Campfire;
use Guzzle\Http\Client as Http;
use Guzzle\Http\Exception\BadResponseException as HttpException;
use Mockery as m;

class Mocked extends \PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $this->config = new Campfire\Config(array(
            'subdomain' => 'vexpress',
            'room'      => 'Notifications',
            'key'       => '123',
        ));
    }

    public function tearDown()
    {
        m::close();
    }

    public function testUnauthorizedException()
    {
        $ex = new Campfire\Exceptions\Transport\UnauthorizedException;
        $this->assertEquals(get_parent_class($ex), 'rcrowe\Campfire\Exceptions\TransportException');
    }

    public function testBadConfig()
    {
        try
        {
            $transport = new Campfire\Transport();
        }
        catch(\Exception $ex)
        {
            $this->assertTrue(strpos($ex->getMessage(), 'must be an instance of rcrowe\Campfire\Config') !== FALSE);
        }

        try
        {
            $transport = new Campfire\Transport(array());
        }
        catch(\Exception $ex)
        {
            $this->assertTrue(strpos($ex->getMessage(), 'must be an instance of rcrowe\Campfire\Config') !== FALSE);
        }
    }

    public function testNoHttpPassed()
    {
        $transport = new Campfire\Transport($this->config);

        $refObj  = new \ReflectionObject($transport);
        $refProp = $refObj->getProperty('http');
        $refProp->setAccessible(true);
        $http    = $refProp->getValue($transport);

        $this->assertEquals(get_class($http), 'Guzzle\Http\Client');
        $this->assertEquals($http->getBaseUrl(), 'https://vexpress.campfirenow.com/room/Notifications');
    }

    public function testCustomHttpAsParam()
    {
        $url = 'https://vexpress.example.com/unit/test';

        $transport = new Campfire\Transport($this->config, new Http($url));

        $refObj  = new \ReflectionObject($transport);
        $refProp = $refObj->getProperty('http');
        $refProp->setAccessible(true);
        $http    = $refProp->getValue($transport);

        $this->assertEquals(get_class($http), 'Guzzle\Http\Client');
        $this->assertEquals($http->getBaseUrl(), $url);
    }

    public function testMockHttpAsParam()
    {
        $url  = 'https://rcrowe.example.com/unit/test';
        $http = m::mock(new Http($url));

        $transport = new Campfire\Transport($this->config, $http);

        $refObj  = new \ReflectionObject($transport);
        $refProp = $refObj->getProperty('http');
        $refProp->setAccessible(true);
        $http    = $refProp->getValue($transport);

        $this->assertEquals(get_parent_class($http), 'Mockery\Mock');
        $this->assertEquals($http->getBaseUrl(), $url);
    }

    public function testBadHttpParam()
    {
        try
        {
            $transport = new Campfire\Transport($this->config, new \stdClass);
            $this->assertFalse(true);
        }
        catch(\InvalidArgumentException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Incorrect HTTP object type passed in');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testGetRequestDefault()
    {
        $transport = new Campfire\Transport($this->config);

        $refMethod = new \ReflectionMethod('rcrowe\Campfire\Transport', 'getRequest');
        $refMethod->setAccessible(true);
        $request   = $refMethod->invoke($transport, 'This is the message that will go in the POST payload');

        $this->assertEquals(get_class($request), 'Guzzle\Http\Message\EntityEnclosingRequest');

        $subdomain = $this->config->get('subdomain');
        $room      = $this->config->get('room');
        $auth_key  = $this->config->get('key');

        // Check request URL
        $this->assertEquals($request->getUrl(), 'https://'.$subdomain.'.campfirenow.com/room/'.$room.'/speak.json');

        // Check POST payload
        $data = array(
            'message' => array(
                'type' => 'TextMessage',
                'body' => 'This is the message that will go in the POST payload',
            )
        );

        $this->assertEquals($request->getBody(), json_encode($data));

        // Check headers
        foreach ($request->getHeaders() as $key => $val)
        {
            switch ($key)
            {
                case 'Host':
                            $this->assertEquals($val[0], $subdomain.'.campfirenow.com');
                            break;

                case 'Accept':
                case 'Content-type':
                            $this->assertEquals($val[0], 'application/json');
                            break;

                case 'User-Agent':
                            $this->assertEquals($val[0], 'rcrowe/Campfire');
                            break;

                case 'Authorization':
                            $this->assertEquals($val[0], 'Basic '.base64_encode($auth_key.':x'));
                            break;
            }
        }
    }

    public function testSuccessfulMessage()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config->get('subdomain').'.campfirenow.com/room/'.$this->config->get('room'));
        $http = m::mock($http);

        $headers = array(
            'Accept'       => 'application/json',
            'Content-type' => 'application/json',
            'User-Agent'   => 'rcrowe/Campfire',
        );

        $data = json_encode(array(
            'message' => array(
                'type' => 'TextMessage',
                'body' => 'Hello world',
            )
        ));

        $http->shouldReceive('post')->with('speak.json', $headers, $data)->andReturn($request);

        $transport = new Campfire\Transport($this->config, $http);
        $success   = $transport->send('Hello world');

        $this->assertTrue($success);
    }

    // Testing that if Guzzle didn't throw an exception
    // we catch that we didn't get a 201 status code, we throw an unknown exception
    public function testUnknownExceptionMessage()
    {
        $response = m::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('getStatusCode')->andReturn(400);

        $request = m::mock('Guzzle\Http\Message\Request');
        $request->shouldReceive('send')->andReturn($response);

        $http = new Http('https://'.$this->config->get('subdomain').'.campfirenow.com/room/'.$this->config->get('room'));
        $http = m::mock($http);

        $headers = array(
            'Accept'       => 'application/json',
            'Content-type' => 'application/json',
            'User-Agent'   => 'rcrowe/Campfire',
        );

        $data = json_encode(array(
            'message' => array(
                'type' => 'TextMessage',
                'body' => 'Hello world',
            )
        ));

        $http->shouldReceive('post')->with('speak.json', $headers, $data)->andReturn($request);

        $transport = new Campfire\Transport($this->config, $http);

        // We aren't throwing an exception
        // We are returning a status code from the web service that does not equal 201 (created)
        try
        {
            $transport->send('Hello world');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\Transport\UnknownException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown error occurred');
            $this->assertEquals(get_parent_class($ex), 'rcrowe\Campfire\Exceptions\TransportException');

            // Make sure that we can get the response object out of the exception
            $this->assertTrue(is_object($ex->getResponse()));
            $this->assertEquals($ex->getResponse()->getStatusCode(), 400);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testUnauthorisedResponse()
    {
        // Request
        $request = m::mock('request');

        $exception = new HttpException;

        $status_code = m::mock('status');
        $status_code->shouldReceive('getStatusCode')->andReturn(401);

        $refObj  = new \ReflectionObject($exception);
        $refProp = $refObj->getProperty('response');
        $refProp->setAccessible(true);
        $refProp->setValue($exception, $status_code);

        $request->shouldReceive('send')->andThrow( $exception );

        // Http obejct
        $http = new Http('https://'.$this->config->get('subdomain').'.campfirenow.com/room/'.$this->config->get('room'));
        $http = m::mock($http);

        $headers = array(
            'Accept'       => 'application/json',
            'Content-type' => 'application/json',
            'User-Agent'   => 'rcrowe/Campfire',
        );

        $data = json_encode(array(
            'message' => array(
                'type' => 'TextMessage',
                'body' => 'Hello world',
            )
        ));

        $http->shouldReceive('post')->with('speak.json', $headers, $data)->andReturn($request);

        $transport = new Campfire\Transport($this->config, $http);

        // Make sure we catch unauthorised
        try
        {
            $transport->send('Hello world');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\Transport\UnauthorizedException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unauthorised: API incorrect');

            // Make sure we can get the request back that generated the error
            $this->assertTrue(is_object($ex->getResponse()));
            $this->assertEquals($ex->getResponse()->getStatusCode(), 401);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testUnknownResponse()
    {
        // Request
        $request = m::mock('request');

        $exception = new HttpException;

        $status_code = m::mock('status');
        $status_code->shouldReceive('getStatusCode')->andReturn(500);

        $refObj  = new \ReflectionObject($exception);
        $refProp = $refObj->getProperty('response');
        $refProp->setAccessible(true);
        $refProp->setValue($exception, $status_code);

        $request->shouldReceive('send')->andThrow( $exception );

        // Http obejct
        $http = new Http('https://'.$this->config->get('subdomain').'.campfirenow.com/room/'.$this->config->get('room'));
        $http = m::mock($http);

        $headers = array(
            'Accept'       => 'application/json',
            'Content-type' => 'application/json',
            'User-Agent'   => 'rcrowe/Campfire',
        );

        $data = json_encode(array(
            'message' => array(
                'type' => 'TextMessage',
                'body' => 'Hello world',
            )
        ));

        $http->shouldReceive('post')->with('speak.json', $headers, $data)->andReturn($request);

        $transport = new Campfire\Transport($this->config, $http);

        // Make sure we catch unauthorised
        try
        {
            $transport->send('Hello world');
            $this->assertFalse(true);
        }
        catch(Campfire\Exceptions\TransportException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown HTTP error occurred');

            // Make sure we can get the request back that generated the error
            $this->assertTrue(is_object($ex->getResponse()));
            $this->assertEquals($ex->getResponse()->getStatusCode(), 500);
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }
}