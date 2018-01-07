<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 13:11
 */

namespace PhpIPAMClient\Controller;

use function PhpIPAMClient\phpipamConnection;

abstract class BaseController
{
	protected $name;

	public static function getStatic()
	{

	}

	public function get(array $identifer = array(), array $params = array())
	{
		phpipamConnection()->call('get', $this->name, $identifer, $params);
	}

	public function post()
	{

	}
}