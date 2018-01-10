# Response

The `colq2\PhpIPAMClient\Connection\Respose` Object is used to handle the Response from the server.
Also this is the return of the `call` function of the `PhpIPAMClient`.

The object has 6 attributes:
* code
* success
* message
* data
* time
* body

## Code
HTTP status code.

Get:
`$response->getCode()`

## Success
Determines if the request was successful.

`$response->isSuccess()`

## Message
Is only set when a request fails. It contains the error message.

`$response->getMessage()`

## Data
Contains the returned data as an array.

`$response->getData()`

## Time
Contains how long the server took to handle the request.

`$response->getTime()`

## Body
This is the raw body which was sent by the server. It is json encoded.

`$response->getBody()`