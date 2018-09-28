<?php
require __DIR__ .'../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContact;
use Guanjia\EPP\verisignEppTransferContactRequest;
use Guanjia\EPP\eppContactPostalInfo;
use Guanjia\EPP\verisignEppCreateContactRequest;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Creating contact\n";
            $contact_info = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            echo "Transfer contact #{$contact_info['contact_id']}\n";
            transfer($conn,$contact_info['contact_id'],$contact_info['password'],'approve');
//            transfer($conn,$contact_info['contact_id'],$contact_info['password'],'reject');
//            transfer($conn,$contact_info['contact_id'],$contact_info['password'],'approve');
//            transfer($conn,$contact_info['contact_id'],$contact_info['password'],'query');
//            transfer($conn,$contact_info['contact_id'],$contact_info['password'],'request');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}
/**
 * @param eppConnection $conn
 * @param $contact_id
 * @param $password
 * @param string $action
 * @param string $sub_product
 */
function transfer($conn,$contact_id, $password, $action = 'cancel', $sub_product = 'dotCOM') {
    $request = new verisignEppTransferContactRequest($contact_id,$password,$action,$sub_product);
    if( $response =  $conn->request($request)) {
        echo "\nTransfer contact action:{$action}, success\n";
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