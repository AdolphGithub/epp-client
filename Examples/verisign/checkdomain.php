<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\verisignEppCheckDomainRequest;

//info domain
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            // Create request to be sent to EPP service
            $domains = "sdfafdf.com";
            $check = new verisignEppCheckDomainRequest($domains);


            // Write request to EPP service, read and check the results
            if ($response = $conn->request($check)) {
                /* @var $response verisignEppCheckDomainResponse 123456 */
                // Walk through the results
                $checks = $response->getCheckedDomains();
                var_dump($checks);
            }

            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}