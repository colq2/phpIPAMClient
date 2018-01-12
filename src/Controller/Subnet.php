<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 22:18
 */

namespace colq2\PhpIPAMClient\Controller;


use colq2\PhpIPAMClient\Exception\PhpIPAMRequestException;

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

	protected static function transformParamsToIDs(array $params): array
	{
		//sectionId, linked_subnet, vlanId, vrfId, masterSubnetId
		$params = self::getIDFromParams($params, 'sectionId', ['sectionID', 'section'], Section::class);
		$params = self::getIDFromParams($params, 'linked_subnet', ['linked_subnetId'], Subnet::class);
		$params = self::getIDFromParams($params, 'vlanId', ['vlanID', 'vlan'], VLAN::class);
		$params = self::getIDFromParams($params, 'vrfId', ['vrfID', 'vrf'], VRF::class);

		return $params;
	}

	public static function getAll()
	{
		$response = static::_getStatic(['all']);
		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}
		$objects = [];

		foreach ($response->getData() as $object)
		{
			$objects[] = new static($object);
		}

		return $objects;
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
		$slaves  = $this->_get([$this->id, 'slaves'])->getData();
		if(is_null($slaves) or empty($slaves)){
			return [];
		}
		$subnets = [];

		foreach ($slaves as $slave)
		{
			$subnets[] = new Subnet($slave);
		}

		return $subnets;
	}

	public function getSlavesRecursive()
	{
		$slaves  = $this->_get([$this->id, 'slaves_recursive'])->getData();
		$subnets = [];

		foreach ($slaves as $slave)
		{
			$subnets[] = new Subnet($slave);
		}

		return $subnets;
	}

	public function getAddresses()
	{
		$addresses    = $this->_get([$this->id, 'addresses']);
		$addressesArr = [];
		foreach ($addresses as $address)
		{
			$addressesArr[] = new Address($address);
		}

	}

	public function getAddressesIP(string $ip)
	{
		$response = $this->_get([$this->id, 'addresses', $ip]);
		if (is_null($response->getData()))
		{
			throw new PhpIPAMRequestException($response);
		}
		else
		{
			$data = array_values($response->getData());

			return new Address($data[0]);
		}
	}

	public function getFirstSubnet(int $mask)
	{
		return new Subnet($this->_get([$this->id, 'first_subnet', $mask])->getData());
	}

	public function getAllSubnets(int $mask)
	{
		//TODO return objects
		return $this->_get([$this->id, 'all_subnets', $mask])->getData();
	}

	public function getCustomFields()
	{
		return $this->_get(['custom_fields'])->getData();
	}

	public function getCIDRSearch(string $subnet)
	{
		return $this->_get(['cidr', $subnet])->getData();
	}

	public function getSearch(string $subnet)
	{
		return $this->_get(['search', $subnet])->getData();
	}

	public function postFirstSubnet(int $mask, $params): Subnet
	{
		$params = self::transformParamsToIDs($params);
		$response = $this->_post([$this->id, 'first_subnet', $mask], $params);
		$id = $response->getBody()['id'];

		return Subnet::getByID($id);
	}

	public function patchResize(int $mask)
	{
		try
		{
			$this->_patch([$this->id, 'resize'], ['mask' => $mask]);
		}
		catch (PhpIPAMRequestException $e)
		{
			if ($e->getMessage() == "New network is same as old network")
			{
				return true;
			}
			else
			{
				throw $e;
			}
		}

		$this->setParams(Subnet::getByID($this->id)->getParams());

		return true;
	}

	public function patchSplit(int $number)
	{
		return $this->_patch([$this->id, 'split'], ['number' => $number])->isSuccess();
	}

	public function deleteTruncate()
	{
		return $this->_delete([$this->id, 'truncate'])->isSuccess();
	}

	public function deletePermissions()
	{
		return $this->_delete([$this->id, 'permissions'])->isSuccess();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSubnet(): string
	{
		return $this->subnet;
	}

	/**
	 * @return int
	 */
	public function getMask(): int
	{
		return $this->mask;
	}

	/**
	 * @param int $mask
	 *
	 * @return Subnet
	 */
	public function setMask(int $mask)
	{
		$this->mask = $mask;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return Subnet
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|Section
	 */
	public function getSectionId(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->sectionId, Section::class, $asObject);
	}

	/**
	 * @param int|Section $sectionId
	 *
	 * @return Subnet
	 */
	public function setSectionId($sectionId)
	{
		$this->sectionId = $sectionId;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|Subnet|null
	 */
	public function getLinkedSubnet(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->linked_subnet, Subnet::class, $asObject);
	}

	/**
	 * @param int|Subnet $linked_subnet
	 *
	 * @return Subnet
	 */
	public function setLinkedSubnet($linked_subnet)
	{
		$this->linked_subnet = $linked_subnet;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|VLAN|null
	 */
	public function getVlanId(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->vlanId, VLAN::class, $asObject);
	}

	/**
	 * @param int|VLAN $vlanId
	 *
	 * @return Subnet
	 */
	public function setVlanId($vlanId)
	{
		$this->vlanId = $vlanId;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|VRF|null
	 */
	public function getVrfId(bool $asObject = true)
	{
		return self::getAsObjectOrID($this->vrfId, VRF::class, $asObject);
	}

	/**
	 * @param int|VRF $vrfId
	 *
	 * @return Subnet
	 */
	public function setVrfId($vrfId)
	{
		$this->vrfId = $vrfId;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|Subnet|null
	 */
	public function getMasterSubnetId(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->masterSubnetId, Subnet::class, $asObject);
	}

	/**
	 * @param int|Subnet $masterSubnetId
	 *
	 * @return Subnet
	 */
	public function setMasterSubnetId($masterSubnetId)
	{
		$this->masterSubnetId = $masterSubnetId;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getNameserverId()
	{
		return $this->nameserverId;
	}

	/**
	 * @param int|null $nameserverId
	 *
	 * @return Subnet
	 */
	public function setNameserverId(int $nameserverId)
	{
		$this->nameserverId = $nameserverId;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getShowName(): bool
	{
		return $this->showName;
	}

	/**
	 * @param bool $showName
	 *
	 * @return Subnet
	 */
	public function setShowName(bool $showName)
	{
		$this->showName = $showName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPermissions(): string
	{
		return $this->permissions;
	}

	/**
	 * @param string $permissions
	 *
	 * @return Subnet
	 */
	public function setPermissions(string $permissions)
	{
		$this->permissions = $permissions;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getDNSrecursive(): bool
	{
		return $this->DNSrecursive;
	}

	/**
	 * @param bool $DNSrecursive
	 *
	 * @return Subnet
	 */
	public function setDNSrecursive(bool $DNSrecursive)
	{
		$this->DNSrecursive = $DNSrecursive;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getDNSrecords(): bool
	{
		return $this->DNSrecords;
	}

	/**
	 * @param bool $DNSrecords
	 *
	 * @return Subnet
	 */
	public function setDNSrecords(bool $DNSrecords)
	{
		$this->DNSrecords = $DNSrecords;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAllowRequests(): bool
	{
		return $this->allowRequests;
	}

	/**
	 * @param bool $allowRequests
	 *
	 * @return Subnet
	 */
	public function setAllowRequests(bool $allowRequests)
	{
		$this->allowRequests = $allowRequests;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getScanAgent(): bool
	{
		return $this->scanAgent;
	}

	/**
	 * @param bool $scanAgent
	 *
	 * @return Subnet
	 */
	public function setScanAgent(bool $scanAgent)
	{
		$this->scanAgent = $scanAgent;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getPingSubnet(): bool
	{
		return $this->pingSubnet;
	}

	/**
	 * @param bool $pingSubnet
	 *
	 * @return Subnet
	 */
	public function setPingSubnet(bool $pingSubnet)
	{
		$this->pingSubnet = $pingSubnet;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getDiscoverSubnet(): bool
	{
		return $this->discoverSubnet;
	}

	/**
	 * @param bool $discoverSubnet
	 *
	 * @return Subnet
	 */
	public function setDiscoverSubnet(bool $discoverSubnet)
	{
		$this->discoverSubnet = $discoverSubnet;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsFolder(): bool
	{
		return $this->isFolder;
	}

	/**
	 * @param bool $isFolder
	 *
	 * @return Subnet
	 */
	public function setIsFolder(bool $isFolder)
	{
		$this->isFolder = $isFolder;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsFull(): bool
	{
		return $this->isFull;
	}

	/**
	 * @return int
	 */
	public function getState(): int
	{
		return $this->state;
	}

	/**
	 * @param int $state
	 *
	 * @return Subnet
	 */
	public function setState(int $state)
	{
		$this->state = $state;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getThreshold(): int
	{
		return $this->threshold;
	}

	/**
	 * @param int $threshold
	 *
	 * @return Subnet
	 */
	public function setThreshold(int $threshold)
	{
		$this->threshold = $threshold;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getLocation(): int
	{
		return $this->location;
	}

	/**
	 * @param int $location
	 *
	 * @return Subnet
	 */
	public function setLocation(int $location)
	{
		$this->location = $location;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEditDate(): string
	{
		return $this->editDate;
	}

}