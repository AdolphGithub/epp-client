<?php
require __DIR__ . '/../../Loader.php';

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppContactHandle;
use Guanjia\EPP\verisignEppInfoDomainRequest;
use Guanjia\EPP\verisignEppUpdateDomainRequest;
use Guanjia\EPP\verisignEppCreateDomainRequest;
use Guanjia\EPP\eppUpdateDomainResponse;
use Guanjia\EPP\eppHost;
use Guanjia\EPP\eppDomain;

/*
 * 这个示例脚本修改您的帐户中的域名
 * 
 * metaregistrar的域名服务器被用作域名服务器
 * 在这个代币中，相同的联系人id用于注册人、管理联系人、技术联系人和账单联系人。
 * 推荐使用的方法是使用您自己的技术联系和账单联系，并将注册者和管理员联系到域名所有者或转售商。
 */


if ($argc <= 1) {
    echo "Usage: modifydomain.php <domainname>\n";
    echo "Please enter the domain name to be modified\n\n";
    die();
}

//$domainname = $argv[1];

echo "Modifying \n";

// Please enter your own settings file here under before using this example
if ($conn = eppConnection::create('')) {
    // Connect to the EPP server
    if ($conn->login()) {

        $domainname = "sadfa1dfssdsdsdfdasdfs.com";
        $contactid = "MRG5bacadc22ff8e";
        $nameservers ='';
        if ($contactid) {
            createdomain($conn, $domainname, $contactid, $contactid, $contactid, $contactid, $nameservers);
        }
//        $domainname = "EXAM84123456.COM";
        modifydomain($conn, $domainname, '54556489454', 'MRG5bac33bdabc83','MRG5bac33bdabc83', null, array('ns1.metaregistrar.nl', 'ns2.metaregistrar.nl'));
        $conn->logout();
    }
}
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    /* @var $conn Guanjia\EPP\eppConnection */
    $domain = new eppDomain($domainname, $registrant);
    $domain->setRegistrant(new eppContactHandle($registrant));
    $domain->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
    $domain->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
//    $domain->addContact(new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING));
    $domain->setAuthorisationCode('sampleAuthInfo-1');
//    var_dump($domain);die;
    if (is_array($nameservers)) {
        foreach ($nameservers as $nameserver) {

            $domain->addHost(new eppHost($nameserver));
        }
    }


    $create = new verisignEppCreateDomainRequest($domain);
    if ($response = $conn->request($create)) {
        /* @var $response Guanjia\EPP\eppCreateDomainResponse */
        echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        return $response->getDomainName();
    }

}

/**
 * @param $conn eppConnection
 * @param $domainname string
 * @param null $registrant string
 * @param null $admincontact string
 * @param null $techcontact string
 * @param null $billingcontact string
 * @param null $nameservers string
 */
function modifydomain($conn, $domainname, $registrant = null, $admincontact = null, $techcontact = null, $billingcontact = null, $nameservers = null,array $secdnsadd = null,array $secdnsrem = null,array $coarem = null ,array $coa_attr_put = null) {
    $response = null;
    try {
        // 首先，检索当前域信息。命名服务器可以被取消，然后再设置。
        $del = null;
        $domain = new eppDomain($domainname);
        $info = new verisignEppInfoDomainRequest($domain,'all');
        if ($response = $conn->request($info)) {
            // 如果有新的域名服务器，请用旧的域名删除它们。
            if (is_array($nameservers)) {
                /* @var Guanjia\EPP\eppInfoDomainResponse $response */
                $oldns = $response->getDomainNameservers();
                if (is_array($oldns)) {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    foreach ($oldns as $ns) {
                        $del->addHost($ns);
                    }
                }
            }

            if ($admincontact) {
                $oldadmin = $response->getDomainContact(eppContactHandle::CONTACT_TYPE_ADMIN);
                if ($oldadmin == $admincontact) {
                    $admincontact = null;
                } else {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    $del->addContact(new eppContactHandle($oldadmin, eppContactHandle::CONTACT_TYPE_ADMIN));
                }
            }
            if ($techcontact) {
                $oldtech = $response->getDomainContact(eppContactHandle::CONTACT_TYPE_TECH);
                if ($oldtech == $techcontact) {
                    $techcontact = null;
                } else {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    $del->addContact(new eppContactHandle($oldtech, eppContactHandle::CONTACT_TYPE_TECH));
                }
            }
        }
        // 在UpdateDomain命令中，您可以设置或添加参数
        // - 注册人总是被设置（你只能有一个注册人）
        // - 管理、技术、账单联系人（你可以有多个联系人，别忘了删除旧的联系人）。
        // - 添加了命名服务器（您可以有多个命名服务器，不要忘记删除旧的名称服务器。
        $mod = null;
        if ($registrant) {
            $mod = new eppDomain($domainname);
            $mod->setRegistrant(new eppContactHandle($registrant));
        }
        $add = null;
        if ($admincontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
        }
        if ($techcontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
        }
        if ($billingcontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING));
        }
        if (is_array($nameservers)) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            foreach ($nameservers as $nameserver) {
                $add->addHost(new eppHost($nameserver));
            }
        }
        $update = new verisignEppUpdateDomainRequest($domain, $add, $del, $mod);

        //echo $update->saveXML();

        if ($response = $conn->request($update)) {
            /* @var eppUpdateDomainResponse $response */
//            echo $response->getResultMessage() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
//        if ($response instanceof verisignEppUpdateDomainResponse) {
//            echo $response->textContent . "\n";
//        }
    }
}