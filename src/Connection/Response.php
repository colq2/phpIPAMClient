<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 01:48
 */

namespace colq2\PhpIPAMClient\Connection;


class Response
{
	protected $code;
	protected $success;
	protected $message;
	protected $data;
	protected $time;

	protected $body;

	public function __construct(\GuzzleHttp\Psr7\Response $response)
	{
		//Get Body from guzzle response
		$body       = json_decode((string) $response->getBody(), true);
		$this->body = $body;

		$this->code = $body['code'];

		if (isset($body['success']))
		{
			$this->success = (bool) $body['success'];
		}

		if (isset($body['message']))
		{
			$this->message = $body['message'];
		}

		if (isset($body['data']))
		{
			$this->data = $body['data'];
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
	 * @return bool
	 */
	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @return string|array|null
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return double
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @return mixed
	 */
	public function getBody()
	{
		return $this->body;
	}
}