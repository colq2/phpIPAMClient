<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 16:12
 */

namespace colq2\PhpIPAMClient\Exception;


use colq2\PhpIPAMClient\Connection\Response;
use Throwable;

class PhpIPAMRequestException extends PhpIPAMException
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