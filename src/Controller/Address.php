<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:34
 */

namespace colq2\PhpIPAMClient\Controller;


class Address extends BaseController
{

	protected static $controllerName = 'addresses';

	protected $id;
	protected $subnetId;
	protected $ip;
	protected $is_gateway;
	protected $description;
	protected $hostname;
	protected $mac;
	protected $owner;
	protected $tag;
	protected $PTRignore;
	protected $PTR;
	protected $deviceId;
	protected $port;
	protected $note;
	protected $lastSeen;
	protected $excludePing;
	protected $editDate;

	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	protected static function transformParamsToIDs(array $params): array
	{
		$params = self::getAsObjectOrID($params, 'subnetId', ['subnet', 'subnetID'], Subnet::class);
		$params = self::getAsObjectOrID($params, 'deviceId', ['device', 'deviceID'], Device::class);
		return $params;
	}

	public static function getByID(int $id)
	{
		return new Address(self::_getStatic([$id])->getData());
	}

	public function getPing()
	{
		return $this->_get([$this->id, 'ping'])->getData();
	}

	/**
	 * @param string     $ip
	 * @param int|Subnet $subnet
	 *
	 * @return Address
	 */
	public static function getByIPAndSubnet(string $ip, $subnet)
	{
		if($subnet instanceof Subnet){
			$subnet = $subnet->getId();
		}

		return new Address(self::_getStatic([$ip, $subnet])->getData());
	}

	public static function getSearchByIP(string $ip)
	{
		$addr = self::_getStatic(['search', $ip])->getData();
		if(is_null($addr) or empty($addr)){
			return [];
		}else{
			$addresses = [];
			foreach ($addr as $address){
				$addresses[] = new Address($address);
			}
			return $addresses;
		}
	}

	public static function getSearchByHostname(string $hostname)
	{
		$addr = self::_getStatic(['search_hostname', $hostname])->getData();
		if(is_null($addr) or empty($addr)){
			return [];
		}else{
			$addresses = [];
			foreach ($addr as $address){
				$addresses[] = new Address($address);
			}
			return $addresses;
		}
	}

	/**
	 * @param int|Section $subnet
	 *
	 * @return string
	 */
	public static function getFirstFree($subnet)
	{
		if($subnet instanceof Subnet){
			$subnet = $subnet->getId();
		}

		return self::_getStatic(['first_free', $subnet])->getData();
	}

	public static function getCustomFields()
	{
		return self::_getStatic(['custom_fields'])->getData();
	}

	public static function getTags()
	{
		return self::_getStatic(['tags'])->getData();
	}

	public static function getTagByID(int $id)
	{
		return self::_getStatic(['tags', $id])->getData();
	}

	public static function getAddressesByTag(int $id)
	{
		$addr = self::_getStatic(['tags', $id, 'addresses'])->getData();
		if(is_null($addr) or empty($addr)){
			return [];
		}else{
			$addresses = [];
			foreach ($addr as $address){
				$addresses[] = new Address($address);
			}
			return $addresses;
		}
	}

	/**
	 * @param array $params
	 *
	 * @return Address
	 */
	public static function post(array $params): Address
	{
		$params = self::transformParamsToIDs($params);
		$response = self::_postStatic([], $params);
		$id = $response->getBody()['id'];

		return Address::getByID($id);
	}

	public static function postFirstFree($subnet, array $params = array())
	{
		if($subnet instanceof Subnet){
			$subnet = $subnet->getId();
		}

		$params = self::transformParamsToIDs($params);
		$response = self::_postStatic(['first_free', $subnet], $params);
		$id = $response->getBody()['id'];
		return Address::getByID($id);
	}

	public function delete()
	{
		return $this->_delete()->isSuccess();
	}

	public static function deleteByIPAndSubnet(string $ip, $subnet)
	{
		if($subnet instanceof Subnet){
			$subnet = $subnet->getId();
		}

		return self::_deleteStatic([$ip, $subnet]);
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|Subnet
	 */
	public function getSubnetId(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->subnetId, Subnet::class, $asObject);
	}

	/**
	 * @param int|Subnet $subnetId
	 *
	 * @return Address
	 */
	public function setSubnetId($subnetId)
	{
		$this->subnetId = $subnetId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIP(): string
	{
		return $this->ip;
	}

	/**
	 * @param string $ip
	 *
	 * @return Address
	 */
	public function setIP(string $ip)
	{
		$this->ip = $ip;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsGateway(): bool
	{
		return $this->is_gateway;
	}

	/**
	 * @param bool $is_gateway
	 *
	 * @return Address
	 */
	public function setIsGateway(bool $is_gateway)
	{
		$this->is_gateway = $is_gateway;

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
	 * @return Address
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHostname(): string
	{
		return $this->hostname;
	}

	/**
	 * @param string $hostname
	 *
	 * @return Address
	 */
	public function setHostname(string $hostname)
	{
		$this->hostname = $hostname;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMac(): string
	{
		return $this->mac;
	}

	/**
	 * @param string $mac
	 *
	 * @return Address
	 */
	public function setMac(string $mac)
	{
		$this->mac = $mac;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOwner(): string
	{
		return $this->owner;
	}

	/**
	 * @param string $owner
	 *
	 * @return Address
	 */
	public function setOwner(string $owner)
	{
		$this->owner = $owner;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getTag(): int
	{
		return $this->tag;
	}

	/**
	 * @param int $tag
	 *
	 * @return Address
	 */
	public function setTag(int $tag)
	{
		$this->tag = $tag;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getPTRignore(): bool
	{
		return $this->PTRignore;
	}

	/**
	 * @param bool $PTRignore
	 *
	 * @return Address
	 */
	public function setPTRignore(bool $PTRignore)
	{
		$this->PTRignore = $PTRignore;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPTR(): int
	{
		return $this->PTR;
	}

	/**
	 * @param int $PTR
	 *
	 * @return Address
	 */
	public function setPTR(int $PTR)
	{
		$this->PTR = $PTR;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return int|Device|null
	 */
	public function getDeviceID(bool $asObject = null)
	{
		return self::getAsObjectOrID($this->deviceId, Device::class, $asObject);
	}

	/**
	 * @param int|Device|null $deviceId
	 *
	 * @return Address
	 */
	public function setDeviceID($deviceId)
	{
		$this->deviceId = $deviceId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPort(): string
	{
		return $this->port;
	}

	/**
	 * @param string $port
	 *
	 * @return Address
	 */
	public function setPort(string $port)
	{
		$this->port = $port;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote(): string
	{
		return $this->note;
	}

	/**
	 * @param string $note
	 *
	 * @return Address
	 */
	public function setNote(string $note)
	{
		$this->note = $note;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastSeen(): string
	{
		return $this->lastSeen;
	}

	/**
	 * @param string $lastSeen
	 *
	 * @return Address
	 */
	public function setLastSeen(string $lastSeen)
	{
		$this->lastSeen = $lastSeen;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getExcludePing(): bool
	{
		return $this->excludePing;
	}

	/**
	 * @param bool $excludePing
	 *
	 * @return Address
	 */
	public function setExcludePing(bool $excludePing)
	{
		$this->excludePing = $excludePing;

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