<?php
require '../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\verisignEppUpdateContactRequest;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\verisignEppCreateContactRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Creating contact\n";
            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            echo "Updating contact #{$contactid['contact_id']}\n";
            updatecontact($conn,$contactid,'up@hostmax.ch','+31.20123456789','Updates name','Updated org','Updated address 1','12345','City','NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}

/**
 * @param eppConnection $conn
 * @param array $contactid
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 */
function updatecontact($conn, $contact_info, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $update = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContact::TYPE_INT);
        $contact = new eppContact($update,$email,$telephone,null,$contact_info['password']);
        $contact->setId($contact_info['contact_id']);
        $up = new verisignEppUpdateContactRequest($contact,  $update);
        if ($response = $conn->request($up)) {
            /* @var $response Guanjia\EPP\eppCreateResponse */
            echo "Contact {$contact_info['contact_id']} updated.\n";
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
 * @return null|array
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode);
    $contactinfo = new eppContact($postalinfo, $email, $telephone,null,'AuthInfo-f3f139d91c969ba52321');
    $contact = new verisignEppCreateContactRequest($contactinfo);
    /* @var $response Guanjia\EPP\verisignEppCreateContactResponse */
    if ($response = $conn->request($contact)) {
        echo  $response->getContactCreateDate() . $response->getContactId() ;
        return [
            'contact_id'=>  $response->getContactId(),
            'password'  =>  $contactinfo->getPassword()
        ];
    }
    return null;
}