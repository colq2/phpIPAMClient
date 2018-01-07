<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:16
 */

namespace PhpIPAMClient;

use PhpIPAMClient\Connection\Connection;


class PhpIPAMClient
{
	protected $connection;

	public function __construct(string $url, string $appID, string $username, string $password, string $apiKey, string $securityMethod = Connection::SECURITY_METHOD_SSL)
	{
		Connection::initializeConnection($url, $appID, $username, $password, $apiKey, $securityMethod);
	}
}