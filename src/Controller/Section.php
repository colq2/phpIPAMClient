<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:36
 */

namespace PhpIPAMClient\Controller;


use PhpIPAMClient\Connection\Connection;

class Section extends BaseController
{
	protected $name = 'sections';

	public function __construct(array $params = array())
	{
		//Check if there is at all a name given
		if (array_key_exists('name', $params))
		{

		}
		else
		{
			return null;
		}
	}

	public static function getAll()
	{
		$connection = Connection::getInstance();
	}

	public static function getByID(int $id)
	{

	}

	public static function getAllSubnets(int $id)
	{

	}

	public static function getByName(string $name)
	{

	}

	public static function getCustomFields()
	{

	}


}