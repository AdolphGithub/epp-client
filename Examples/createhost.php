<?php

require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\eppCreateHostRequest;

if ($argc <= 1) {
    echo "Usage: createhost.php <hostname>\n";
    echo "Please a host name to create\n\n";
    die();
}

$hostname = $argv[1];

try {
// Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            createhost($conn, $hostname);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}



/**
 * @param $conn eppConnection
 * @param $hostname string
 * @return null
 */
function createhost($conn, $hostname) {
    $create = new eppCreateHostRequest(new eppHost($hostname));
    if ($response = $conn->request($create)) {
        /* @var $response Guanjia\EPP\eppCreateHostResponse */
        echo "Host created on " . $response->getHostCreateDate() . " with id " . $response->getHostName() . "\n";
    }
    return null;
}