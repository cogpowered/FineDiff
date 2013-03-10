<?php

/**
 * PHP library for 37Signals Campfire. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <rob@vocabexpress.com>
 * @copyright Copyright (c) 2012, Alpha Initiatives Ltd.
 * @license MIT
 */

namespace rcrowe\Campfire;

/**
 * Deals with holding and enforcing proper configuration.
 */
class Config
{
    /**
     * Holds configuration options. Required options:
     *
     * @var array
     */
    protected $config = array(
        'subdomain' => null,
        'room'      => null,
        'key'       => null,
    );

    /**
     * Class constructor. Gets an instance insuring that the required config is set.
     *
     * You must pass in the following required config options: subdomain, room & key.
     *
     * @param array $config Pass in config options, must include required keys.
     * @return rcrowe\Campfire\Config
     *
     * @throws rcrowe\Campfire\Exceptions\ConfigException Thrown when a required config item is not passed in.
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;

        // Check we have all the config options we need
        foreach (array('subdomain', 'room', 'key') as $item) {

            if (!isset($this->config[$item]) OR !$this->config[$item]) {
                throw new Exceptions\ConfigException('Unable to find config item: '.$item);
            }
        }
    }

    /**
     * Return all config options.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Return an individual config item.
     *
     * @param mixed $item If found the value is returned, else NULL if it can't be found.
     * @return mixed
     */
    public function get($item)
    {
        return (isset($this->config[$item])) ? $this->config[$item] : null;
    }
}
