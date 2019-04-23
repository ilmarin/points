<?php

namespace App;

/**
 * Base exception class for application
 */
class ApplicationException extends \Exception
{

    protected $data;

    public function __construct($message, $previous)
    {
        $request = app('request');

        $this->data['query_string'] = $request->fullUrl();
        parent::__construct($message, 0, $previous);
    }

    public function getData()
    {
        return $this->data;
    }

}
