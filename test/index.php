<?php
/**
 * Created by PhpStorm.
 * User: sonyon
 * Date: 08.01.18
 * Time: 13:57
 */

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

require_once __DIR__ . '/../vendor/autoload.php';
//$url = makeURL('10.0.0.5');

//echo $url;
//echo "\n";
//echo checkSSL($url);

//$test = ['id', 12, 'all'];
//dd(implode('/', $test));
$app_key = '8048ca3c72cdf8200c74edd0a71120bd';
$client = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testAppCrypt','' , '', $app_key, Connection::SECURITY_METHOD_CRYPT);
//$client  = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testApp', 'admin', 'eRmPE4t1BXnXjd47bbkhPsSZpixXK3_Fg-v2xg1nmVmYg3QpBGtoUNvn9U5vUbv', '', Connection::SECURITY_METHOD_SSL);
dd($client->getAllControllers());

//$section = Section::create(['name'  => 'testSection']);
//$section = Section::getByName('testSection');
//$section->delete();
//$section->patch();
//dd($section);

//$sections = Section::getAll();
//dd($sections);

//try{
//	$custom_fields = Section::getCustomFields();
//	dd($custom_fields);
//}catch (PhpIPAMRequestException $e){
//	dd($e);
//}

try
{
//	$subnets = Subnet::getByID(1);
//	dd($subnets->getUsage());
//	$subnet = Subnet::post(['subnet' => '10.0.0.0', 'mask' => 30, 'sectionId' => 2]);
//	dd(Subnet::getAll());
//	$folder = Folder::getByID(2);
//	dd($folder);

//	$subnet = Subnet::getByID(10);
//	$secondSubnet = $subnet->postFirstSubnet(30);
//	dd($secondSubnet);

	//Create subnet in folder
//	$folder = Folder::getByID(5);
//	dd($folder->postFirstSubnet(30));


	//Test resize and split
//	$subnet = Subnet::getByID(10);
//	$subnet->patchResize(16);
//	dd($subnet);

//	$subnet = Subnet::getByID(10);
//	dd($subnet);
//	dd($subnet->getSlaves());
//	dd($subnet->getAddresses());
//	$subnet = Subnet::getByID(10);
//	$subnet->patchSplit(2);
//	$addr = $subnet->getAddressesIP('10.0.0.2');
//	dd($subnet->getSlavesRecursive());
	//	$subnet->patchSplit(2);


//	$class = Section::class;
//
//	$section = call_user_func(array($class, 'getByID'),2);
//
//	$section = Section::getByID(2);
//	$subnets = $section->getAllSubnets();
//	dd($subnets);
}
catch (PhpIPAMRequestException $e)
{
	dd($e);
}


/**
 * Address controller
 */

try
{
//	$address = Address::getByID(12);
//	dd($address);
//	$subnet = Subnet::getByID(16);
//	dd(Address::getFirstFree(16));


}
catch (PhpIPAMRequestException $e)
{
	dd($e);
}

/**
 * L2Domain controller
 */

try
{
//	dd(VLAN::getAll());
//	dd(L2Domain::getByID(1)->getVLANs());
//	dd(L2Domain::getAll());
}
catch (PhpIPAMRequestException $e)
{
	dd($e);
}

try
{
//	dd(VRF::getAll());
}
catch (PhpIPAMRequestException $e)
{
	dd($e);
}

/*
 * Devices
 */

try
{
//	dd(VLAN::getByID(1));
//	dd(Device::getAll());
//	dd(Device::getByID(1)->getAddresses());
}
catch (\Exception $e)
{
	dd($e);
}

try
{
//	$section = Section::getByID(1);
//	$section->setDescription();
//	$section->setName('SectionNeme');
//	$section->patch();
//	dd($client->getAllControllers());
}
catch (PhpIPAMRequestException $e)
{
	dd($e);

	$response = $e->getResponse();
}

try{
	$vlan = VLAN::getByID(4);
	$vlan->delete();
	dd($vlan);
}catch (PhpIPAMException $e){
	dd($e);
}
