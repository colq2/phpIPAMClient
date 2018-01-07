<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:16
 */

namespace colq2\PhpIPAMClient;

use colq2\PhpIPAMClient\Connection\Connection;


class PhpIPAMClient
{
	protected $connection;

	public function __construct(string $url, string $appID, string $username, string $password, string $apiKey, string $securityMethod = Connection::SECURITY_METHOD_SSL)
	{
		$this->connection = Connection::initializeConnection($url, $appID, $username, $password, $apiKey, $securityMethod);
	}

	public function call(string $method, string $controller, array $identifiers = array(), array $params = array())
	{
		return $this->connection->call($method, $controller, $identifiers, $params);
	}

	public function getAllControllers()
	{
		return $this->call('OPTIONS', '')->getData();
	}
}