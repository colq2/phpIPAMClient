<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:38
 */

namespace colq2\PhpIPAMClient\Controller;


class L2Domain extends BaseController
{
	protected static $controllerName = 'l2domains';

	protected $id;
	protected $name;
	protected $description;
	protected $sections;
	protected $editDate;

	protected static function transformParamsToIDs(array $params): array
	{
		return $params;
	}

	public static function getAll()
	{
		$response = self::_getStatic();
		if (is_null($response->getData()) or empty($response->getData()))
		{
			return [];
		}
		$domains = [];

		foreach ($response->getData() as $domain)
		{
			$domains[] = new L2Domain($domain);
		}

		return $domains;
	}


	public function getVLANs()
	{
		$response = $this->_get([$this->id, 'vlans']);
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

	public function patch(array $params = array())
	{
		$this->setParams($params);
		$params = $this->getParams();

		return $this->_patch([], $params)->isSuccess();
	}

	public function delete()
	{
		return $this->_delete([$this->id])->isSuccess();
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
	 * @return L2Domain
	 */
	public function setName(string $name)
	{
		$this->name = $name;

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
	 * @return L2Domain
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
	 * @return L2Domain
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