<?php
require '../Loader.php';

\Guanjia\Loader::load();


use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppCheckHostRequest;
use Guanjia\EPP\verisignEppCheckHostResponse;


/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */

for ($i = 1; $i < $argc; $i++) {
    $contact[] = $argv[$i];
}

echo "Checking " . count($contact) . " contact names\n";
if ($conn = eppConnection::create(__DIR__ . '/../settings.ini',true)) {
    // Connect and login to the EPP server
    if ($conn->login()) {
        // Check domain names
        checkhosts($conn, [
            'contact_id'    =>  $contact[0],
            'type'          =>  'dotCOM'
        ]);
        $conn->logout();
    }
}

/**
 * @param $conn Guanjia\EPP\eppConnection
 * @param $contact array of hostnames
 */
function checkhosts($conn, $contact) {
    // Create request to be sent to EPP service
    $check = new \Guanjia\EPP\verisignEppInfoContactRequest($contact);
    // Write request to EPP service, read and check the results
    if ($response = $conn->request($check)) {
        /* @var $response Guanjia\EPP\verisignEppInfoContactResponse */
        // Walk through the results
        $checks = $response->getContact();
        var_dump($checks);
    }
}