<?php

require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\verisignEppCreateContactRequest;
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            createcontact($conn, 'info@test.com', '+31.201234567', 'Domain Administration', 'Metaregistrar', 'Address 1', 'Zipcode', 'City', 'NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}
/**
 * 创建联系人
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

        echo  $response->getContactCreateDate() . $response->getContactId() ;
        $ContactId = $response->getContactId();
        return $ContactId;
//        var_dump($ContactId);
    }
    return null;
}