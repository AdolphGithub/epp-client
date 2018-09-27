<?php
require('../../autoloader.php');

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppCheckDomainRequest;
use Guanjia\EPP\eppCheckDomainResponse;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\eppCheckContactRequest;
use Guanjia\EPP\eppCheckHostRequest;
use Guanjia\EPP\ficoraEppCreateContactRequest;
use Guanjia\EPP\eppCreateDomainRequest;
use Guanjia\EPP\eppCreateHostRequest;
use Guanjia\EPP\ficoraEppCheckBalanceRequest;
use Guanjia\EPP\ficoraEppCheckBalanceResponse;
use Guanjia\EPP\eppPollRequest;
use Guanjia\EPP\eppPollResponse;


/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */

$domains = ['ewoutdegraaf.fi'];


try {

    if ($conn = eppConnection::create('settings.ini', false)) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            echo "Checking balance\n";
            checkbalance($conn);
            echo "Checking poll\n";
            checkpoll($conn);
            // Check domain names
            echo "Checking " . count($domains) . " domain names\n";
            checkdomains($conn, $domains);
            echo "Checking contact\n";
            checkcontact($conn,'C5525');
            echo "Checking hosts\n";
            checkhosts($conn,['ns1.metaregistrar.com','ns2.metaregistrar.com','ns3.metaregistrar.com']);
            //echo "Creating contact\n";
           //$contactid = createcontact($conn, 'ewout@metaregistrar.com','+35.8401231234','Department' ,'Guanjia' ,'Zuidelijk Halfrond 1' ,'8201 DD' , 'Gouda', 'NL');
            $registrant = 'C5525';
            $admin = 'C2526';
            $tech = 'C4529';
            echo "Creating domain\n";
            createdomain($conn,'ewoutdegraaf4.fi',$registrant,$admin,$tech,null,['ns1.metaregistrar.com','ns2.metaregistrar.com']);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage()."\n";
    echo $e->getLastCommand();
    echo "\n\n";
}

/**
 * @param eppConnection $conn
 */
function checkbalance($conn) {
    $check = new ficoraEppCheckBalanceRequest();
    if ($response = $conn->request($check)) {
        /* @var $response ficoraEppCheckBalanceResponse */
        echo "Balance is ".$response->getBalanceAmount()." on ".$response->getBalanceDate()."\n";
        //echo $response->saveXML();
    }
}

/**
 * @param eppConnection $conn
 */
function checkpoll($conn) {
    $poll = new eppPollRequest(eppPollRequest::POLL_REQ);
    if ($response = $conn->request($poll)) {
        /* @var $response eppPollResponse */
        echo "You have ".$response->getMessageCount()." poll messages waiting\n";
    }
}

/**
 * @param $conn eppConnection
 * @param $domains array of domain names
 */
function checkdomains($conn, $domains) {
    // Create request to be sent to EPP service
    $check = new eppCheckDomainRequest($domains,false);
    // Write request to EPP service, read and check the results
    if ($response = $conn->request($check)) {
        /* @var $response eppCheckDomainResponse */
        // Walk through the results
        $checks = $response->getCheckedDomains();
        foreach ($checks as $check) {
            echo $check['domainname'] . " is " . ($check['available'] ? 'free' : 'taken');
            if ($check['available']) {
                echo ' (' . $check['reason'] .')';
            }
            echo "\n";
        }
    }
}


function checkcontact($conn, $contactid) {
    /* @var $conn Guanjia\EPP\eppConnection */
    try {
        $check = new eppCheckContactRequest(new eppContactHandle($contactid),false);
        if ($response = $conn->request($check)) {
            /* @var $response Guanjia\EPP\eppCheckContactResponse */
            //$response->dumpContents();
            $checks = $response->getCheckedContacts();
            foreach ($checks as $contact => $check) {
                echo "Contact $contact " . ($check ? 'does not exist' : 'exists') . "\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}



function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    /* @var $conn Guanjia\EPP\eppConnection */
    try {
        $contactinfo = new eppContact(new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContact::TYPE_LOC), $email, $telephone);
        // Ficora will not accept a password field in the request
        $contactinfo->setPassword(null);
        $contact = new ficoraEppCreateContactRequest($contactinfo);
        $contact->setRole(ficoraEppCreateContactRequest::FI_CONTACT_ROLE_TECHNICAL);
        $contact->setType(ficoraEppCreateContactRequest::FI_CONTACT_TYPE_COMPANY);
        $contact->setIsfinnish(false);
        $contact->setFirstname('Ewout');
        $contact->setLastname('de Graaf');
        $contact->setBirthdate('2005-04-03 22:00');
        $contact->setRegisternumber('1234312-5');
        $contact->setIdentity('123423A123F');
        $contact->setLegalemail($contactinfo->getEmail());
        if ($response = $conn->request($contact)) {
            /* @var $response Guanjia\EPP\eppCreateContactResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
        echo $e->getLastCommand()."\n";
    }
    return null;
}


/**
 * @param $conn eppConnection
 * @param $hosts
 * @return bool|null
 */
function checkhosts($conn, $hosts) {
    try {
        $checkhost = array();
        foreach ($hosts as $host) {
            $checkhost[] = new eppHost($host);
        }
        $check = new eppCheckHostRequest($checkhost,false);
        if ($response = $conn->request($check)) {
            /* @var $response Guanjia\EPP\eppCheckHostResponse */
            $checks = $response->getCheckedHosts();
            $allchecksok = true;
            foreach ($checks as $hostname => $check) {
                echo "$hostname " . ($check ? 'does not exist' : 'exists') . "\n";
                if ($check) {
                    $allchecksok = false;
                }
            }
            return $allchecksok;
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param eppConnection $conn
 * @param string $hostname
 * @param string $ipaddress
 */
function createhost($conn, $hostname, $ipaddress=null) {

    try {
        $create = new eppCreateHostRequest(new eppHost($hostname,$ipaddress),false);
        if ($response = $conn->request($create)) {
            /* @var $response Guanjia\EPP\eppCreateHostResponse */
            echo "Host created on " . $response->getHostCreateDate() . " with name " . $response->getHostName() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 */
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    /* @var $conn Guanjia\EPP\eppConnection */
    try {
        $domain = new eppDomain($domainname, $registrant);
        $domain->setPeriod(2);
        $domain->setRegistrant(new eppContactHandle($registrant));
        if ($admincontact) {
            $domain->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
        }
        if ($techcontact) {
            $domain->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
        }
        if ($billingcontact) {
            $domain->addContact(new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING));
        }
        $domain->setAuthorisationCode('rand0m');
        if (is_array($nameservers)) {
            foreach ($nameservers as $nameserver) {
                $domain->addHost(new eppHost($nameserver));
            }
        }
        $create = new eppCreateDomainRequest($domain,false,false);
        $create->dumpContents();
        if ($response = $conn->request($create)) {
            /* @var $response Guanjia\EPP\eppCreateDomainResponse */
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}