<?php

require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\verisignEppDeleteDomainRequest;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\verisignEppCreateDomainRequest;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\verisignEppCreateContactRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "create domain\n";

            $nameservers = array('ns1.metaregistrar.nl','ns2.metaregistrar.nl');
//            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            $domainname = "asdfasdfasdasdfffffff.com";
            $contactid = "MRG5bacadc22ff8e";
            if ($contactid) {
                createdomain($conn, $domainname, $contactid, $contactid, $contactid, $contactid, $nameservers);
            }


            echo "delete domain\n";
            $request = new verisignEppDeleteDomainRequest($domainname,'alkfjlksajdlfjasldf=');
            if($response = $conn->request($request)){
                echo 'delete domain success';
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}


/**
 * 创建域名
 * @param eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 * @return string
 */
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    /* @var $conn Guanjia\EPP\eppConnection */
    $domain = new eppDomain($domainname, $registrant);
    $domain->setRegistrant(new eppContactHandle($registrant));
    $domain->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
    $domain->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
    $domain->setAuthorisationCode('sampleAuthInfo-1');
    if (is_array($nameservers)) {
        foreach ($nameservers as $nameserver) {

            $domain->addHost(new eppHost($nameserver));
        }
    }


    $create = new verisignEppCreateDomainRequest($domain);
    /* @var $response Guanjia\EPP\eppCreateDomainResponse */
    if ($response = $conn->request($create)) {
        echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        return $response->getDomainName();
    }
    return $domainname;
}

/**
 * @param $conn eppConnection
 * @param $email string
 * @param $telephone string
 * @param $name string
 * @param $organization string
 * @param $address string
 * @param $postcode string
 * @param $city string
 * @param $country string
 * @return null
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode);
    $contactinfo = new eppContact($postalinfo, $email, $telephone);
    $contactinfo->setPassword('');
    $contact = new verisignEppCreateContactRequest($contactinfo);
    if ($response = $conn->request($contact)) {
        /* @var $response Guanjia\EPP\eppCreateContactResponse */
        echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
        return $response->getContactId();
    } else {
        echo "Create contact failed";
    }
    return null;
}