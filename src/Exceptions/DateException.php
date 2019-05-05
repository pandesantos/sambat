<?php
/**
 * Created by PhpStorm.
 * User: Santosh
 * Date: 5/5/2019
 * Time: 2:46 PM
 */

namespace Santosh\Sambat\Exceptions;

use Exception;
use Throwable;

class DateException extends Exception
{
   public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
   {
       parent::__construct($message, $code, $previous);
   }
}