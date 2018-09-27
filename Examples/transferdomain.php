<?php
require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppTransferRequest;

/*
 * This script requests a domain name transfer into your account
 */

if ($argc <= 2) {
    echo "Usage: transferdomain.php <domainname> <authcode>\n";
    echo "Please the domain name and the auth code for transfer\n\n";
    die();
}
$domainname = $argv[1];
$authcode = $argv[2];

echo "Transferring $domainname\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            transferdomain($conn, $domainname, $authcode);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param eppConnection $conn
 * @param string $domainname
 * @param string $authcode
 */
function transferdomain($conn, $domainname, $authcode) {
    try {
        $domain = new eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $domain->setRegistrant(new \Guanjia\EPP\eppContactHandle('registrant handle'));
        $domain->addContact(new \Guanjia\EPP\eppContactHandle('admin handle', \Guanjia\EPP\eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new \Guanjia\EPP\eppContactHandle('tech handle', \Guanjia\EPP\eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new \Guanjia\EPP\eppContactHandle('billing handle', \Guanjia\EPP\eppContactHandle::CONTACT_TYPE_BILLING));
        $domain->addHost(new \Guanjia\EPP\eppHost('ns1.yourdomainprovider.net'));
        $domain->addHost(new \Guanjia\EPP\eppHost('ns2.yourdomainprovider.net'));
        $domain->addHost(new \Guanjia\EPP\eppHost('ns3.yourdomainprovider.net'));
        $transfer = new \Guanjia\EPP\metaregEppTransferExtendedRequest(eppTransferRequest::OPERATION_REQUEST,$domain);
        if ($response = $conn->request($transfer)) {
            /* @var $response Guanjia\EPP\eppTransferResponse */
            echo $response->getDomainName()," transfer request was succesful\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}