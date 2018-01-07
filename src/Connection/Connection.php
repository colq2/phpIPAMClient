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
use function PhpIPAMClient\addLastSlash;
use function PhpIPAMClient\makeURL;

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

	/**
	 * HTTP error codes for responses
	 *
	 * @var mixed
	 * @access public
	 */
	public $error_codes = array(
		// OK
		200 => "OK",
		201 => "Created",
		202 => "Accepted",
		204 => "No Content",
		// Client errors
		400 => "Bad Request",
		401 => "Unauthorized",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Method Not Allowed",
		415 => "Unsupported Media Type",
		// Server errors
		500 => "Internal Server Error",
		501 => "Not Implemented",
		503 => "Service Unavailable",
		505 => "HTTP Version Not Supported",
		511 => "Network Authentication Required"
	);

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

	public static function initializeConnection(string $url, string $app_name, string $username, string $password, string $method = 'ssl', string $apiKey = null): Connection
	{
		self::$connection = new Connection($url, $app_name, $username, $password, $apiKey, $method);

		return self::getInstance();
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

	private function setUrl(string $url)
	{
		//Make the url
		$url = makeURL($url);

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