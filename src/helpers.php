<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 17:17
 */

namespace PhpIPAMClient;

//TODO: if there is a port given the 'https' is quatsch
use PhpIPAMClient\Connection\Connection;

function phpipamMakeURL($url, $scheme = 'https://'): string
{
//	$url = strtolower($url);
	$url = trim($url);
	// remove something like //www. to www.
	$url = preg_replace("/^\/+/", "", $url);
	//Set https if nothing is given
	$url = parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
	//Add last slash if not set
	$url = phpipamAddLastSlash($url);
	//check if add the end is api/ given
	if (substr($url, -4) !== 'api/')
	{
		$url .= 'api/';
	}

	return $url;
}

function phpipamCheckSSL(string $url)
{
	// Create a stream context
	$stream = stream_context_create(array("ssl" => array("capture_peer_cert" => true)));
	// Bind the resource 'https://www.example.com' to $stream
	$read = fopen($url, "rb", false, $stream);
	// Get stream parameters
	$params = stream_context_get_params($read);
	// Check that SSL certificate is not null
	// $cert should be for example "resource(4) of type (OpenSSL X.509)"
	$cert = $params["options"]["ssl"]["peer_certificate"];

	return (!is_null($cert)) ? true : false;
}

function phpipamAddLastSlash(string $value): string
{
	if (substr($value, -1) !== '/')
	{
		$value .= '/';
	}

	return $value;
}

function phpipamConnection()
{
	return Connection::getInstance();
}