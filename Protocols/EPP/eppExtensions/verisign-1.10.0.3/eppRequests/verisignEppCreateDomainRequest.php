<?php

namespace Guanjia\EPP;


class verisignEppCreateDomainRequest extends verisignBaseRequest
{
    //联系人类别
    const CONTACT_TYPE_REGISTRANT = 'reg';
    const CONTACT_TYPE_ADMIN = 'admin';
    const CONTACT_TYPE_TECH = 'tech';
    const CONTACT_TYPE_BILLING = 'billing';
    /**
     * @var bool
     */
    private $forcehostattr = false;

    /**
     * ContactObject object to add namespaces to
     * @var \DomElement
     */
    public $contact_object = null;

    function __construct($createinfo,$sub_contact = 'dotCOM',$code = null,$type = eppRequest::TYPE_CREATE) {
        parent::__construct();
        $create = $this->createElement($type);
        $this->contact_object = $this->createElement('domain:'.$type);
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');

        $create->appendChild($this->contact_object);

        $this->getCommand()->appendChild($create);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($sub_contact);//扩展

        $this->addContacts($createinfo);
        $this->setCode($code);
        $this->addSessionId();
    }


    public function addContacts( eppDomain $createinfo){
        if ($createinfo instanceof eppDomain) {
            $this->setDomain($createinfo);
        } else {
            throw new eppException('createinfo必须是eppCreateDomainRequest的eppDomain类型');
        }

    }


    /*
     * @param eppSecdns $secdns
     */
    public function addSecdns($secdns) {
        /* @var eppSecDNS $secdns */
        if (!$this->extension) {
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        $seccreate = $this->createElement('secDNS:create');
        $seccreate->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
        if ($secdns->getKeytag()) {
            /*
             * Keytag found, assuming client wants to add dnssec data via dsData interface
             * http://tools.ietf.org/search/rfc5910#section-4.1
             */
            $secdsdata = $this->createElement('secDNS:dsData');
            $secdsdata->appendChild($this->createElement('secDNS:keyTag', $secdns->getKeytag()));
            $secdsdata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $secdsdata->appendChild($this->createElement('secDNS:digestType', $secdns->getDigestType()));
            $secdsdata->appendChild($this->createElement('secDNS:digest', $secdns->getDigest()));
            if ($secdns->getPubkey()) {
                /*
                 * Pubkey found, adding option key data to the request
                 */
                $seckeydata = $this->createElement('secDNS:keyData');
                $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
                $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
                $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
                $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
                $secdsdata->appendChild($seckeydata);
            }
            $seccreate->appendChild($secdsdata);
        } else {
            /*
             * Keytag not found, assuming client wants to add dnssec data via keyData interface
             * http://tools.ietf.org/search/rfc5910#section-4.2
             */
            $seckeydata = $this->createElement('secDNS:keyData');
            $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
            $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
            $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
            $seccreate->appendChild($seckeydata);
        }
        $this->extension->appendChild($seccreate);

        // Put session id at the end of the EPP command chain
        $this->addSessionId();
    }

    /**
     *
     * @param eppDomain $domain
     * @return \DOMElement
     * @throws eppException
     */
    public function setDomain(eppDomain $domain) {
        if (!strlen($domain->getDomainname())) {
            throw new eppException('创建域请求中没有有效的域名');
        }
        if (!strlen($domain->getRegistrant())) {
            throw new eppException('在创建域请求中没有有效的注册人');
        }
        #
        # Object create structure
        #
        $this->contact_object->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($domain->getPeriod() > 0) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', $domain->getPeriodUnit());
            $this->contact_object->appendChild($domainperiod);
        }
        $nsobjects = $domain->getHosts();
        if ($domain->getHostLength() > 0) {
            $nameservers = $this->createElement('domain:ns');
            foreach ($nsobjects as $nsobject) {
                /* @var $nsobject eppHost */
                if (($this->getForcehostattr()) || ($nsobject->getIpAddressCount() > 0)) {
                    $nameservers->appendChild($this->addDomainHostAttr($nsobject));
                } else {
                    $nameservers->appendChild($this->addDomainHostObj($nsobject));
                }
            }
            $this->contact_object->appendChild($nameservers);
        }
        $this->contact_object->appendChild($this->createElement('domain:registrant', $domain->getRegistrant()));
        $contacts = $domain->getContacts();
        if ($domain->getContactLength() > 0) {
            foreach ($contacts as $contact) {
                /* @var $contact eppContactHandle */
                $this->addDomainContact($this->contact_object, $contact->getContactHandle(), $contact->getContactType());
            }
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->contact_object->appendChild($authinfo);
        }

        // Check for DNSSEC keys and add them
        if ($domain->getSecdnsLength() > 0) {
            for ($i = 0; $i < $domain->getSecdnsLength(); $i++) {
                $sd = $domain->getSecdns($i);
                /* @var $sd eppSecdns */
                if ($sd) {
                    $ext = new eppSecdns();
                    $ext->copy($sd);
                    $this->addSecdns($ext);
                }
            }
        }

    }

    /**
     *
     * @param \DOMElement $domain
     * @param string $contactid
     * @param string $contacttype
     */
    private function addDomainContact($domain, $contactid, $contacttype) {
        $domaincontact = $this->createElement('domain:contact', $contactid);
        $domaincontact->setAttribute('type', $contacttype);
        $domain->appendChild($domaincontact);
    }

    /**
     *
     * @param eppHost $host
     * @return \DOMElement
     */
    private function addDomainHostAttr(eppHost $host) {

        $ns = $this->createElement('domain:hostAttr');
        $ns->appendChild($this->createElement('domain:hostName', $host->getHostname()));
        if ($host->getIpAddressCount() > 0) {
            $addresses = $host->getIpAddresses();
            foreach ($addresses as $address => $type) {
                $ip = $this->createElement('domain:hostAddr', $address);
                $ip->setAttribute('ip', $type);
                $ns->appendChild($ip);
            }
        }
        return $ns;
    }

    /**
     *
     * @param eppHost $host
     * @return \DOMElement
     */
    private function addDomainHostObj(eppHost $host) {
        $ns = $this->createElement('domain:hostObj', $host->getHostname());
        return $ns;
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }

    /**
     * update code
     * @param $codes
     */
    public function setCode($codes){
        if(count($codes) > 0) {
            $verification_codes = [];

            while($code = array_shift($codes)) {
                array_push($verification_codes,$this->createElement('verificationCode:code',$code));
            }

            $verification_code = $this->appendChildes($this->setAttributes('verificationCode:encodedSignedCode',[
                'xmlns:verificationCode'    =>  'urn:ietf:params:xml:ns:verificationCode-1.0'
            ]),[
                'verificationCode:code' => $codes
            ]);

            $this->getExtension()->appendChild($verification_code);
        }
    }
}