<?php

/**
 * PHP library for 37Signals Campfire. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <rob@vocabexpress.com>
 * @copyright Copyright (c) 2012, Alpha Initiatives Ltd.
 * @license MIT
 */

namespace rcrowe\Campfire;

use \rcrowe\Campfire as Campfire;

/**
 * Provides a static interface to the library if that's your bag.
 */
class Facade
{
    /**
     * @var rcrowe\Campfire\Facade
     */
    protected static $instance;

    /**
     * Get a new instance of Campfire.
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
    public static function init(array $config = array(), $http = null)
    {
        return static::instance(new Campfire($config, $http));
    }

    /**
     * Returns the currently initialised instance of rcrowe\Campfire.
     *
     * @param rcrowe\Campfire $instance Set the instance.
     * @return rcrowe\Campfire
     */
    public static function instance(Campfire $instance = null)
    {
        if ($instance !== null) {
            static::$instance = $instance;
        }

        return static::$instance;
    }

    /**
     * Remove the static instance of rcrowe\Campfire.
     *
     * This might be used to reload the instance of rcrowe\Campfire if a config variable
     * needed to change.
     *
     * @return void
     */
    public static function destroy()
    {
        static::$instance = null;
    }

    /**
     * Exposes the following methods on the class: queue & send.
     *
     * Before calling must have initialised either with Facade::init() or Facade::instance().
     *
     * @param string $name      Method name being called.
     * @param array  $arguments Array of parameters passed to the method.
     * @return mixed
     *
     * @throws rcrowe\Campfire\Exceptions\FacadeException Class not initialised.
     * @throws InvalidArgumentException                                   Thrown if adding a message that isn't a string.
     * @throws rcrowe\Campfire\Exceptions\TransportException              Thrown when the queue of messages is empty or a HTTP error.
     * @throws rcrowe\Campfire\Exceptions\Transport\UnauthorizedException Thrown when API key is wrong.
     * @throws rcrowe\Campfire\Exceptions\UnknownException                Thrown when an unknown error occurs.
     */
    public static function __callStatic($name, $arguments)
    {
        // Do we have a valid instance initialised
        if (!isset(static::$instance)) {
            throw new Campfire\Exceptions\FacadeException('Facade::init(...) must be called first');
        }

        switch ($name) {
            case 'msg':
                $method = 'send';
                $arg1   = (isset($arguments[0])) ? $arguments[0] : null;

                if ($arg1 === null) {
                    // throw exception
                    throw new \InvalidArgumentException('No message was passed in as the first argument');
                }
                break;
            default:
                $method = $name;
                $arg1   = (isset($arguments[0])) ? $arguments[0] : null;
        }

        static::$instance->$method($arg1);
    }
}
