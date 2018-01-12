# phpIPAMClient

phpIPAM is an open-source IP address management web application which can be controlled by an api.
This is an api client for php which can be used to control phpIPAM.

Learn more:

* [phpIPAM Homepage](https://phpipam.net/)
* [phpIPAM Source](https://github.com/phpipam/phpipam)
* [phpIPAM API Docs](https://phpipam.net/api/api_documentation/)

## Installation
You can install this package through composer:

At the moment I suggest to stay at the 1.0.x-dev version:

`composer require colq2/phpipam-client:1.0.x-dev`

cause i always find some small issues and todo's that I'm fixing on this branch.

Else you can go with the current release:

`composer require colq2/phpipam-client`


## Basic Usage

You can use this client in two ways.
1. Use the controller classes and let the client manage the most things for you
2. Use the call method on the client object

Notice: I only provide secure connections to the server. The API provides two ways to do that:
* SSL
* Own encryption

Read more on [Security](#security)


### Initialize connection
`use colq2\PhpIPAMClient;`

`$client = new PhpIPAMClient('1.2.3.4', 'myApp', 'name', 'password', 'apikey');`

or

`use colq2\PhpIPAMClient\Connection\Connection;`

`Connection::initializeConnection('1.2.3.4', 'myApp', 'name', 'password', 'apikey');`

### First Way

I will use the sections controller as Example.
It's important to understand, that this api ist strong object oriented. This means, that the controllers can be used as objects that represents the objects server side. The objects provides getter and setter for their attributes. And you can set client side objects to the attribute. This client handles to conversion between Object and IDs on it's own!

Import the controller with `use colq2\PhpIPAMClient\Controller\Section;` at the top.
#### Get all sections

To get all sections use the getAll method. This return an array with all sections as `colq2\Controller\Section` instances.

`$sections = Section::getAll();`

#### Get section by id or name
`$section = Section::getByID('1');`

`$section = Section::getByName('name');`

#### Create a section
You can use the construct or the create method.

With the construct:

`$section = new Section(['params_array']);`

And use the getter and setter:

`$section->setDescription();`

You need to use the patch method to safe it:

`$section->patch();`

#### Patch a section

To patch a section use the setter on it or an array to the method. Or both!
But the array will override the given attributes.

`$section->setDescription('A cool description!');`

#### Delete a section

`$section->delete();`

### Second Way
`$response = $client->call('method', 'controller', ['identifier_array'], ['param_array']);`

This will return an instance of `colq2\Connection\Response`.
You can access the data using the getData method.

`$data = $response->getData()`

To get the whole response body use the getBody method.

`$body = $response->getBody()`

Learn more on [Response Object](docs/response.md)

## Controller

#### Notice

This docs are not ready yet. I'm working on it. Read the phpIPAM Api docs. I mapped the request url's to name. For example:
* GET   /api/my_app/subnets/{id}/ => $subnet = Subnet::getById($id);
* GET   /api/my_app/subnets/{id}/usage/ => $subnet->getUsage();
* PATCH /api/my_app/subnets/{id}/ => Subnet::patch();

For more detailed information for all methods and controller look at [phpIPAM website](https://phpipam.net) and at the following controller documentation:

* [Sections](docs/section.md)
* [Subnets](docs/subnet.md)
* [Folders](docs/folder.md)
* [VLAN](docs/vlan.md)
* [Address](docs/address.md)
* [L2 Domain](docs/L2Domain.md)
* [VRF](docs/vrf.md)
* [Device](docs/device.md)

## Security

The last parameter a of the construct is a value of the following constants:

`use colq2\PhpIPAMClient\Connection\Connection`
* `Connection::SECURITY_METHOD_SSL`
* `Connection::SECURITY_METHOD_CRYPT`
* `Connection::SECURITY_METHOD_BOTH`

I recommend to use the SLL method. For this you need a https connection to your Server!

If you don't have the possibility to use SSL use crypt. You need to provide the API Key from the Backend.
Furthermore you need to change one file serverside cause phpipam uses `mcrypt` but this method is deprecated. I am using `openssl`. 

[index.php](https://github.com/colq2/phpipam/blob/master/api/index.php)

It is on my repo but i will stay in contact with the developer of phpipam so that we can find together a solution for that. 

`$client = new PhpIPAMClient('www.example.com/phpipam', 'myApp','' , '', 'your app key, Connection::SECURITY_METHOD_CRYPT)`

And also if you are paranoid security fanatics (like me) use the `Connection::SECURITY_METHOD_BOTH`

`$client = new PhpIPAMClient('https://www.example.com/phpipam', 'myApp','' , '', 'your app key, Connection::SECURITY_METHOD_BOTH)`

## Exceptions

There are two types of Exceptions:
* `PhpIPAMException`

This will be thrown when something went wrong with the api client.

* `PhpIPAMRequestException`

This will be thrown if a request fails. This Exception saves the response so you can lookup the error:
`$response = $e->getResponse();`

Read more on [Response Object](docs/response.md)
## License
MIT
