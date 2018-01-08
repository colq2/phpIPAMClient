<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 22:18
 */

namespace colq2\PhpIPAMClient\Controller;


use colq2\PhpIPAMClient\Exception\PhpIPAMException;
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


	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	public static function getAll()
	{
		$response = self::_getStatic(['all']);
		dd($response);
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
		//TODO Implement
		$response = self::_postStatic([], $params);
		$id       = $response->getBody()['id'];

		return Subnet::getByID($id);
		dd($response);
		//A subnet needs a section id and a subnet and a mask
		if (array_key_exists('subnet', $params) or array_key_exists('sectionId', $params))
		{
			self::_postStatic([], $params);
		}
		else
		{
			throw new PhpIPAMException('Name is not given. Provide at least a name for the section.');
		}

		//Section is created lets get it
		return Section::getByName($params['name']);
	}


	public function postFirstSubnet(int $mask): Subnet
	{
		$response = $this->_post([$this->id, 'first_subnet', $mask]);
		$id       = $response->getBody()['id'];

		return Subnet::getByID($id);
	}

	/**
	 * Updates Subnet
	 * You can provide an array with the new params or use the setter methods.
	 *
	 * @param array $params Array with the params that should be set
	 *
	 * @return bool
	 */
	public function patch(array $params = array())
	{
		$this->setParams($params);
		$params = $this->getParams();

		$response = self::_patch([], $params);

		return $response->isSuccess();
	}

	/**
	 * Resizes subnet to new mask
	 *
	 * @param int $mask New mask
	 *
	 * @return bool
	 * @throws PhpIPAMRequestException
	 */
	public function patchResize(int $mask)
	{
		try{
			$response = $this->_patch([$this->id, 'resize'], ['mask' => $mask]);
		}catch (PhpIPAMRequestException $e){
			//Catch if the changes doesn't change anything
			if($e->getMessage() === "New network is same as old network"){
				return true;
			}else{
				throw $e;
			}
		}
		if($response->isSuccess()){
			//Update the this object
			$section = Subnet::getByID($this->id);
			$this->setParams($section->getParams());
			return true;
		}
	}

	/**
	 * Splits subnet to smaller subnets
	 *
	 * @param int $number       Number in how much subnets this should be split
	 *
	 * @return bool
	 */
	public function patchSplit(int $number): bool
	{
		try{
			$response = $this->_patch([$this->id, 'split'], ['number' => $number]);
		}catch (PhpIPAMRequestException $e)
		{
			return false;
		}

		if($response->isSuccess()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Deletes Subnet
	 *
	 * @return bool
	 */
	public function delete(): bool
	{
		return $this->_delete([$this->id])->isSuccess();
	}

}