<?php

require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppDeleteHostRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Delete host\n";
            $request = new verisignEppDeleteHostRequest('ns1.example.com');
            if($response = $conn->request($request)){
                echo 'delete host success';
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}