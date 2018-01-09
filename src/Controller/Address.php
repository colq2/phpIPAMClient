<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 09.01.18
 * Time: 11:34
 */

namespace colq2\PhpIPAMClient\Controller;


class Address extends BaseController
{

	protected static $controllerName = 'addresses';

	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	protected static function transformParamsToIDs(array $params)
	{
		// TODO: Implement transformParamsToIDs() method.
	}
}