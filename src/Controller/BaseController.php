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

	protected static $controllerName = '';

	protected function _get(array $identifier = array(), array $params = array())
	{
		return static::_getStatic($identifier, $params);
	}

	protected static function _getStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('get', static::$controllerName, $identifier, $params);
	}

	protected function _post(array $identifier = array(), array $params = array())
	{
		return static::_postStatic($identifier, $params);
	}

	protected static function _postStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('post', static::$controllerName, $identifier, $params);
	}

	protected function _put(array $identifier = array(), array $params = array())
	{
		return static::_putStatic($identifier, $params);
	}

	protected static function _putStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('put', static::$controllerName, $identifier, $params);
	}

	protected function _patch(array $identifier = array(), array $params = array())
	{
		return static::_patchStatic($identifier, $params);
	}

	protected function _patchStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('patch', static::$controllerName, $identifier, $params);
	}

	protected function _delete(array $identifier = array(), array $params = array())
	{
		return static::_deleteStatic($identifier, $params);
	}

	protected function _deleteStatic(array $identifier = array(), array $params = array())
	{
		return phpipamConnection()->call('delete', static::$controllerName, $identifier, $params);
	}

}
