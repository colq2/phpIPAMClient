<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 01:48
 */

namespace PhpIPAMClient\Connection;


class Response
{
	protected $code;
	protected $message;
	protected $time;
	protected $data;

	protected $body;

	public function __construct(\GuzzleHttp\Psr7\Response $response)
	{
		//Get Body from guzzle respons
		$body = json_decode((string) $response->getBody(), true);
		$this->body;

		$this->code = $body['code'];
		if ($body['success'])
		{
			$this->data    = $body['data'];
			$this->message = "";
		}
		else
		{
			$this->message = $body['message'];
		}
		$this->time = $body['time'];

	}

	/**
	 * @return integer
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return double
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}