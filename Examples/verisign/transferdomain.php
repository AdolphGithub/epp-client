<?php

require  __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppDomain;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\verisignEppCreateDomainRequest;
use Guanjia\EPP\verisignEppTransferDomainRequest;

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
            $code = [
                'YXNka2ZqbGFrc2pkZg==',
            ];
            $domain_info = createdomain($conn, $domainname, $contactid, $contactid, $contactid, $contactid, $nameservers);


            echo "transfer domain\n";
////-------------------------------------   transfer request --------------------------------------
//            $request = new verisignEppTransferDomainRequest();
//            $request->doRequest([
//                'domain_name'   =>  $domain_info['domain_name'],
//                'period'        =>  1,
//                'password'      =>  $domain_info['password']
//            ],$code,'dotCOM',[
////                [
////                    'domain_name'   =>  'domain.name',
////                    'password'      =>  'password',
////                    'infoRoleid'    =>  'adsljfladsjflkasd-fadskfjlsdakf-asdfjdalkjflkj'
////                ],
////                [
////                    'domain_name'   =>  'domain.name',
////                    'password'      =>  'password',
////                    'infoRoleid'    =>  'adsljfladsjflkasd-fadskfjlsdakf-asdfjdalkjflkj'
////                ],
//            ]);
//            if($response = $conn->request($request)){
//                echo 'transfer domain success';
//            }
////-----------------------------------------------------------------------------------------------
////--------------------------------------   transfer query ---------------------------------------
//            $request = new verisignEppTransferDomainRequest();
//            $request->doQuery($domain_info['domain_name'],'dotCOM',[
////               [
////                   'domain_name'    =>  'domain.name'
////               ]
//            ]);
//
//            if($response = $conn->request($request)){
//                echo 'transfer query domain success' . "\n";
//            }
////-----------------------------------------------------------------------------------------------
////--------------------------------------   transfer approve -------------------------------------
//            $request = new verisignEppTransferDomainRequest();
//            $request->doApprove($domain_info['domain_name'],'dotCOM',[
////               [
////                   'domain_name'    =>  'domain.name'
////               ]
//            ]);
//
//            if($response = $conn->request($request)){
//                echo 'transfer query domain success' . "\n";
//            }
////-----------------------------------------------------------------------------------------------
////--------------------------------------   transfer reject  -------------------------------------
//            $request = new verisignEppTransferDomainRequest();
//            $request->doReject($domain_info['domain_name'],'dotCOM',[
////               [
////                   'domain_name'    =>  'domain.name'
////               ]
//            ]);
//
//            if($response = $conn->request($request)){
//                echo 'transfer query domain success' . "\n";
//            }
////-----------------------------------------------------------------------------------------------
//--------------------------------------   transfer cancel  -------------------------------------
            $request = new verisignEppTransferDomainRequest();
            $request->doCancel($domain_info['domain_name'],'dotCOM',[
//               [
//                   'domain_name'    =>  'domain.name'
//               ]
            ]);

            if($response = $conn->request($request)){
                echo 'transfer query domain success' . "\n";
            }
//-----------------------------------------------------------------------------------------------
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