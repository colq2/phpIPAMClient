<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:46
 */

namespace PhpIPAM\Connection;

use function PhpIPAM\addLastSlash;
use function PhpIPAM\makeURL;
use PhpIPAM\Exception\PhpIPAMException;
use Respect\Validation\Validator;

class Connection
{
	const SECURITY_METHOD_SSL = 'ssl';
	const SECURITY_METHOD_CRYPT = 'crypt';

	protected $token;
	protected $token_expires;

	protected $url;
	protected $appID;
	protected $fullURL;
	private $username;
	private $password;
	private $apiKey;
	
	//TODO implement crypt method
	protected $possibleSecurityMethods = [self::SECURITY_METHOD_SSL, self::SECURITY_METHOD_CRYPT];
	protected $securityMethod = self::SECURITY_METHOD_SSL;

	private static $connection;

	private function __construct(string $url, string $appID, string $username, string $password, string $securityMethod = 'ssl', string $apiKey = null)
	{
		$this->setSecurityMethod($securityMethod);
		$this->setUrl($url);
		$this->setAppID($appID);
		
		switch ($this->securityMethod){
			case self::SECURITY_METHOD_SSL:
				$this->generateFullURL();
				break;

			case self::SECURITY_METHOD_CRYPT:
				$this->setApiKey($apiKey);
				break;
		}
	}

	private function setUrl(string $url)
	{
		//Make the url
		$url = makeURL($url);

		//Validate url
		if (!Validator::url()->validate($url))
		{
			throw new PhpIPAMException('The given url is corrupted.');
		};
	}

	private function setAppID(string $appID)
	{
		//Validate -> app_id have to he between 2 and 12 chars and alphanumeric
		if (Validator::length(3, 12)->validate($appID) && Validator::alnum()->validate($appID))
		{
			$this->appID = $appID;
		}
		else
		{
			throw new PhpIPAMException('Invalid app id');
		}
	}

	private function generateFullURL()
	{
		$this->fullURL = $this->url . addLastSlash($this->appID);
	}

	private function setAuth(string $username, string $password)
	{
		if (Validator::notEmpty()->validate($username) && Validator::notEmpty()->validate($password))
		{
			$this->username = $username;
			$this->password = $password;
		}
		else
		{
			throw new PhpIPAMException('Username and password can\'t be empty.');
		}
	}

	private function setSecurityMethod(string $securityMethod)
	{
		if(Validator::contains($securityMethod)->validate($this->possibleSecurityMethods)){
			$this->securityMethod = $securityMethod;
		}
	}

	private function setApiKey(string $apiKey){
		if(Validator::notEmpty()->validate($apiKey)){
			$this->apiKey = $apiKey;
		}else{
			throw new PhpIPAMException('Invalid api key.');
		}
	}
	
	public static function initializeConnection(string $url, string $app_name, string $username, string $password, string $method = 'ssl', string $apiKey = null): Connection
	{
		self::$connection = new Connection($url, $app_name, $username, $password, $apiKey, $method);
	}

	public static function getInstance(): Connection
	{
		if (self::$connection == null)
		{
			throw new PhpIPAMException('There is no connection.');
		}
		else
		{
			return self::$connection;
		}
	}
}