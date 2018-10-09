<?php

require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppCreateHostRequest;
use \Guanjia\EPP\eppHost;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            $hostname ="ns1.metaregistrar.nl";
        $ipconfig = "190.1.60.5";
        createhost($conn, $hostname,$ipconfig);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}

/**
 * 添加host
 * @param $conn eppConnection
 * @param $hostname string
 * @return null
 */
function createhost($conn, $hostname,$ipconfig) {
    $create = new verisignEppCreateHostRequest(new eppHost($hostname,$ipconfig));
    if ($response = $conn->request($create)) {
        /* @var $response Guanjia\EPP\eppCreateHostResponse */
        echo "Host created on " . $response->getHostCreateDate() . " with id " . $response->getHostName() . "\n";
    }
    return null;
}