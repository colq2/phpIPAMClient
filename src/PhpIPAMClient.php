<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:16
 */

namespace colq2\PhpIPAMClient;

use colq2\PhpIPAMClient\Connection\Connection;
use colq2\PhpIPAMClient\Controller\Address;
use colq2\PhpIPAMClient\Controller\Device;
use colq2\PhpIPAMClient\Controller\Folder;
use colq2\PhpIPAMClient\Controller\L2Domain;
use colq2\PhpIPAMClient\Controller\Section;
use colq2\PhpIPAMClient\Controller\Subnet;
use colq2\PhpIPAMClient\Controller\VLAN;
use colq2\PhpIPAMClient\Controller\VRF;


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


	public function getToken()
	{
		return $this->connection->getToken();
	}

	public function getTokenExpires()
	{
		return $this->connection->getTokenExpires();
	}
	public function getAllUsers()
	{
		return $this->call('get', 'user', ['all'])->getData();
	}

	public function getAllAdmins()
	{
		return $this->call('admins', 'user', ['all'])->getData();
	}

	public function section(): Section
	{
		return new Section();
	}

	public function subnet(): Subnet
	{
		return new Subnet();
	}

	public function folder(): Folder
	{
		return new Folder();
	}

	public function vlan(): VLAN
	{
		return new VLAN();
	}

	public function address(): Address
	{
		return new Address();
	}

	public function l2domain(): L2Domain
	{
		return new L2Domain();
	}

	public function vrf(): VRF
	{
		return new VRF();
	}

	public function device(): Device
	{
		return new Device();
	}
}