<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppPollReqRequest;


try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Info host\n";
            $request = new verisignEppPollReqRequest();
            if($response = $conn->request($request)){
                /* @var $response Guanjia\EPP\verisignEppPollReqResponse */
                $request->saveXML();
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}
