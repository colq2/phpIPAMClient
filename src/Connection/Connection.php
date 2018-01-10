<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 15:46
 */

namespace colq2\PhpIPAMClient\Connection;

use colq2\PhpIPAMClient\Exception\PhpIPAMException;
use colq2\PhpIPAMClient\Exception\PhpIPAMRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Respect\Validation\Rules\DateTest;
use Respect\Validation\Validator;
use function colq2\PhpIPAMClient\phpipamAddLastSlash;
use function colq2\PhpIPAMClient\phpipamMakeURL;

class Connection
{
	const SECURITY_METHOD_SSL = 'ssl';
	const SECURITY_METHOD_CRYPT = 'crypt';
	const SECURITY_METHOD_BOTH = 'ssl|crypt';
	protected $token;
	protected $tokenExpires;

	protected $url;
	protected $appID;
	protected $fullURL;
	private $username;
	private $password;
	private $apiKey;

	protected $possibleSecurityMethods = [self::SECURITY_METHOD_SSL, self::SECURITY_METHOD_CRYPT, self::SECURITY_METHOD_BOTH];
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
	 * @throws PhpIPAMRequestException
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
				try
				{
					$this->login();
				}
				catch (RequestException $e)
				{
					$response = new Response($e->getResponse());
					throw new PhpIPAMRequestException($response, $response->getMessage());
				}
				break;

			case self::SECURITY_METHOD_CRYPT:
				$this->setApiKey($apiKey);
				break;
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
		$method     = strtolower($method);
		$controller = strtolower($controller);


		switch ($this->securityMethod)
		{
			case Connection::SECURITY_METHOD_SSL:
				return $this->callSSL($method, $controller, $identifier, $params);
				break;

			case Connection::SECURITY_METHOD_CRYPT:
			case Connection::SECURITY_METHOD_BOTH:
				return $this->callCrypt($method, $controller, $identifier, $params);
				break;

			default:
				throw new PhpIPAMException('This Security method is not allowed.');
		}
	}

	protected function callSSL(string $method, string $controller, array $identifier = array(), array $params = array()): Response
	{
		//Check if token is expired
		$this->checkToken();
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

		try
		{
			$response = $client->$method($url, [
				'headers' => [
					'phpipam-token' => $this->token
				],
				'body'    => http_build_query($params),
				'verify'  => false
			]);

			return new Response($response);
		}
		catch (RequestException $e)
		{
			$response = new Response($e->getResponse());
			throw new PhpIPAMRequestException($response, $response->getMessage());
		}
	}

	protected function callCrypt(string $method, string $controller, array $identifier = array(), array $params = array()): Response
	{

		//To encrypt we need to use openssl, 'aes-128-ecb' and OPENSSL_RAW_DATA
		//base64_encode(openssl_encrypt($str, 'aes-128-ecb', $secret, OPENSSL_RAW_DATA));
		//Server side we need to change $$aes_compliant_crypt to true to use this encryption

		//Generate $param array
		$identifier_arr = [];
		$i              = 0;
		foreach ($identifier as $item)
		{
			$i++;
			if ($i === 1)
			{
				$identifier_arr['id'] = $item;
			}
			else
			{
				$identifier_arr['id' . $i] = $item;
			}
		}
		$params               = array_merge($params, $identifier_arr);
		$params['controller'] = $controller;
		//encrypt it
		$cipher_method     = 'AES-256-OFB';
		$size              = openssl_cipher_iv_length($cipher_method);
		$iv                = openssl_random_pseudo_bytes($size);
		$json_params       = json_encode($params);
		$crypt_params      = openssl_encrypt($json_params, $cipher_method, $this->apiKey, OPENSSL_RAW_DATA, $iv);
		$encrypted_request = urlencode(base64_encode($iv . $crypt_params));

		$url = $this->url . '?app_id=' . $this->appID . '&enc_request=' . $encrypted_request;
		//generate client and sent request
		$client = new Client();

		$response = $client->$method($url, [
			'headers' => [
//				'CONTENT-TYPE' => 'application/x-www-form-urlencoded'
				'CIPHER' => 'openssl'
			],
		]);

		return new Response($response);
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
		if (is_null(self::$connection))
		{
			throw new PhpIPAMException("Connection is not established.");
		}
		else
		{
			return self::$connection;
		}
	}

	private function setUrl(string $url)
	{
		//Ensure that url is https if ssl is used
		switch ($this->securityMethod)
		{
			case Connection::SECURITY_METHOD_SSL:
			case Connection::SECURITY_METHOD_BOTH:
				$url = phpipamMakeURL($url, 'https://');
				break;

			case Connection::SECURITY_METHOD_CRYPT:
				$url = phpipamMakeURL($url, 'http://');
				break;

		}

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
		else
		{
			throw new PhpIPAMException('Invalid security method.');
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

	public function getToken()
	{
		return $this->token;
	}

	public function getTokenExpires()
	{
		return $this->tokenExpires;
	}

	protected function checkToken()
	{
		$date_now   = new \DateTime();
		$date_token = new \DateTime($this->tokenExpires);
		if ($date_now > $date_token)
		{
			//Token expired, lets relogin
			$this->login();
		}
	}

	public function __destruct()
	{
		try
		{
			$connection = self::getInstance();
			$connection->call('delete', 'user');
		}
		catch (PhpIPAMException $e)
		{
		}
	}
}