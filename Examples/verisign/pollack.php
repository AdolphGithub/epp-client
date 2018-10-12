<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppPollAckRequest;
use Guanjia\EPP\verisignEppPollAckResponse;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "poll act\n";
            $messageid = "126456478987455315564";
            $request = new verisignEppPollAckRequest($messageid);
            /* @var $response Guanjia\EPP\verisignEppPollAckResponse */
            if($response = $conn->request($request)){
                return $request->getDelDataName();
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}
