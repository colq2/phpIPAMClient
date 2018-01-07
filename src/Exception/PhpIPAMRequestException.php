<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 16:12
 */

namespace PhpIPAMClient\Exception;


use PhpIPAMClient\Connection\Response;
use Throwable;

class PhpIPAMRequestException extends \Exception
{
	protected $response;

	public function __construct(Response $response, string $message = "", int $code = 0, Throwable $previous = null)
	{
		$this->response = $response;
		parent::__construct($message, $code, $previous);
	}

	public function getResponse(): Response
	{
		return $this->response;
	}
}