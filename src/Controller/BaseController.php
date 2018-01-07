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

	protected static $name;

	public function get(array $identifier = array(), array $params = array())
	{
		return self::getStatic($identifier, $params);
	}

	public static function getStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('get', self::name, $identifier, $params);
	}

	public function post(array $identifier = array(), array $params = array())
	{
		return self::postStatic($identifier, $params);
	}

	public static function postStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('post', self::name, $identifier, $params);
	}

	public function put(array $identifier = array(), array $params = array())
	{
		return self::putStatic($identifier, $params);
	}

	public static function putStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('put', self::name, $identifier, $params);
	}

	public function patch(array $identifier = array(), array $params = array())
	{
		return self::putStatic($identifier, $params);
	}

	public function patchStatic(array $identifier = array(), array $params = array())
	{
		return self::putStatic($identifier, $params);
	}

	public function delete(array $identifier = array(), array $params = array())
	{
		return self::deleteStatic($identifier, $params);
	}

	public function deleteStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('delete', self::name, $identifier, $params);
	}

}
