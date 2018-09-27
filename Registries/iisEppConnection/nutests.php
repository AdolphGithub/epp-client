<?php
require('../../autoloader.php');

$conn = new Guanjia\EPP\iisEppConnection();
// Connect to the EPP server
if ($conn->connect()) {
    if (greet($conn)) {
        if (login($conn)) {
            echo "Test 1: Create domain name with subordinate hosts\n";
            test1($conn, 'fte236937-eca49a10f5cbc2fb8371e6817b9ec54e-01.nu');
            #echo "Test 2: Update contact with telephone and postal code\n";
            #test2($conn,'');
            #echo "Test 3: Add ip 217.108.99.249 , 2001:698:a:e:208:2ff:fe15:b2e8 to host\n";
            #test3($conn, '');
            #echo "Test 4: Add host primary.nu to domain\n";
            #test4($conn, '');
            #echo "Test 5: Remove host testhost from domain\n";
            #test5($conn, '');
            #echo "Test 6: Change the owner of domain \n";
            #test6($conn, '');
            #echo "Test 7: Renew domain\n";
            #test7($conn,'');
            #echo "Test 8: Set client delete for domain\n";
            #test8($conn, '');
            #echo "Test 9: Clear client delete for domain\n";
            #test9($conn, '');
            #echo "Test 10: Request transfer of domain with authInfo\n";
            #test10($conn, '.nu', '');
            #echo "Test 11: Remove DS records from domain \n";
            #test11($conn,'.nu');
            #echo "Test 12: Set authInfo for domain\n";
            #test12($conn, 'fte236937-eca49a10f5cbc2fb8371e6817b9ec54e-09.nu', 'FTEfte--3070');
            #echo "Test 13: Empty message queue\n";
            #test13($conn);
            logout($conn);
        }
    }
}

function test1($conn, $domainname) {
    $domain = new Guanjia\EPP\eppDomain($domainname);
    if (checkdomain($conn, $domain)) {
        $registrant = createcontact($conn, 'noreply@iis.nu', '+46.123412340', 'Hostmistress', 'Not a Real Company', 'longwayhome', '76543', 'Kiruna', 'NL');
        $adminc = createcontact($conn, 'noreply@iis.nu', '+46.123412340', 'Hostmistress', 'Not a Real Company', 'longwayhome', '76543', 'Kiruna', 'NL');
        createdomain($conn, $domainname, $registrant, $adminc, $adminc, null, null);
        /* Check and/or create host objects */
        echo $domainname . " is registered, update domain with host objects\n";
        $hostnames[] = 'ns1.' . $domainname;
        $hostnames[] = 'ns2.' . $domainname;
        checkandcreatehosts($conn, $hostnames);

        updatedomainaddhost($conn, $domainname, $hostnames);
    } else {
        #$hostnames[] = 'ns1.'.$domainname;
        #$hostnames[] = 'ns2.'.$domainname;
        #updatedomainaddhost($conn,$domainname,$hostnames);
        echo "Test 1 completed, info domain:\n";
        infodomain($conn, $domainname);
    }
}

function test2($conn, $contactid) {
    updatecontact($conn, $contactid);
}

function test3($conn, $hostname) {
    $ips[] = '217.108.99.249';
    $ips[] = '2001:698:a:e:208:2ff:fe15:b2e8';
    updatehostaddip($conn, $hostname, $ips);
}

function test4($conn, $domainname) {
    updatedomainaddhost($conn, $domainname, array('primary.nu'));
}

function test5($conn, $domainname) {
    updatedomainremovehost($conn, $domainname, array('testhost.' . $domainname));
}

function test6($conn, $domainname) {
    //infodomain($conn,$domainname);
    // Current registrant = CONT1002-236937
    $registrant = createcontact($conn, 'noreply@iis.nu', '+46.123412340', 'Hostmistress', 'Not a Real Company', 'longwayhome', '76543', 'Kiruna', 'NL');
    updatedomainchangeregistrant($conn, $domainname, $registrant);
}


function test7($conn, $domainname) {
    // Current exp date: 2013-06-16T00:00:00.0Z
    $info = infodomain($conn, $domainname);
    /* @var $info Guanjia\EPP\iisEppInfoDomainResponse */
    echo "Current exp date: " . $info->getDomainExpirationDate();
    renewdomain($conn, $domainname, $info->getDomainExpirationDate());
}

