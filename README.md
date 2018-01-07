# phpIPAMClient

This will be a nice api client for phpIPAM. It is in work, so just take a coffee chill some days :)

## Installation
You can install this package through composer:

`composer require colq2/phpipam-client`

## Usage

You can use this client in two ways.
1. Use the controller classes and let the client manage the most things for you
2. Use the call methode

### Initialize connection

`$client = new colq2\PhpIPAMClient('1.2.3.4', 'myApp', 'name', 'password', 'apikey');`

or

`colq2\Connection\Connection::initializeConnection('1.2.3.4', 'myApp', 'name', 'password', 'apikey');`

### First Way

I will use the sections controller as Example.
Every method from the api is implemented in the controller class.

#### Get all sections

To get all sections use the getAll method. This return an array with all sections as `colq2\Controller\Section` instances.

`$sections = colq2\Controller\Section::getAll()`

#### Get section by id or name
`$section = colq2\Controller\Section::getByID('1')`

`$section = colq2\Controller\Section::getByName('name')`

#### Create a section
You can use the construct or the create method.

With the construct:

`$section = new Section(['params_array'])`

But you need to use the patch method to safe it:

`$section->patch()`

With the create method:

`$section = colq2\Controller\Section::create(['params_array'])`

Here you don't need to safe, this will create instantly a section on the server.

### Second Way
`$response = $client->call('method', 'controller', ['identifier_array'], ['param_array']);`

This will return an instance of `colq2\Connection\Response`.
You can access the data using the getData method.

`$data = $response->getData()`

To get the whole response body use the getBody method.

`$body = $response->getBody()`

## License
MIT