<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:36
 */

namespace PhpIPAMClient\Controller;

use PhpIPAMClient\Exception\PhpIPAMException;

/**
 * Class Section
 * @package PhpIPAMClient\Controller
 */
class Section extends BaseController
{
	/**
	 * Name of the controller
	 * @var string
	 */
	protected static $controllerName = 'sections';

	/**
	 * Section identifier, identifies which section to work on.
	 * @var
	 */
	protected $id;
	/**
	 * Section name, unique value
	 * @var
	 */
	protected $name;
	/**
	 * Description of section
	 * @var
	 */
	protected $description;
	/**
	 * Id of master section if section is nested (default: 0)
	 * @var
	 */
	protected $masterSection;
	/**
	 * Json encoded group permissions for section groupId:permission_level (0-3)
	 * @var
	 */
	protected $permissions;
	/**
	 * Weather to check consistency for subnets and IP addresses (default: 0)
	 * @var
	 */
	protected $strictMode;
	/**
	 * Order of subnets in this section (default: subnet,asc)
	 * @var
	 */
	protected $subnetOrdering;
	/**
	 * Order of sections list display
	 * @var
	 */
	protected $order;
	/**
	 * Date of last edit (yyyy-mm-dd hh:ii:ss)
	 * @var
	 */
	protected $editDate;
	/**
	 * Show / hide VLANs in subnet list (default: 0)
	 * @var
	 */
	protected $showVLAN;
	/**
	 * Show / hide VRFs in subnet list(default: 0)
	 * @var
	 */
	protected $showVRF;
	/**
	 * Show only supernets in subnet list(default: 0)
	 * @var
	 */
	protected $showSupernetOnly;
	/**
	 * Id of NS resolver to be used for section
	 * @var
	 */
	protected $DNS;

	/**
	 * Section constructor.
	 *
	 * @param array $params
	 */
	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	/**
	 * Returns all sections
	 * @return array
	 */
	public static function getAll()
	{
		$response = self::_getStatic();
		$sections = array();
		foreach ($response->getData() as $section)
		{
			$sections[] = new Section($section);
		}

		return $sections;
	}

	/**
	 * Returns specific section
	 *
	 * @param int $id
	 *
	 * @return Section
	 */
	public static function getByID(int $id)
	{
		$response = self::_getStatic([$id]);

		return new Section($response->getData());
	}

	/**
	 * Returns all subnets in section
	 * @param int $id
	 */
	public static function getAllSubnets(int $id)
	{
		//TODO: implement
	}

	/**
	 * Returns specific section by name
	 * @param string $name
	 *
	 * @return Section
	 */
	public static function getByName(string $name)
	{
		$response = self::_getStatic([$name]);
		$section  = new Section($response->getData());

		return $section;
	}

	/**
	 *    Returns custom section fields
	 * //TODO: Custom field not supported
	 * @deprecated
	 * @return array
	 */
	public static function getCustomFields()
	{
		$response = self::_getStatic(['custom_fields']);

		return $response->getData();
	}

	/**
	 * Creates a new Section
	 *
	 * @param array $params
	 *
	 * @return Section
	 * @throws PhpIPAMException
	 */
	public static function create(array $params)
	{
		//Check if there is at all a name given
		if (array_key_exists('name', $params))
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

	/**
	 * Updates section
	 *
	 * @param array $params
	 *
	 * @return bool
	 */
	public function patch(array $params = array())
	{
		$this->setParams($params);
		$params   = $this->getParams();
		$response = parent::_patch([], $params);

		return $response->isSuccess();
	}

	/**
	 * Deletes section with all belonging subnets and addresses
	 * @return bool
	 */
	public function delete()
	{
		return parent::_delete([], ['id' => $this->getId()])->isSuccess();
	}

	/**
	 * Sets the parameter of the section from array
	 *
	 * @param array $params
	 */
	protected function setParams(array $params)
	{
		foreach ($params as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * Gets all parameter in a array
	 * @return array
	 */
	protected function getParams()
	{
		$params = get_object_vars($this);
		unset($params['controllerName']);

		return $params;
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
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return Section
	 */
	public function setName(string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return Section
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * TODO: Return section object
	 * @return int
	 */
	public function getMasterSection()
	{
		return $this->masterSection;
	}

	/**
	 * @param int $masterSection
	 *
	 * @return Section
	 */
	public function setMasterSection(int $masterSection)
	{
		$this->masterSection = $masterSection;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

	/**
	 * @param string $permissions
	 *
	 * @return Section
	 */
	public function setPermissions(string $permissions)
	{
		$this->permissions = $permissions;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getStrictMode()
	{
		return $this->strictMode;
	}

	/**
	 * @param bool $strictMode
	 *
	 * @return Section
	 */
	public function setStrictMode(bool $strictMode)
	{
		$this->strictMode = $strictMode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSubnetOrdering()
	{
		return $this->subnetOrdering;
	}

	/**
	 * @param string $subnetOrdering
	 *
	 * @return Section
	 */
	public function setSubnetOrdering(string $subnetOrdering)
	{
		$this->subnetOrdering = $subnetOrdering;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @param int $order
	 *
	 * @return Section
	 */
	public function setOrder(int $order)
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * @param string $editDate
	 *
	 * @return $this
	 */
	private function setEditDate(string $editDate)
	{
		$this->editDate = $editDate;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEditDate()
	{
		return $this->editDate;
	}


	/**
	 * @return bool
	 */
	public function getShowVLAN()
	{
		return $this->showVLAN;
	}

	/**
	 * @param bool $showVLAN
	 *
	 * @return Section
	 */
	public function setShowVLAN(bool $showVLAN)
	{
		$this->showVLAN = $showVLAN;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getShowVRF()
	{
		return $this->showVRF;
	}

	/**
	 * @param bool $showVRF
	 *
	 * @return Section
	 */
	public function setShowVRF(bool $showVRF)
	{
		$this->showVRF = $showVRF;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getShowSupernetOnly()
	{
		return $this->showSupernetOnly;
	}

	/**
	 * @param bool $showSupernetOnly
	 *
	 * @return Section
	 */
	public function setShowSupernetOnly(bool $showSupernetOnly)
	{
		$this->showSupernetOnly = $showSupernetOnly;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDNS()
	{
		return $this->DNS;
	}

	/**
	 * @param string $DNS
	 *
	 * @return Section
	 */
	public function setDNS($DNS)
	{
		$this->DNS = $DNS;

		return $this;
	}


}