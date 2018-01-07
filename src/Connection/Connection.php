<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:46
 */

namespace PhpIPAMClient\Connection;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PhpIPAMClient\Exception\PhpIPAMException;
use Respect\Validation\Validator;
use function PhpIPAMClient\phpipamAddLastSlash;
use function PhpIPAMClient\phpipamMakeURL;

class Connection
{
	const SECURITY_METHOD_SSL = 'ssl';
	const SECURITY_METHOD_CRYPT = 'crypt';

	protected $token;
	protected $tokenExpires;

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

	/**
	 * Connection constructor.
	 *
	 * @param string      $url
	 * @param string      $appID
	 * @param string      $username
	 * @param string      $password
	 * @param string      $securityMethod
	 * @param string|null $apiKey
	 *
	 * @throws PhpIPAMException
	 */
	private function __construct(string $url, string $appID, string $username, string $password, string $securityMethod = 'ssl', string $apiKey = null)
	{
		$this->setSecurityMethod($securityMethod);
		$this->setUrl($url);
		$this->setAppID($appID);

		$this->fullURL = $this->url . $this->appID . '/';
		switch ($this->securityMethod)
		{
			case self::SECURITY_METHOD_SSL:
				$this->setAuth($username, $password);
				$this->generateFullURL();
				break;

			case self::SECURITY_METHOD_CRYPT:
				$this->setApiKey($apiKey);
				break;
		}

		try
		{
			$this->login();
		}
		catch (RequestException $e)
		{
			//TODO nice exeption handling
			throw new PhpIPAMException($e->getMessage());
//			dd($e->getCode());
//			$body = (string) $e->getResponse()->getBody();
//			dd((string)$e->getResponse()->getBody());
		}
	}

	protected function login()
	{
		$client = new Client();

		//Login is on user controller
		$url         = $this->fullURL . 'user/';
		$credentials = base64_encode($this->username . ':' . $this->password);

		$response = $client->post($url, [
			'headers' => [
				'Authorization' => 'Basic ' . $credentials
			],
			'timeout' => 60,
			'verify'  => false
		]);

		$response           = new Response($response);
		$this->token        = $response->getData()['token'];
		$this->tokenExpires = $response->getData()['expires'];
	}

	public function call(string $method, string $controller, array $identifier = array(), array $params = array())
	{
		//TODO ensure that method and controller are valid
		$method     = strtolower($method);
		$controller = strtolower($controller);


		switch ($this->securityMethod)
		{
			case Connection::SECURITY_METHOD_SSL:
				return $this->callSSL($method, $controller, $identifier, $params);
				break;

			case Connection::SECURITY_METHOD_CRYPT:
				return $this->callCrypt($method, $controller, $identifier, $params);
				break;

			default:
				throw new PhpIPAMException('This Security method is not allowed.');
		}
	}

	protected function callSSL(string $method, string $controller, array $identifier = array(), array $params = array()): Response
	{
		//TODO ensure that it is a ssl connection

		//TODO check that token is not expired
		$client = new Client();
		//Controller could be empty for the options call
		if (empty($controller))
		{
			$url = $this->fullURL;
		}
		else
		{
			$url = $this->fullURL . $controller . '/' . implode('/', $identifier);
		}
		$url = phpipamAddLastSlash($url);

//		dd($this->token);
		$response = $client->$method($url, [
			'headers' => [
				'phpipam-token' => $this->token
			],
			'body'    => \GuzzleHttp\json_encode($params),
			'verify'  => false
		]);

		return new Response($response);
	}

	protected function callCrypt(string $method, string $controller, array $identifier = array(), array $params = array()): Response
	{
		return null;
	}

	public static function callStatic(string $method, string $controller, array $identifier = array(), array $params = array())
	{
		$connection = self::getInstance();

		return $connection->call($method, $controller, $identifier, $params);
	}

	public static function initializeConnection(string $url, string $app_name, string $username, string $password, string $method = 'ssl', string $apiKey = null): Connection
	{
		self::$connection = new Connection($url, $app_name, $username, $password, $apiKey, $method);

		return self::getInstance();
	}

	public static function getInstance(): Connection
	{
		return self::$connection;
	}

	private function setUrl(string $url)
	{
		//Make the url
		$url = phpipamMakeURL($url);

		//Validate url
		if (Validator::url()->validate($url))
		{
			$this->url = $url;
		}
		else
		{
			throw new PhpIPAMException('The given url is corrupted.');
		}
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
		$this->fullURL = $this->url . phpipamAddLastSlash($this->appID);
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
		if (Validator::contains($securityMethod)->validate($this->possibleSecurityMethods))
		{
			$this->securityMethod = $securityMethod;
		}
	}

	private function setApiKey(string $apiKey)
	{
		if (Validator::notEmpty()->validate($apiKey))
		{
			$this->apiKey = $apiKey;
		}
		else
		{
			throw new PhpIPAMException('Invalid api key.');
		}
	}
}