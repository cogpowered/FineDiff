<?php

/**
 * PHP library for 37Signals Campfire. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <rob@vocabexpress.com>
 * @copyright Copyright (c) 2012, Alpha Initiatives Ltd.
 * @license MIT
 */

namespace rcrowe;

/**
 * PHP library for 37Signals Campfire. Designed for incidental notifications from an application.
 *
 * This class provides an OO interface for sending messages with the Campfire library.
 */
class Campfire
{
    /**
     * @var rcrowe\Campfire\Config
     */
    protected $config;

    /**
     * @var rcrowe\Campfire\Queue
     */
    protected $queue;

    /**
     * @var rcrowe\Campfire\Transport
     */
    protected $transport;

    /**
     * Class constructor. Get an instance of the Campfire library.
     *
     * Expects to the see the following paramaters passed in the config array:
     *     - subdomain: http://{subdomain}.campfirenow.com.
     *     - room: Numeric ID for the room you want the message sent to.
     *     - key: API key for the user you the message sent from.
     *
     * @param array              $config Pass in the required config params to initalise the library.
     * @param Guzzle\Http\Client $http   Mainly used for mocking the transport layer.
     * @return rcrowe\Campfire
     *
     * @throws rcrowe\Campfire\Exceptions\ConfigException Thrown when a required config option is missing
     * @throws InvalidArgumentException                   Thrown when the $http param is not an instance of Guzzle\Http\Client
     */
    public function __construct(array $config = array(), $http = null)
    {
        $this->config    = new Campfire\Config($config);
        $this->queue     = new Campfire\Queue;
        $this->transport = new Campfire\Transport($this->config, $http);
    }

    /**
     * Add a string or object with __toString defined to the queue of messges to send.
     *
     * Queue as many messages as you like and have them sent together. Currently they
     * are each sent as seperate messages.
     *
     * @see Campfire::send()
     *
     * @param string|object $msg String or an object that can be cast to a string.
     * @return int Index of the item added to the queue, useful for removing it.
     *
     * @throws InvalidArgumentException Thrown if you try adding anything apart from a string.
     */
    public function queue($msg)
    {
        return $this->queue->add($msg);
    }

    /**
     * Send a single message or the contents of the message queue.
     *
     * @param string|object $msg String or an object that can be cast to a string.
     * @return bool Returns TRUE when all messages have been sent.
     *
     * @throws InvalidArgumentException                                   Thrown if adding a message that isn't a string.
     * @throws rcrowe\Campfire\Exceptions\TransportException              Thrown when the queue of messages is empty or a HTTP error.
     * @throws rcrowe\Campfire\Exceptions\Transport\UnauthorizedException Thrown when API key is wrong.
     * @throws rcrowe\Campfire\Exceptions\UnknownException                Thrown when an unknown error occurs.
     */
    public function send($msg = null)
    {
        if ($msg !== null) {
            $this->queue->add($msg);
        }

        // Check there's something in the queue to send
        if (count($this->queue) === 0) {
            throw new Campfire\Exceptions\TransportException('Queue is empty');
        }

        // Loop over and send each item in the queue
        foreach ($this->queue as $msg) {
            $this->transport->send($msg);
        }

        $this->queue->remove();

        return true;
    }
}
