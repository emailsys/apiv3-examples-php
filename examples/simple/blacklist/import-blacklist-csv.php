<?php

use Camspiers\JsonPretty\JsonPretty;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Specify api credentials
$username = ''; // TODO specify
$password = ''; // TODO specify

$client = new Client([
    // Base URI for all guzzle requests, remember that the emailsys API only supports HTTPS
    'base_uri' => 'https://apiv3.emailsys.net'
]);

// A simple utility to prettify json data
$pretty = new JsonPretty();

try {

    $page = 1;
    $csvFileHandle = fopen('php://stdout', 'w');

    // Guzzle JSON request API is documented here: http://guzzle.readthedocs.io/en/stable/quickstart.html#uploading-data
    $response = $client->post('/blacklist/import', [
        // Guzzle request headers API is documented here: http://guzzle.readthedocs.io/en/stable/request-options.html#headers
        'headers' => [
            // The accept header tells the API that you expect JSON to be returned
            'Accept' => 'application/json'
        ],
        // Guzzle request authentication is documented here: http://guzzle.readthedocs.io/en/stable/request-options.html#auth
        'auth' => [
            $username,
            $password
        ],
        'json' => [
            'file' => [
                // A blacklist import only requires a single column in the CSV file, data must be passed base64 encoded
                'content' => base64_encode('testpattern@example.com'),
                'type' => 'text/csv'
            ]
        ]
    ]);

    // Please note that a blacklist import is not executed right away, but started as a background job.
    // You can poll the job using the /jobs/<job_id> endpoint until its status is either "finished" or "error"
    echo $pretty->prettify((string)$response->getBody()) . PHP_EOL;

} catch (ClientException $e) {

    // Guzzle exception handling is documented here: http://guzzle.readthedocs.io/en/stable/quickstart.html#exceptions
    // ClientException is thrown for any 4XX HTTP statuscode (client errors)

    if ($e->hasResponse()) {
        echo $pretty->prettify((string)$e->getResponse()->getBody()) . PHP_EOL;
    }

}
