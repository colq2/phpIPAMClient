<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 06.01.18
 * Time: 17:17
 */

namespace colq2\PhpIPAMClient;

use colq2\PhpIPAMClient\Connection\Connection;

function phpipamMakeURL($url, $scheme = 'https://'): string
{
	$url = trim($url);
	// Remove all to // like http:// or https://
	$url = preg_replace("/^.+\/\//", "", $url);
	//Set scheme
//	$url = parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
	$url = $scheme . $url;
	//Add last slash if not set
	$url = phpipamAddLastSlash($url);
	//check if add the end is api/ given
	if (substr($url, -4) !== 'api/')
	{
		$url .= 'api/';
	}

	return $url;
}

function phpipamAddLastSlash(string $value): string
{
	if (substr($value, -1) !== '/')
	{
		$value .= '/';
	}

	return $value;
}

function phpipamConnection(): Connection
{
	return Connection::getInstance();
}