function test8($conn, $domainname) {
    $info = infodomain($conn, $domainname);
    /* @var $info Guanjia\EPP\iisEppInfoDomainResponse */
    echo "Client delete setting: " . $info->getDomainClientDelete();
    if ($info->getDomainClientDelete() == '0') {
        updatedomainsetclientdelete($conn, $domainname, '1');
    }
}

function test9($conn, $domainname) {
    $info = infodomain($conn, $domainname);
    /* @var $info Guanjia\EPP\iisEppInfoDomainResponse */
    echo "Client delete setting: " . $info->getDomainClientDelete();
    if ($info->getDomainClientDelete() == '1') {
        updatedomainsetclientdelete($conn, $domainname, '0');
    }
}

function test10($conn, $domainname, $authcode) {
    transferdomain($conn, $domainname, $authcode);
}

function test11($conn, $domainname) {
    $info = dnssecinfodomain($conn, $domainname);
    /* @var $info Guanjia\EPP\eppInfoDomainResponse */
    $keydata = $info->getKeydata();
    if (count($keydata) > 0) {
        updatedomainremoveds($conn, $domainname);
    } else {
        echo "No DS data present any more on this domain name\n";
    }
}

function test12($conn, $domainname, $authcode) {
    updatedomainsetauthcode($conn, $domainname, $authcode);
}

