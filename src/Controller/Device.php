<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:40
 */

namespace colq2\PhpIPAMClient\Controller;


class Device extends BaseController
{

	protected static $controllerName = 'devices';

	protected $id;
	protected $hostname;
	protected $ip;
	protected $type;
	protected $description;
	protected $sections;
	protected $rack;
	protected $rack_start;
	protected $rack_size;
	protected $location;
	protected $editDate;

	protected static function transformParamsToIDs(array $params): array
	{
		$params['sections'] = self::convertSectionsToID($params['sections']);

		return $params;
	}

	public function getSubnets()
	{
		$response = $this->_get([$this->getId(), 'subnets']);
		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}

		$subnets = [];

		foreach ($response->getData() as $subnet)
		{
			$subnets[] = new Subnet($subnet);
		}

		return $subnets;
	}

	public function getAddresses()
	{
		$response = $this->_get([$this->getId(), 'addresses']);
		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}

		$addresses = [];

		foreach ($response->getData() as $address)
		{
			$addresses[] = new Address($address);
		}

		return $addresses;
	}

	public function getId(): int
	{
		return $this->id;
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
	 * @return Device
	 */
	public function setHostname(string $hostname)
	{
		$this->hostname = $hostname;

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
	 * @return Device
	 */
	public function setIP(string $ip)
	{
		$this->ip = $ip;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 *
	 * @return Device
	 */
	public function setType($type)
	{
		$this->type = $type;

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
	 * @return Device
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @param bool|null $asObject
	 *
	 * @return array
	 */
	public function getSections(bool $asObject = null)
	{
		$sections = [];
		foreach ($this->sections as $section)
		{
			$sections[] = self::getAsObjectOrID($section, Section::class, $asObject);
		}
		$this->sections = $sections;

		return $this->sections;
	}

	/**
	 * @param mixed $sections
	 *
	 * @return Device
	 */
	public function setSections($sections)
	{
		$this->sections = $sections;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRack(): string
	{
		return $this->rack;
	}

	/**
	 * @param string $rack
	 *
	 * @return Device
	 */
	public function setRack(string $rack)
	{
		$this->rack = $rack;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRackStart(): string
	{
		return $this->rack_start;
	}

	/**
	 * @param string $rack_start
	 *
	 * @return Device
	 */
	public function setRackStart(string $rack_start)
	{
		$this->rack_start = $rack_start;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRackSize(): string
	{
		return $this->rack_size;
	}

	/**
	 * @param string $rack_size
	 *
	 * @return Device
	 */
	public function setRackSize(string $rack_size)
	{
		$this->rack_size = $rack_size;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->location;
	}

	/**
	 * @param string $location
	 *
	 * @return Device
	 */
	public function setLocation(string $location)
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