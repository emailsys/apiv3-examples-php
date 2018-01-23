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

// Utility function to filter meta keys (ones prefixed with underscore) from given dataset
$filterMetaKeys = function($data) {

    return array_filter($data, function($value, $key) {
        return substr($key, 0, 1) !== '_';
    }, ARRAY_FILTER_USE_BOTH);

};

try {

    // Per default, we export to php://stdout, specify filename here if required
    $csvFileHandle = fopen('php://stdout', 'w');

    if (empty($csvFileHandle)) {
        echo 'Unable to open file' . PHP_EOL;
        exit(1);
    }

    $page = 1;

    do {

        $response = $client->get('/blacklist', [
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
            'query' => [
                'page' => $page
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (intval($data['total_items']) === 0) {
            echo 'No items on blacklist' . PHP_EOL;
            exit(1);
        }

        if ($page === 1) {
            fputcsv($csvFileHandle, array_keys($filterMetaKeys($data['_embedded']['blacklist'][0])));
        }

        foreach ($data['_embedded']['blacklist'] AS $blacklistEntry) {
            fputcsv($csvFileHandle, $filterMetaKeys($blacklistEntry));
        }

        // Please note that we export the blacklist entries completely here, while the /blacklist/import endpoint only
        // requires you to specify a list of patterns one per line

        $page++;

    } while ($page <= $data['page_count']);

    if (is_resource($csvFileHandle)) {
        fclose($csvFileHandle);
    }

} catch (ClientException $e) {

    // Guzzle exception handling is documented here: http://guzzle.readthedocs.io/en/stable/quickstart.html#exceptions
    // ClientException is thrown for any 4XX HTTP statuscode (client errors)

    if ($e->hasResponse()) {
        echo $pretty->prettify((string)$e->getResponse()->getBody()) . PHP_EOL;
    }

}
