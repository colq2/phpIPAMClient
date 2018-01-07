<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 22:18
 */

namespace colq2\PhpIPAMClient\Controller;


class Subnet extends BaseController
{

	protected static $controllerName = 'subnets';

	protected $id;
	protected $subnet;
	protected $mask;
	protected $description;
	protected $sectionId;
	protected $linked_subnet;
	protected $vlanId;
	protected $vrfId;
	protected $masterSubnetId;
	protected $nameserverId;
	protected $showName;
	protected $permissions;
	protected $DNSrecursive;
	protected $DNSrecords;
	protected $allowRequests;
	protected $scanAgent;
	protected $pingSubnet;
	protected $discoverSubnet;
	protected $isFolder;
	protected $isFull;
	protected $state;
	protected $threshold;
	protected $location;
	protected $editDate;


	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	public static function getByID(int $id)
	{
		$response = self::_getStatic([$id]);

		return new Subnet($response->getData());
	}

	public static function getCIDR(string $subnet)
	{
		//TODO implement
	}

	public function getUsage()
	{
		return self::_getStatic([$this->id, 'usage'])->getData();
	}

	public function getFirstFree()
	{
		return $this->_get([$this->id, 'first_free'])->getData();
	}

	public function getSlaves()
	{
		//TODO: return objects
		return $this->_get([$this->id, 'slaves'])->getData();
	}

	public function getSlavesRecursive()
	{
		//TODO: return objects
		return $this->_get([$this->id, 'slaves_recursive'])->getData();
	}

	public function getAddresses()
	{
		return $this->_get([$this->id, 'addresses'])->getData();
	}

	public function getAddressesIP(string $ip)
	{
		return $this->_get([$this->id, 'addresses', $ip])->getData();
	}

	public function getFirstSubnet(int $mask)
	{
		return $this->_get([$this->id, 'first_subnet', $mask])->getData();
	}

	public function getAllSubnets(int $mask)
	{
		return $this->_get([$this->id, 'all_subnets', $mask])->getData();
	}

	public function getCustomFields()
	{
		return $this->_get(['custom_fields']);
	}

	public function getSearch(string $subnet)
	{

	}


}