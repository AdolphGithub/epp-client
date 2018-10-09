<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\verisignEppInfoDomainRequest;

//info domain
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Info doamin\n";
            $domainname = "sdfasf454564.com";
            $info = new verisignEppInfoDomainRequest(new eppDomain($domainname),'none');

            if ($response = $conn->request($info)) {
                echo "Domain name" . $response->getDomainName() . "\n";
                var_dump($response->getDomain());
                echo "Last update on ".$response->getDomainUpdateDate()."\n";
            }

            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}