function test13($conn) {
    $polldata = poll($conn);
    /* @var $polldata Guanjia\EPP\eppPollResponse */
    while ($polldata->getMessageCount() > 0) {
        pollack($conn, $polldata->getMessageId());
        $polldata = poll($conn);
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @return \Guanjia\EPP\eppPollResponse|\Guanjia\EPP\eppResponse|null
 */
function poll($conn) {
    try {
        $poll = new Guanjia\EPP\eppPollRequest(Guanjia\EPP\eppPollRequest::POLL_REQ);
        if ((($response = $conn->writeandread($poll)) instanceof Guanjia\EPP\eppPollResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppPollResponse */
            if ($response->getResultCode() == Guanjia\EPP\eppResponse::RESULT_MESSAGE_ACK) {
                echo $response->saveXML();
                echo $response->getMessageCount() . " messages waiting in the queue\n";
                echo "Picked up message " . $response->getMessageId() . ': ' . $response->getMessage() . "\n";
                return $response;
            } else {
                echo $response->getResultMessage() . "\n";
            }
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $messageid
 */
function pollack($conn, $messageid) {
    try {
        $poll = new Guanjia\EPP\eppPollRequest(Guanjia\EPP\eppPollRequest::POLL_ACK, $messageid);
        if ((($response = $conn->writeandread($poll)) instanceof Guanjia\EPP\eppPollResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppPollResponse */
            echo "Message $messageid is acknowledged\n";
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param array $hosts
 */
function checkandcreatehosts($conn, $hosts) {
    $checkhost = array();
    try {
        foreach ($hosts as $host) {
            $checkhost[] = new Guanjia\EPP\eppHost($host);
        }
        $check = new Guanjia\EPP\eppCheckRequest($checkhost);
        if ((($response = $conn->writeandread($check)) instanceof Guanjia\EPP\eppCheckHostResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppCheckHostResponse */
            $checks = $response->getCheckedHosts();
            foreach ($checks as $hostname => $check) {
                echo "$hostname " . ($check ? 'does not exist' : 'exists') . "\n";
                if ($check) {
                    if ($hostname == '') {
                        $ipaddress[] = '81.4.97.131';
                        //$ipaddress[] = 'fe80:0:0:0:200:f8ff:fe21:67cf';
                    } else {
                        $ipaddress[] = '188.93.148.99';
                        //$ipaddress[] = '2a00:4e40:1:1:0:5:3';
                    }
                    echo "Creating host $hostname \n";
                    createhost($conn, $hostname, $ipaddress);
                }
            }
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $hostname
 * @param string $ipaddress
 */
function createhost($conn, $hostname, $ipaddress) {
    try {
        $create = new Guanjia\EPP\eppHost($hostname, $ipaddress);
        $host = new Guanjia\EPP\eppCreateRequest($create);
        if ((($response = $conn->writeandread($host)) instanceof Guanjia\EPP\eppCreateHostResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppCreateHostResponse */
            echo "Host created on " . $response->getHostCreateDate() . " with name " . $response->getHostName() . "\n";
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @return null
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $postalinfo = new Guanjia\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Guanjia\EPP\eppContact::TYPE_LOC);
        $contactinfo = new Guanjia\EPP\eppContact($postalinfo, $email, $telephone);
        $contact = new Guanjia\EPP\iisEppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Guanjia\EPP\eppCreateContactResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppCreateContactResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 */
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    try {
        $reg = new Guanjia\EPP\eppContactHandle($registrant);
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $domain->setRegistrant($reg);
        if ($admincontact) {
            $admin = new Guanjia\EPP\eppContactHandle($admincontact, Guanjia\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
            $domain->addContact($admin);
        }
        if ($techcontact) {
            $tech = new Guanjia\EPP\eppContactHandle($techcontact, Guanjia\EPP\eppContactHandle::CONTACT_TYPE_TECH);
            $domain->addContact($tech);
        }
        if ($billingcontact) {
            $billing = new Guanjia\EPP\eppContactHandle($billingcontact, Guanjia\EPP\eppContactHandle::CONTACT_TYPE_BILLING);
            $domain->addContact($billing);
        }
        if (is_array($nameservers)) {
            foreach ($nameservers as $nameserver) {
                $host = new Guanjia\EPP\eppHost($nameserver);
                $domain->addHost($host);
            }
        }
        $create = new Guanjia\EPP\eppCreateRequest($domain);
        if ((($response = $conn->writeandread($create)) instanceof Guanjia\EPP\eppCreateDomainResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppCreateDomainResponse */
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $expdate
 */
function renewdomain($conn, $domainname, $expdate) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $domain->setPeriodUnit('y');
        $domain->setPeriod('3');
        $expdate = substr($expdate, 0, 10);
        $renew = new Guanjia\EPP\eppRenewRequest($domain, $expdate);
        if ((($response = $conn->writeandread($renew)) instanceof Guanjia\EPP\eppRenewResponse) && ($response->Success())) {
            echo "Domain $domainname renewed, infoing\n";
            infodomain($conn, $domainname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param array $hosts
 */
function updatedomainaddhost($conn, $domainname, $hosts) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $add = new Guanjia\EPP\eppDomain($domainname);
        foreach ($hosts as $host) {
            $h = new Guanjia\EPP\eppHost($host);
            $add->addHost($h);
        }
        $up = new Guanjia\EPP\eppUpdateHostRequest($domain, $add, null, null);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateHostResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppUpdateHostResponse */
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn, $domainname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param array $hosts
 */
function updatedomainremovehost($conn, $domainname, $hosts) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $remove = new Guanjia\EPP\eppDomain($domainname);
        foreach ($hosts as $host) {
            $h = new Guanjia\EPP\eppHost($host);
            $remove->addHost($h);
        }
        $up = new Guanjia\EPP\eppUpdateRequest($domain, null, $remove, null);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn, $domainname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 */
function updatedomainchangeregistrant($conn, $domainname, $registrant) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $chg = new Guanjia\EPP\eppDomain($domainname);
        $chg->setRegistrant($registrant);
        $up = new Guanjia\EPP\eppUpdateRequest($domain, null, null, $chg);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn, $domainname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $authcode
 */
function updatedomainsetauthcode($conn, $domainname, $authcode) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $chg = new Guanjia\EPP\eppDomain($domainname);
        $chg->setAuthorisationCode($authcode);
        $up = new Guanjia\EPP\eppUpdateRequest($domain, null, null, $chg);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Domain $domainname updated with authcode\n";
            //infodomain($conn,$domainname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $clientdelete
 */
function updatedomainsetclientdelete($conn, $domainname, $clientdelete) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $up = new Guanjia\EPP\iisEppUpdateDomainClientDeleteRequest($domain, $clientdelete);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn, $domainname);
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 */
function updatedomainremoveds($conn, $domainname) {
    try {
        $secdns = new Guanjia\EPP\eppSecdns();
        $secdns->setData(45678, 2, 'B5C422428DEA4137FBF15E1049A48D27FA5EADE64D2EC9F3B58A994A6ABDE543');
        $secdns->setAlgorithm(5);
        $domain = new Guanjia\EPP\eppDnssecUpdateDomainRequest($domainname, null, $secdns);
        echo $domain->saveXML();
        if ((($response = $conn->writeandread($domain)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo $response->saveXML();
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }

}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $contactid
 */
function updatecontact($conn, $contactid) {
    try {
        $contact = new Guanjia\EPP\eppContactHandle($contactid);
        $update = new Guanjia\EPP\eppContact();
        $update->setVoice('+46.799999999');
        $pi = new Guanjia\EPP\eppContactPostalInfo(null, 'Kiruna', 'SE', null, null, null, '18752', Guanjia\EPP\eppContact::TYPE_LOC);
        $update->addPostalInfo($pi);
        $up = new Guanjia\EPP\eppUpdateRequest($contact, null, null, $update);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Contact $contactid updated, infoing\n";
            infocontact($conn, $contactid);
        }
    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $hostname
 * @param array $ips
 */
function updatehostaddip($conn, $hostname, $ips) {
    try {
        $domain = new Guanjia\EPP\eppHost($hostname);
        $add = new Guanjia\EPP\eppHost($hostname);
        foreach ($ips as $ip) {
            $add->setIpAddress($ip);
        }
        $up = new Guanjia\EPP\eppUpdateRequest($domain, $add, null, null);
        if ((($response = $conn->writeandread($up)) instanceof Guanjia\EPP\eppUpdateResponse) && ($response->Success())) {
            echo "Host $hostname updated, infoing\n";
            infohost($conn, $hostname);
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @return \Guanjia\EPP\iisEppInfoDomainResponse
 */
function infodomain($conn, $domainname) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $info = new Guanjia\EPP\eppInfoDomainRequest($domain);
        if ((($response = $conn->writeandread($info)) instanceof Guanjia\EPP\iisEppInfoDomainResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\iisEppInfoDomainResponse */
            echo $response->saveXML();
            return $response;
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}


/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @return \Guanjia\EPP\eppInfoDomainResponse
 */
function dnssecinfodomain($conn, $domainname) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $info = new Guanjia\EPP\eppInfoDomainRequest($domain);
        if ((($response = $conn->writeandread($info)) instanceof Guanjia\EPP\eppInfoDomainResponse) && ($response->Success())) {
            /* @var $response Guanjia\EPP\eppInfoDomainResponse */
            return $response;
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param \Guanjia\EPP\eppConnection $conn
 * @param string $contactid
 */
function infocontact($conn, $contactid) {
    try {
        $contact = new Guanjia\EPP\eppContactHandle($contactid);
        $info = new Guanjia\EPP\eppInfoContactRequest($contact);
        if ((($response = $conn->writeandread($info)) instanceof Guanjia\EPP\eppInfoContactResponse) && ($response->Success())) {
            echo $response->saveXML();
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }

}

/**
 * @param Guanjia\EPP\eppConnection $conn
 * @param string $hostname
 */
function infohost($conn, $hostname) {
    try {
        $host = new Guanjia\EPP\eppHost($hostname);
        $info = new Guanjia\EPP\eppInfoHostRequest($host);
        if ((($response = $conn->writeandread($info)) instanceof Guanjia\EPP\eppInfoHostResponse) && ($response->Success())) {
            echo $response->saveXML();
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }

}

/**
 * @param Guanjia\EPP\eppConnection  $conn
 * @param Guanjia\EPP\eppDomain $domain
 * @return mixed
 */
function checkdomain($conn, $domain) {
    $check = new Guanjia\EPP\eppCheckRequest($domain);
    if ((($response = $conn->writeandread($check)) instanceof Guanjia\EPP\eppCheckResponse) && ($response->Success())) {
        /* @var $response Guanjia\EPP\eppCheckResponse */
        $result = $response->getCheckedDomains();
        foreach ($result as $checkeddomain) {
            if ($checkeddomain['domainname'] == $domain->getDomainName()) {
                return $checkeddomain['available'];
            }
        }
    }
    return null;
}


/**
 * @param Guanjia\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $authcode
 */
function transferdomain($conn, $domainname, $authcode) {
    try {
        $domain = new Guanjia\EPP\eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $transfer = new Guanjia\EPP\eppTransferRequest(Guanjia\EPP\eppTransferRequest::OPERATION_REQUEST, $domain);
        if ((($response = $conn->writeandread($transfer)) instanceof Guanjia\EPP\eppTransferResponse) && ($response->Success())) {
            echo $response->saveXML();
        }

    } catch (Guanjia\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}