<?php

require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\verisignEppCreateDomainRequest;
use Guanjia\EPP\verisignEppRestoreDomainRequest;
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "create domain\n";
            $nameservers = array('ns1.metaregistrar.nl','ns2.metaregistrar.nl');
//            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            $domainname = 'asdfas' . date('His',time()) . '.com';
            $contactid = "MRG5bacadc22ff8e";
            $code = 'YXNka2ZqbGFrc2pkZg==';
            $domain_info = createdomain($conn, $domainname, $contactid, $contactid, $contactid, $contactid, $nameservers);

            echo "restore domain start\n";
////----------------------------------- restore request ---------------------------------------------------
//            $request = new verisignEppRestoreDomainRequest();
//            $request->doRequest($domain_info['domain_name'],'dotCOM');
//            if($response = $conn->request($request)){
//                echo 'sync domain success' . "\n";
//            }
////----------------------------------- restore request ---------------------------------------------------

//----------------------------------- restore report  ---------------------------------------------------
            $request = new verisignEppRestoreDomainRequest();
            $request->doReport([
                'domain_name'   =>  $domain_info['domain_name'],
                'preData'       =>  'Pre-WhoIs Data...',
                'postData'      =>  'Post-WhoIs Data...',
                'delTime'       =>  '2019-09-29T22:00:00.0Z',
                'resTime'       =>  '2019-11-29T22:00:00.0Z',
                'resReason'     =>  'Customer forgot to renew.',
                'statement'     =>  [
                    'I agree that the Domain Name has not been restored in order to assume the rights to use or sell the name to myself or for any third party.',
                    'I agree that the information provided in this Restore Report is true to the best of my knowledge, and acknowledge that intentionally supplying false information in the Restore Report shall constitute an incurable material breach of the Registry-Registrar Agreement.'
                ],
                'other'         =>  ''
            ],'dotCOM');

            if($response = $conn->request($request)){
                echo 'sync domain success' . "\n";
            }
//----------------------------------- restore report  ---------------------------------------------------
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
 * @return array|string
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
        return [
            'password'  =>  $domain->getAuthorisationCode(),
            'domain_name'   =>  $response->getDomainName()
        ];
    }
    return $domainname;
}