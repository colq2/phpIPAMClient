<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:34
 */

namespace colq2\PhpIPAMClient\Controller;


class VLAN extends BaseController
{

	protected static $controllerName = 'vlan';

	protected $vlanId;
	protected $domainId;
	protected $name;
	protected $number;
	protected $description;
	protected $editDate;

	protected static function transformParamsToIDs(array $params): array
	{
		$params = self::getIDFromParams($params, 'domainId', ['domainID', 'domain'], L2Domain::class);

		return $params;
	}

	public function getSubnets()
	{
		$response = $this->_get([$this->id, 'subnets']);
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

	public function getSubnetsInSection($section)
	{
		if ($section instanceof Section)
		{
			$section = $section->getId();
		}

		$response = $this->_get([$this->id, 'subnets', $section]);

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

	public function getCustomFields()
	{
		return $this->_get([$this->id, 'custom_fields'])->getData();
	}

	public function getSearch(int $number)
	{
		$response = $this->_get([$this->id, 'search', $number]);

		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}

		$vlans = [];

		foreach ($response->getData() as $vlan)
		{
			$vlans[] = new VLAN($vlan);
		}

		return $vlans;
	}

	public function delete()
	{
		return $this->_delete([], ['vlanId' => $this->getId()])->isSuccess();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->vlanId;
	}

	/**
	 * @return int|L2Domain|null
	 */
	public function getDomainId()
	{
		return self::getAsObjectOrID($this->domainId, 'domainId', ['domainID', 'domain'], L2Domain::class);
	}

	/**
	 * @param int|L2Domain|null $domainId
	 *
	 * @return VLAN
	 */
	public function setDomainId($domainId)
	{
		$this->domainId = $domainId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return VLAN
	 */
	public function setName(string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * @param int $number
	 *
	 * @return VLAN
	 */
	public function setNumber(int $number)
	{
		$this->number = $number;

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
	 * @return VLAN
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

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