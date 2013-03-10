<?php

namespace rcrowe\Campfire\Tests\Message;

use rcrowe\Campfire as Campfire;

class ObjTest {
    public function __toString() {
        return 'ObjTest toString check';
    }
}

class Queue extends \PHPUnit_Framework_TestCase
{
    protected $queue;

    public function setUp()
    {
        $this->queue = new Campfire\Queue;
    }

    public function testInstance()
    {
        $this->assertTrue($this->queue instanceof Campfire\Queue);

        // Make sure Campfire object holds the same Queue
        $campfire = new Campfire(array(
            'subdomain' => 'vexpress',
            'room'      => 'Notifications',
            'key'       => '123',
        ));

        $refObj  = new \ReflectionObject($campfire);
        $refProp = $refObj->getProperty('queue');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($campfire);

        $this->assertTrue($queue instanceof Campfire\Queue);
    }

    public function testSingleMessage()
    {
        $index = $this->queue->add('Hello world');

        $this->assertTrue(is_int($index));
        $this->assertEquals(0, $index);

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals(1, count($queue));
        $this->assertTrue(isset($queue[0]));
        $this->assertEquals($queue[0], 'Hello world');
    }

    public function testAddObject()
    {
        $index = $this->queue->add(new ObjTest);

        $this->assertTrue(is_int($index));
        $this->assertEquals(0, $index);

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals(1, count($queue));
        $this->assertTrue(isset($queue[0]));
        $this->assertEquals($queue[0], 'ObjTest toString check');
    }

    public function testQueingMessage()
    {
        $this->queue->add('Hello world 2');
        $this->queue->add('Test 1');
        $this->queue->add(new ObjTest);

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertTrue(isset($queue[0]));
        $this->assertTrue(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));

