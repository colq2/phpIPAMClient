<?php

use colq2\PhpIPAMClient\Connection\Connection;
use colq2\PhpIPAMClient\PhpIPAMClient;

require_once __DIR__ . '/../vendor/autoload.php';

for ($j = 0; $j < 10; $j++)
{
	$client = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testApp', 'admin', 'eRmPE4t1BXnXjd47bbkhPsSZpixXK3_Fg-v2xg1nmVmYg3QpBGtoUNvn9U5vUbv', '', Connection::SECURITY_METHOD_SSL);

	//Start timer
	$time_start = microtime(true);
	for ($i = 0; $i < 100; $i++)
	{
		$subnets = \colq2\PhpIPAMClient\Controller\Subnet::getAll();
	}
	$time_stop = microtime(true);

	$time = $time_stop - $time_start;
	echo "SSL: $time seconds.\n";


//Test crypt
	$app_key = '8048ca3c72cdf8200c74edd0a71120bd';
	$client  = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testAppCrypt', '', '', $app_key, Connection::SECURITY_METHOD_CRYPT);

//Start timer
	$time_start = microtime(true);
	for ($i = 0; $i < 100; $i++)
	{
		$subnets = \colq2\PhpIPAMClient\Controller\Subnet::getAll();
	}
	$time_stop = microtime(true);

	$time = $time_stop - $time_start;
	echo "Crypt: $time seconds.\n";

	//Test crypt and ssl
	$app_key = '8048ca3c72cdf8200c74edd0a71120bd';
	$client  = new PhpIPAMClient('https://phpipam.o-wycisk.de/api', 'testAppCrypt', '', '', $app_key, Connection::SECURITY_METHOD_BOTH);

//Start timer
	$time_start = microtime(true);
	for ($i = 0; $i < 100; $i++)
	{
		$subnets = \colq2\PhpIPAMClient\Controller\Subnet::getAll();
	}
	$time_stop = microtime(true);

	$time = $time_stop - $time_start;
	echo "Crypt: $time seconds.\n";
}