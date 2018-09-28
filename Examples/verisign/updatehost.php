<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppUpdateHostRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "update host\n";
            $request = new verisignEppUpdateHostRequest('ns1.example.com',[
                'new_host'  =>  'dns.jia10000.cn',
                'add'  =>  [
                    [
                        'type'  =>  'v4',
                        'ip'    =>  '129.32.12.110'
                    ]
                ],
                'remove'    =>  [
                    [
                        'type'  =>  'v4',
                        'ip'    =>  '134.54.63.20'
                    ]
                ]
            ]);
            if($response = $conn->request($request)){
                echo 'update host success';
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}