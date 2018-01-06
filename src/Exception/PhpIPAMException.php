<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 17:57
 */

namespace PhpIPAM\Exception;


use Throwable;

class PhpIPAMException extends \Exception
{
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}