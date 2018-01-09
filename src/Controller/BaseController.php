<?php
/**
 * Created by PhpStorm.
 * User: Oliver
 * Date: 07.01.2018
 * Time: 13:11
 */

namespace colq2\PhpIPAMClient\Controller;

use function colq2\PhpIPAMClient\phpipamConnection;

abstract class BaseController
{

	protected static $controllerName = '';
	public static $defaultAsObject = true;

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

		return static::transformParamsToIDs($params);
	}

	/**
	 * Method turn Objects into their id's in a params array
	 *
	 * @param array  $params       The param array
	 * @param string $key          The key which should be there at the end
	 * @param array  $possibleKeys Possible key in which the Object could stand
	 *
	 * @param        $class
	 *
	 * @return mixed
	 */
	public static function getIDFromParams(array $params, string $key, array $possibleKeys = array(), $class)
	{
		//Merge keys to one array
		$keys = array_merge($possibleKeys, [$key]);
		foreach ($keys as $k)
		{
			//check if key exists in params and if its an instance of the given class
			if (array_key_exists($k, $params) AND is_a($params[$k], $class, true))
			{
				$params[$key] = $params[$k]->getId();

				//Delete $k if it different from $key
				if ($key !== $k)
				{
					unset($params[$k]);
				}

				return $params;
			}
		}

		return $params;
	}

	/**
	 * @param      $value
	 * @param      $class
	 * @param bool|null $asObject
	 *
	 * @return object|int|null
	 */
	protected static function getAsObjectOrID($value, $class, bool $asObject)
	{
		if(is_null($asObject)){
			$asObject = call_user_func(array($class, 'getDefaultAsObjectValue'));
		}

		if (is_null($value))
		{
			return $value;
		}

		if ($asObject)
		{
			//Return as Object
			if (is_a($value, $class))
			{
				//$value is instance of class
				return $value;
			}
			else
			{
				return call_user_func(array($class, 'getByID'), $value);
			}
		}
		else
		{
			//Return ID
			if (is_a($value, $class))
			{
				return $value->getId();
			}
			else
			{
				return $value;
			}

		}
	}

	protected function getDefaultAsObjectValue(): bool
	{
		return (bool) static::$defaultAsObject;
	}

	public function setDefaultAsObjectValue(bool $asObject){
		static::$defaultAsObject = $asObject;
	}

	protected abstract static function transformParamsToIDs(array $params);
}
