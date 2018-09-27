<?php
require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppSecdns;
use Guanjia\EPP\eppDnssecUpdateDomainRequest;
use Guanjia\EPP\eppException;

try {
    $domainname = 'dnssectest.nl';
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableDnssec();
        if ($conn->login()) {
            $add = new eppDomain($domainname);
            $sec = new eppSecdns();
            $sec->setKey('256', '8', 'AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
            $add->addSecdns($sec);
            $update = new eppDnssecUpdateDomainRequest($domainname, $add);
            if ($response = $conn->request($update)) {
                /* @var $response Guanjia\EPP\eppUpdateDomainResponse */
                echo "DNSSEC updated\n";
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

