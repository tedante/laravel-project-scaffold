<?php

namespace App\Exceptions;

use Exception;

class UnprocessEntityException extends Exception
{
  protected $statusCode = 422;

  public function __construct($message = '', $code = 0)
  {
    parent::__construct();
    $this->message = $message;
    $this->code = $code;
  }

  final function getStatusCode(){
    return $this->statusCode;
  }
}