<?php

namespace rcrowe\Campfire\Exceptions;

class TransportException extends \Exception
{
    /**
     * @var Response
     */
    protected $response;

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