        $this->assertEquals($queue[0], 'Hello world 2');
        $this->assertEquals($queue[1], 'Test 1');
        $this->assertEquals($queue[2], 'ObjTest toString check');
    }

    public function testQueueAsArray()
    {
        $this->queue[] = 'Hello world 3';
        $this->queue[] = 'Test 2';
        $this->queue[] = new ObjTest;

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertTrue(isset($queue[0]));
        $this->assertTrue(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));

        $this->assertEquals($queue[0], 'Hello world 3');
        $this->assertEquals($queue[1], 'Test 2');
        $this->assertEquals($queue[2], 'ObjTest toString check');
    }

    // Trying to set an item to a non-existing index
    public function testQueueAsArrayWithBadIndex()
    {
        $this->queue[0]  = 'Test 0';
        $this->queue[1]  = 'Test 1';
        $this->queue[4]  = 'Test 4';
        $this->queue[10] = 'Test 10';

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals($queue[0], 'Test 0');
        $this->assertEquals($queue[1], 'Test 1');
        $this->assertEquals($queue[2], 'Test 4');
        $this->assertEquals($queue[3], 'Test 10');
    }

    // Setting at an index just removes it and adds a new message to the end
    public function testQueueAsArrayWithIndex()
    {
        $this->queue[] = 'Test 1';
        $this->queue[] = 'Test 2';
        $this->queue[] = 'Test 3';
        $this->queue[] = 'Test 4';
        $this->queue[] = 'Test 5';
        $this->queue[] = 'Test 6';

        $this->queue[1] = 'There was a big fish';
        $this->queue[3] = 'that sat on a cat';
        $this->queue[4] = 'smoking Mr Dogs finest';

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertTrue(isset($queue[0]));
        $this->assertFalse(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));
        $this->assertFalse(isset($queue[3]));
        $this->assertFalse(isset($queue[4]));
        $this->assertTrue(isset($queue[5]));
        $this->assertTrue(isset($queue[6]));
        $this->assertTrue(isset($queue[7]));
        $this->assertTrue(isset($queue[8]));

        $this->assertEquals($queue[0], 'Test 1');
        $this->assertEquals($queue[2], 'Test 3');
        $this->assertEquals($queue[5], 'Test 6');
        $this->assertEquals($queue[6], 'There was a big fish');
        $this->assertEquals($queue[7], 'that sat on a cat');
        $this->assertEquals($queue[8], 'smoking Mr Dogs finest');
    }

    public function testQueueGetAsArray()
    {
        $this->queue->add('test 1');
        $this->queue->add('1 tset');

        $this->assertEquals($this->queue[0], 'test 1');
        $this->assertEquals($this->queue[1], '1 tset');
    }

    public function testQueueCount()
    {
        $this->queue[] = 'Hello world 3';
        $this->queue[] = 'Test 2';
        $this->queue[] = new ObjTest;

        $this->assertEquals($this->queue->count(), 3);
        $this->assertEquals($this->queue->count(), count($this->queue));
    }

    public function testRemoveQueueItem()
    {
        // Array access doesn't support removal index
        // through normal function calls
        $this->assertFalse(is_int( $this->queue[] = 'test' ));
        $this->assertEquals(count($this->queue), 1);
        unset($this->queue[0]);
        $this->assertEquals(count($this->queue), 0);

        // Adding to the queue with add() will return an index
        // so that you can call remove on it
        $index = $this->queue->add('Hello world');
        $this->assertEquals($index, 0);

        $index = $this->queue->add('Hello world 2');
        $this->assertEquals($index, 1);

        $this->assertEquals(count($this->queue), 2);

        $this->queue->remove(1);
        $this->assertEquals(count($this->queue), 1);

        $this->queue->remove(0);
        $this->assertEquals(count($this->queue), 0);


        // Now lets add & remove to make sure all the correct indexes exist
        $this->queue[] = 'Test 1';
        $index = $this->queue->add('Test 2');

        $this->assertEquals($index, 1);

        $this->queue->remove($index);

        $index = $this->queue->add('Test 3');

        $this->assertEquals($index, 1);

        $this->queue[] = 'Test 4';
        $index = $this->queue->add('Test 5');
        $this->queue[] = 'Test 6';

        $this->assertEquals($index, 3);

        $this->queue->remove($index);

        $this->assertEquals(count($this->queue), 4);
    }

    public function testEmptyQueue()
    {
        $this->queue[] = 'Test 1';
        $this->queue[] = 'Test 2';
        $this->queue[] = 'Test 3';

        $this->assertEquals(count($this->queue), 3);
        $this->queue->remove();
        $this->assertEquals(count($this->queue), 0);
    }

    public function testMessageAddChecks()
    {
        // Doesnt except a none string
        // Or an object that doesnt have __toString()
        $obj = new \stdClass;

        foreach (array(NULL, FALSE, TRUE, $obj, '') as $data)
        {
            try
            {
                $this->queue->add($data);
                $this->assertFalse(true);
            }
            catch(\InvalidArgumentException $ex)
            {
                $this->assertTrue(true);

                if (is_object($data))
                {
                    $this->assertEquals($ex->getMessage(), 'Object can not be converted to a string');
                }
                else
                {
                    $this->assertEquals($ex->getMessage(), 'Can only add a string to the queue');
                }
            }
            catch(\Exception $ex)
            {
                $this->assertFalse(true);
            }
        }

        // same for array access
        foreach (array(NULL, FALSE, TRUE, $obj, '') as $data)
        {
            try
            {
                $this->queue[] = $data;
                $this->assertFalse(true);
            }
            catch(\InvalidArgumentException $ex)
            {
                $this->assertTrue(true);

                if (is_object($data))
                {
                    $this->assertEquals($ex->getMessage(), 'Object can not be converted to a string');
                }
                else
                {
                    $this->assertEquals($ex->getMessage(), 'Can only add a string to the queue');
                }
            }
            catch(\Exception $ex)
            {
                $this->assertFalse(true);
            }
        }
    }

    public function testMessageRemoveChecks()
    {
        $this->queue->add('Test 1');
        $this->queue->add('Test 2');
        $this->queue->add('Test 3');

        $this->assertTrue($this->queue->offsetExists(0));
        $this->assertTrue($this->queue->offsetExists(1));
        $this->assertTrue($this->queue->offsetExists(2));
        $this->assertFalse($this->queue->offsetExists(3));

        try
        {
            $this->queue->remove(3);
            $this->assertFalse(true);
        }
        catch(\OutOfRangeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown index: 3');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testMessageRemoveChecksAsArray()
    {
        $this->queue[] = 'Test 1';
        $this->queue[] = 'Test 2';

        $this->assertTrue($this->queue->offsetExists(0));
        $this->assertTrue($this->queue->offsetExists(1));
        $this->assertFalse($this->queue->offsetExists(2));

        $this->assertTrue(isset($this->queue[0]));
        $this->assertTrue(isset($this->queue[1]));
        $this->assertFalse(isset($this->queue[2]));

        try
        {
            unset($this->queue[2]);
            $this->assertFalse(true);
        }
        catch(\OutOfRangeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown index: 2');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testQueueIteratorInterface()
    {
        $values = array('RC', 'Dog', 'Cat', 'Carrot Cake');

        foreach ($values as $data)
        {
            $this->queue->add($data);
        }

        foreach ($this->queue as $val)
        {
            $this->assertTrue(in_array($val, $values));
        }

        foreach ($this->queue as $key => $val)
        {
            $this->assertTrue(is_int($key));
            $this->assertEquals($val, $values[$key]);
        }
    }
}