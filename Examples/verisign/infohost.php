<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppInfoHostRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Info host\n";
            $request = new verisignEppInfoHostRequest('ns1.example.com');
            if($response = $conn->request($request)){
                echo 'Info host success';
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}