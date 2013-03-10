<?php

/**
 * Send a message to a Campfire room.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2012, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Campfire;

use Task;
use BuildException;
use Project;
use Exception;
use rcrowe\Campfire as Campfire;

/**
 * Send a single message to a specific Campfire room.
 */
class PhingTask extends Task
{
    /**
     * @var string Campfire subdomain.
     */
    protected $subdomain;

    /**
     * @var string|int Campfire room.
     */
    protected $room;

    /**
     * @var string API key for Campfire user the message will be sent from.
     */
    protected $key;

    /**
     * @var string Message to send to the Campfire room.
     */
    protected $msg;

    /**
     * @var bool On failure should we abort. If false Phing continues.
     */
    protected $haltOnFailure = false;

    /**
     * Set the Campfire subdomain.
     *
     * @param string $subdomain Campfire subdomain.
     * @return void
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * Get the Campfire subdomain.
     *
     * @throws BuildException When subdomain has not been set.
     * @return string
     */
    public function getSubdomain()
    {
        $subdomain = ($this->subdomain !== null) ? $this->subdomain : $this->getProject()->getProperty('campfire.subdomain');

        if ($subdomain === null) {
            throw new BuildException('Campfire subdomain is not set');
        }

        return $subdomain;
    }

    /**
     * Set the Campfire room.
     *
     * @param string $room Campfire room.
     * @return void
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * Get the Campfire room.
     *
     * @throws BuildException When room has not been set.
     * @return string
     */
    public function getRoom()
    {
        $room = ($this->room !== null) ? $this->room : $this->getProject()->getProperty('campfire.room');

        if ($room === null) {
            throw new BuildException('Campfire room is not set');
        }

        return $room;
    }

    /**
     * Set the Campfire api key.
     *
     * @param string $key Campfire key.
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the Campfire key.
     *
     * @throws BuildException When key has not been set.
     * @return string
     */
    public function getKey()
    {
        $key = ($this->key !== null) ? $this->key : $this->getProject()->getProperty('campfire.key');

        if ($key === null) {
            throw new BuildException('Campfire key is not set');
        }

        return $key;
    }

    /**
     * Set the message to be sent to the Campfire room.
     *
     * @param string $msg Message.
     * @return void
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Get the message being sent to the Campfire room.
     *
     * @throws BuildException When key has not been set.
     * @return string
     */
    public function getMsg()
    {
        if ($this->msg === null) {
            throw new BuildException('Campfire message is not set');
        }

        return $this->msg;
    }

    /**
     * Set whether to exit on error.
     *
     * @param bool $halt Exit on error, default FALSE.
     * @return void
     */
    public function setHaltOnFailure($halt)
    {
        $this->haltOnFailure = $halt;
    }

    /**
     * Kicks everything off
     *
     * @return void
     */
    public function main()
    {
        $campfire = new Campfire(array(
            'subdomain' => $this->getSubdomain(),
            'room'      => $this->getRoom(),
            'key'       => $this->getKey(),
        ));

        try {
            $campfire->send($this->getMsg());
        } catch (Exception $ex) {

            $msg = 'Failed to send message to Campfire: '.$ex->getMessage();

            // Do we want this error to halt the rest of the build file
            if ($this->haltOnFailure) {
                throw new BuildException($msg);
            } else {
                $this->log($msg, Project::MSG_ERR);
                return;
            }
        }

        $this->log('Successfully sent message to Campfire', Project::MSG_INFO);
    }
}