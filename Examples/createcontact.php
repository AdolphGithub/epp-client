<?php

require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\eppCreateContactRequest;

/**
 * This code example creates a contact object with a registry
 */

try {
// Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            createcontact($conn, 'info@test.com', '+31.201234567', 'Domain Administration', 'Guanjia', 'Address 1', 'Zipcode', 'City', 'NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
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
    $contact = new eppCreateContactRequest($contactinfo);
    if ($response = $conn->request($contact)) {
        /* @var $response Guanjia\EPP\eppCreateContactResponse */
        echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
        return $response->getContactId();
    } else {
        echo "Create contact failed";
    }
    return null;
}