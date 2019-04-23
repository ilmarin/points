<?php

namespace App;

/**
 * Description of PointsRepositoryException
 *
 * @author ilya
 */
class PointsRepositoryException extends ApplicationException
{

    public function __construct($message, array $data, \Throwable $previous)
    {

        $this->data = $data;
        parent::__construct($message, $previous);
    }

}
