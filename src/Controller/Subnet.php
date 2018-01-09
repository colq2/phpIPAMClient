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
		//TODO return object
		return $this->_get([$this->id, 'addresses'])->getData();
	}

	public function getAddressesIP(string $ip)
	{
		//TODO return object
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

	public function getCIDRSearch(string $subnet)
	{
		return $this->_get(['cidr', $subnet])->getData();
	}

	public function getSearch(string $subnet)
	{
		return $this->_get(['search', $subnet])->getData();
	}

	public static function post(array $params = array())
	{
		//Params that could be converted from objects to id
		$params   = self::transformToIDs($params);
		$response = self::_postStatic([], $params);
		$id       = $response->getBody()['id'];

		return Subnet::getByID($id);
	}


	public function postFirstSubnet(int $mask): Subnet
	{
		$response = $this->_post([$this->id, 'first_subnet', $mask]);
		dd($response);
		$id = $response->getBody()['id'];

		return Subnet::getByID($id);
	}

	protected static function transformParamsToIDs(array $params)
	{
		//sectionId, linked_subnet, vlanId, vrfId, masterSubnetId
		$params = self::getIDFromParams($params, 'sectionId', ['sectionID', 'section'], Section::class);
		$params = self::getIDFromParams($params, 'linked_subnet', ['linked_subnetId'], Subnet::class);
		$params = self::getIDFromParams($params, 'vlanId', ['vlanID', 'vlan'], 'vlan::class');
		$params = self::getIDFromParams($params, 'vrfId', ['vrfID', 'vrf'], 'vrf::class');

		return $params;
	}

	//TODO adjust getter and setter

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Subnet
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSubnet()
	{
		return $this->subnet;
	}

	/**
	 * @param mixed $subnet
	 *
	 * @return Subnet
	 */
	public function setSubnet($subnet)
	{
		$this->subnet = $subnet;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMask()
	{
		return $this->mask;
	}

	/**
	 * @param mixed $mask
	 *
	 * @return Subnet
	 */
	public function setMask($mask)
	{
		$this->mask = $mask;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return Subnet
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSectionId()
	{
		return $this->sectionId;
	}

	/**
	 * @param mixed $sectionId
	 *
	 * @return Subnet
	 */
	public function setSectionId($sectionId)
	{
		$this->sectionId = $sectionId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLinkedSubnet()
	{
		return $this->linked_subnet;
	}

	/**
	 * @param mixed $linked_subnet
	 *
	 * @return Subnet
	 */
	public function setLinkedSubnet($linked_subnet)
	{
		$this->linked_subnet = $linked_subnet;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getVlanId()
	{
		return $this->vlanId;
	}

	/**
	 * @param mixed $vlanId
	 *
	 * @return Subnet
	 */
	public function setVlanId($vlanId)
	{
		$this->vlanId = $vlanId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getVrfId()
	{
		return $this->vrfId;
	}

	/**
	 * @param mixed $vrfId
	 *
	 * @return Subnet
	 */
	public function setVrfId($vrfId)
	{
		$this->vrfId = $vrfId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMasterSubnetId()
	{
		return $this->masterSubnetId;
	}

	/**
	 * @param mixed $masterSubnetId
	 *
	 * @return Subnet
	 */
	public function setMasterSubnetId($masterSubnetId)
	{
		$this->masterSubnetId = $masterSubnetId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getNameserverId()
	{
		return $this->nameserverId;
	}

	/**
	 * @param mixed $nameserverId
	 *
	 * @return Subnet
	 */
	public function setNameserverId($nameserverId)
	{
		$this->nameserverId = $nameserverId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getShowName()
	{
		return $this->showName;
	}

	/**
	 * @param mixed $showName
	 *
	 * @return Subnet
	 */
	public function setShowName($showName)
	{
		$this->showName = $showName;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

	/**
	 * @param mixed $permissions
	 *
	 * @return Subnet
	 */
	public function setPermissions($permissions)
	{
		$this->permissions = $permissions;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDNSrecursive()
	{
		return $this->DNSrecursive;
	}

	/**
	 * @param mixed $DNSrecursive
	 *
	 * @return Subnet
	 */
	public function setDNSrecursive($DNSrecursive)
	{
		$this->DNSrecursive = $DNSrecursive;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDNSrecords()
	{
		return $this->DNSrecords;
	}

	/**
	 * @param mixed $DNSrecords
	 *
	 * @return Subnet
	 */
	public function setDNSrecords($DNSrecords)
	{
		$this->DNSrecords = $DNSrecords;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAllowRequests()
	{
		return $this->allowRequests;
	}

	/**
	 * @param mixed $allowRequests
	 *
	 * @return Subnet
	 */
	public function setAllowRequests($allowRequests)
	{
		$this->allowRequests = $allowRequests;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getScanAgent()
	{
		return $this->scanAgent;
	}

	/**
	 * @param mixed $scanAgent
	 *
	 * @return Subnet
	 */
	public function setScanAgent($scanAgent)
	{
		$this->scanAgent = $scanAgent;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPingSubnet()
	{
		return $this->pingSubnet;
	}

	/**
	 * @param mixed $pingSubnet
	 *
	 * @return Subnet
	 */
	public function setPingSubnet($pingSubnet)
	{
		$this->pingSubnet = $pingSubnet;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDiscoverSubnet()
	{
		return $this->discoverSubnet;
	}

	/**
	 * @param mixed $discoverSubnet
	 *
	 * @return Subnet
	 */
	public function setDiscoverSubnet($discoverSubnet)
	{
		$this->discoverSubnet = $discoverSubnet;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getisFolder()
	{
		return $this->isFolder;
	}

	/**
	 * @param mixed $isFolder
	 *
	 * @return Subnet
	 */
	public function setIsFolder($isFolder)
	{
		$this->isFolder = $isFolder;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getisFull()
	{
		return $this->isFull;
	}

	/**
	 * @param mixed $isFull
	 *
	 * @return Subnet
	 */
	public function setIsFull($isFull)
	{
		$this->isFull = $isFull;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param mixed $state
	 *
	 * @return Subnet
	 */
	public function setState($state)
	{
		$this->state = $state;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getThreshold()
	{
		return $this->threshold;
	}

	/**
	 * @param mixed $threshold
	 *
	 * @return Subnet
	 */
	public function setThreshold($threshold)
	{
		$this->threshold = $threshold;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocation()
	{
		return $this->location;
	}

	/**
	 * @param mixed $location
	 *
	 * @return Subnet
	 */
	public function setLocation($location)
	{
		$this->location = $location;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEditDate()
	{
		return $this->editDate;
	}

	/**
	 * @param mixed $editDate
	 *
	 * @return Subnet
	 */
	public function setEditDate($editDate)
	{
		$this->editDate = $editDate;

		return $this;
	}



}