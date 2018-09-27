<?php
require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\eppUpdateContactRequest;
use Guanjia\EPP\eppContactPostalInfo;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Creating contact\n";
            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            echo "Updating contact #$contactid\n";
            updatecontact($conn,$contactid,'up@hostmax.ch','+31.20123456789','Updates name','Updated org','Updated address 1','12345','City','NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}

/**
 * @param eppConnection $conn
 * @param string $contactid
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 */
function updatecontact($conn, $contactid, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $contact = new eppContactHandle($contactid);
        $update = new eppContact(new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContact::TYPE_LOC),$email,$telephone);
        $up = new eppUpdateContactRequest($contact, null, null, $update);
        if ($response = $conn->request($up)) {
            /* @var $response Guanjia\EPP\eppCreateResponse */
            echo "Contact $contactid updated.\n";
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param eppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @return null|string
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $contact = new Guanjia\EPP\eppContact(new Guanjia\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Guanjia\EPP\eppContact::TYPE_LOC), $email, $telephone);
        $create = new Guanjia\EPP\EppCreateContactRequest($contact);
        if ($response = $conn->request($create)) {
            /* @var $response Guanjia\EPP\eppCreateContactResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}