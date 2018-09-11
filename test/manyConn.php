<?php

namespace colq2\PhpIPAMClient;

use colq2\PhpIPAMClient\Connection\Connection;
use colq2\PhpIPAMClient\Controller\Address;
use colq2\PhpIPAMClient\Controller\Device;
use colq2\PhpIPAMClient\Controller\Folder;
use colq2\PhpIPAMClient\Controller\L2Domain;
use colq2\PhpIPAMClient\Controller\Section;
use colq2\PhpIPAMClient\Controller\Subnet;
use colq2\PhpIPAMClient\Controller\VLAN;
use colq2\PhpIPAMClient\Controller\VRF;
use colq2\PhpIPAMClient\Exception\PhpIPAMException;
use colq2\PhpIPAMClient\Exception\PhpIPAMRequestException;
use colq2\PhpIPAMClient\PhpIPAMClient;

require_once __DIR__ . '/../vendor/autoload.php';

$connections = [];

$app_key = '8048ca3c72cdf8200c74edd0a71120bd';

for ($i = 0; $i < 100; $i++)
{
//	$connections[] = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testApp', 'admin', 'eRmPE4t1BXnXjd47bbkhPsSZpixXK3_Fg-v2xg1nmVmYg3QpBGtoUNvn9U5vUbv', '', Connection::SECURITY_METHOD_SSL);

}

$i = 0;
foreach ($connections as $connection)
{
	if ($connection instanceof PhpIPAMClient) ;

	echo "Conn $i : " . $connection->getToken() . " " . $connection->getTokenExpires() . "\n";
	echo "calling method... \n";
	$connection->getAllUsers();
	$i++;
}

$client1 = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testApp', 'admin', 'eRmPE4t1BXnXjd47bbkhPsSZpixXK3_Fg-v2xg1nmVmYg3QpBGtoUNvn9U5vUbv', '', Connection::SECURITY_METHOD_SSL);
$client2 = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testApp', 'admin', 'eRmPE4t1BXnXjd47bbkhPsSZpixXK3_Fg-v2xg1nmVmYg3QpBGtoUNvn9U5vUbv', '', Connection::SECURITY_METHOD_SSL);

echo "Calling client 1\n";
$client1->getAllUsers();
echo "Unset client1\n";
unset($client1);
echo "Sleep...";
sleep(2);
echo "Calling client2\n";
$client2->getAllUsers();