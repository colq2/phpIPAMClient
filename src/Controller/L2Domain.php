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

	protected static function transformParamsToIDs(array $params)
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

	public static function getByID(int $id)
	{
		return new L2Domain(self::_getStatic([$id])->getData());
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

	public static function post(array $params)
	{
		//Params that could be converted from objects to id
		$params   = self::transformParamsToIDs($params);
		$response = self::_postStatic([], $params);
		$id       = $response->getBody()['id'];

		return Subnet::getByID($id);
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
	 * @return string
	 */
	public function getEditDate(): string
	{
		return $this->editDate;
	}
}