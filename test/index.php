<?php

namespace colq2\PhpIPAMClient;

use colq2\PhpIPAMClient\Connection\Connection;
use colq2\PhpIPAMClient\Controller\Section;
use colq2\PhpIPAMClient\Controller\Subnet;
use colq2\PhpIPAMClient\Controller\VLAN;
use colq2\PhpIPAMClient\Exception\PhpIPAMRequestException;

require_once __DIR__ . '/../vendor/autoload.php';
//$url = makeURL('10.0.0.5');

//echo $url;
//echo "\n";
//echo checkSSL($url);

//$test = ['id', 12, 'all'];
//dd(implode('/', $test));
$app_key = '';
//$client = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'AppName', 'ApiUsername', 'HereIsThePassword', '', Connection::SECURITY_METHOD_SSL);
$client = new PhpIPAMClient('phpipam.o-wycisk.de/api', 'testAppCrypt','' , '', $app_key, Connection::SECURITY_METHOD_CRYPT);


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
//	$subnet = Subnet::getByID(1);
//	dd($subnet);
//	$secondSubnet = $subnet->postFirstSubnet(30);
//	dd($secondSubnet);

//	$subnet = Subnet::post([]);
//	dd($subnet);



//	$params = [
//		'section' => Section::getByID(2),
//		'linked_subnet' => Subnet::getByID(1)
//	];
//
//	dd(Subnet::transformToIDs($params));
//	dd(Subnet::getIDFromParams($params, 'sectionId', ['section'], Section::class));
//


}
catch (PhpIPAMRequestException $e)
{
	dd($e);
}

try{
	dd(VLAN::getAll());
	$cleartext = array('JO' => 'OK', 'ZOP' => 'KEK');
	echo json_encode($cleartext)."\n";

	$encrypted_request = openssl_encrypt(json_encode($cleartext), 'aes-256-ecb', $app_key, OPENSSL_RAW_DATA);
	$encrypted_request = base64_encode($encrypted_request);
//	$encrypted_request = urlencode($encrypted_request);
	echo $encrypted_request."\n";

	$decoded = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $app_key, base64_decode($encrypted_request), MCRYPT_MODE_ECB);
	echo $decoded."\n";
}catch (PhpIPAMRequestException $e){
	dd($e);
}
