<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:39
 */

namespace colq2\PhpIPAMClient\Controller;


class VRF extends BaseController
{

	protected static $controllerName = 'vrf';

	protected $id;
	protected $name;
	protected $rd;
	protected $description;
	protected $sections;
	protected $editDate;

	protected static function transformParamsToIDs(array $params): array
	{
		$params['sections'] = self::convertSectionsToID($params['sections']);

		return $params;
	}

	public static function getAll()
	{
		$response = self::_getStatic();
		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}
		$vrfs = [];

		foreach ($response->getData() as $vrf)
		{
			$vrfs[] = new VRF($vrf);
		}

		return $vrfs;
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

	public static function getCustomFields()
	{
		return self::_getStatic(['custom_fields'])->getData();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return VRF
	 */
	public function setName(string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRd(): string
	{
		return $this->rd;
	}

	/**
	 * @param string $rd
	 *
	 * @return VRF
	 */
	public function setRd(string $rd)
	{
		$this->rd = $rd;

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
	 * @return VRF
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
	 * @param array $sections
	 *
	 * @return VRF
	 */
	public function setSections(array $sections)
	{
		$this->sections = $sections;

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