<?php

namespace Guanjia\EPP;


class verisignEppUpdateDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;
    public $domainobject = null;
    private $forcehostattr = false;
    public function __construct($domainname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $namespacesinroot=true,$sub_product = 'dotCOM')
    {
        parent::__construct();
        $element = $this->createElement($this->type);

        $domain_update = $this->createElement('domain:'.$this->type);
        $domain_update = $this->setAttributes($domain_update,[
            'xmlns:contact' =>  'urn:ietf:params:xml:ns:contact-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd'
        ]);
        $element->appendChild($domain_update);

        // 批量设值
        $this->createContact($domainname, $addinfo, $removeinfo, $updateinfo);
        $element->appendChild($this->domainobject);
        $this->getCommand()->appendChild($element);
       $this->setAtExtensions($sub_product);
    }

    /**
     * @param $objectname
     * @param eppDomain $addinfo
     * @param eppDomain $removeinfo
     * @param eppDomain $updateinfo
     * @throws eppException
     */
    public function createContact($objectname,$addinfo,$removeinfo,$updateinfo)
    {


        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            if (strlen($objectname)) {
                $domainname = $objectname;
            } else {
                throw new eppException("对象名称必须是eppUpdateDomainRequest的有效字符串");
            }
        }

        if (($addinfo instanceof eppDomain) || ($removeinfo instanceof eppDomain) || ($updateinfo instanceof eppDomain)) {
            $this->updateDomain($domainname, $addinfo, $removeinfo, $updateinfo);
        } else {
            throw new eppException('addinfo, removeinfo and updateinfo needs to be eppDomain object on eppUpdateDomainRequest');
        }

    }

    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo) {
        #
        # Object create structure
        #
        $this->domainobject = $this->createElement('domain:name', $domainname);
        if ($addInfo instanceof eppDomain && $addInfo) {
            $addcmd = $this->createElement('domain:add');
            $this->addDomainChanges($addcmd, $addInfo);
            $this->domainobject->appendChild($addcmd);
        }
        if ($removeInfo instanceof eppDomain && $removeInfo) {
            $remcmd = $this->createElement('domain:rem');
            $this->addDomainChanges($remcmd, $removeInfo);
            $this->domainobject->appendChild($remcmd);
        }
        if ($updateInfo instanceof eppDomain && $updateInfo) {
            $chgcmd = $this->createElement('domain:chg');
            $this->addDomainChanges($chgcmd, $updateInfo);
            $this->domainobject->appendChild($chgcmd);
        }
    }


    /**
     * @param \DOMElement $dom
     * @param eppDomain $domain
     * @return \DOMElement
     */
   /* private function updateDomain(\DOMElement $element,$objectname,$addinfo,$removeinfo,$updateinfo)
    {

        $domain_add = $this->createElement('domain:add');
        foreach($domain_host_objs->getStreets() as $street){
            $domain_add->appendChild($this->createElement('contact:street',$street));
        }

        $domain_host_obj = $this->createElement('domain:ns');
        foreach($domain_host_objs->gethostObj() as $host){
            $domain_host_obj->appendChild($this->createElement('domain:hostObj',$host));
        }

        $rem_ns_domain_host_obj = $this->createElement("domain:ns");
         foreach($epp_postal_info->getStreets() as $host){
             $rem_ns_domain_host_obj->appendChild($this->createElement('domain:hostObj',$host));
         }


        $contacts = $domain->getContacts();
        if (is_array($contacts)) {
            foreach ($contacts as $contact) {
                /* @var eppContactHandle $contact */
    /*  $this->addDomainContact($element, $contact->getContactHandle(), $contact->getContactType());
  }
}


return $this->appendChildes($dom,[
  'domain:name'    =>  '',
  'domain:add'   =>  [
      'contact:postalInfo type=' . $epp_postal_info->getType()    =>  [
          'domain:ns'  =>  $domain_host_obj,
      ],
      "domain:contact" => $domain_contact,
      /*
          <domain:contact type="admin">T09571012</domain:contact>
          <domain:contact type="tech">T09571012</domain:contact>
          <domain:status s="clientDeleteProhibited"/>
          <domain:status s="clientUpdateProhibited"/>
       * */
    /*  ],
      'domain:rem'  =>  [
          'domain:ns'  =>  $rem_ns_domain_host_obj,
          /*
              <domain:contact type="admin">T09571011</domain:contact>
              <domain:contact type="tech">T09571011</domain:contact>
              <domain:status s="clientHold"/>
              <domain:status s="clientTransferProhibited"/>
           */
    /*   ],
       'domain:chg'  => [
           'domain:registrant' => '',
           'domain:authInfo' => [
               'domain:pw' => ''
           ],
       ],

   ]);
}
/**
*
* @param \domElement $element
* @param eppDomain $domain
*/
    protected function addDomainChanges($element, eppDomain $domain) {
        if ($domain->getRegistrant()) {
            $element->appendChild($this->createElement('domain:registrant', $domain->getRegistrant()));
        }
        $hosts = $domain->getHosts();
        if (is_array($hosts) && (count($hosts))) {
            $nameservers = $this->createElement('domain:ns');
            foreach ($hosts as $host) {
                /* @var eppHost $host */
                if (($this->getForcehostattr()) ||  (is_array($host->getIpAddresses()))) {
                    $nameservers->appendChild($this->addDomainHostAttr($host));
                } else {
                    $nameservers->appendChild($this->addDomainHostObj($host));
                }
            }
            $element->appendChild($nameservers);
        }
        $contacts = $domain->getContacts();
        if (is_array($contacts)) {
            foreach ($contacts as $contact) {
                /* @var eppContactHandle $contact */
                $this->addDomainContact($element, $contact->getContactHandle(), $contact->getContactType());
            }
        }
        $statuses = $domain->getStatuses();
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $this->addDomainStatus($element, $status);
            }
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $pw = $this->createElement('domain:pw');
            $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            $authinfo->appendChild($pw);
            $element->appendChild($authinfo);
        }
    }


    protected function setAtExtensions12($sub_contact)
    {

        $namestore_ext = $this->createElement('namestoreExt:namestoreExt');

         $this->setAttributes($namestore_ext,[
            'xmlns:namestoreExt' =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]);

        $this->getExtension()
            ->appendChild($this->appendChildes($this->setAttributes($this->createElement('secDNS:update'),[
                'xmlns:secDNS' => 'urn:ietf:params:xml:ns:secDNS-1.1',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'urn:ietf:params:xml:ns:secDNS-1.1 secDNS-1.1.xsd',
            ]),[
                'secDNS:rem' => [
                    //循环
                    'secDNS:dsData' => [
                        'secDNS:keyTag' => '',
                        'secDNS:alg' => '',
                        'secDNS:digestType' => '',
                        'secDNS:digest' => '',
                    ]
                ],

                'secDNS:add'  => [
                    //循环
                    'secDNS:dsData'  =>  [
                        'secDNS:keyTag'  =>  '',
                        'secDNS:alg'  =>  '',
                        'secDNS:digestType'  =>  '',
                        'secDNS:digest'  =>  '',
                    ]
                ],
            ]))
            ->appendChild($this->appendChildes($this->setAttributes($this->createElement('coa:update'),[
                'xmlns:coa'  =>  'urn:ietf:params:xml:ns:coa-1.0',
                'xmlns:xsi'  =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'  =>  'urn:ietf:params:xml:ns:coa-1.0 coa-1.0.xsd',
            ]), [
                'coa:rem'  =>  [
                    //循环
                    'coa:key'  =>  '',
                ],
                'coa:put'  => [
                    //循环
                    'coa:attr'  => [
                        'coa:key' => '',
                        'coa:value' => '',
                    ]
                ]
            ]));


        $subProduct = $this->createElement("namestoreExt:subProduct", $sub_contact);
        $namestore_ext->appendChild($subProduct);
        $this->getExtension()->appendChild($namestore_ext);

    }

    protected function setAtExtensions($sub_contact)
    {

        $namestore_ext = $this->createElement('namestoreExt:namestoreExt');

        $this->setAttributes($namestore_ext,[
            'xmlns:namestoreExt' =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]);


        $subProduct = $this->createElement("namestoreExt:subProduct", $sub_contact);
        $namestore_ext->appendChild($subProduct);
        $this->getExtension()->appendChild($namestore_ext);

    }



    /**
     *
     * @param \domElement $element
     * @param string $status
     */
    protected function addDomainStatus($element, $status) {
        $stat = $this->createElement('domain:status');
        $stat->setAttribute('s', $status);
        $element->appendChild($stat);
    }

    /**
     *
     * @param \domElement $domain
     * @param string $contactid
     * @param string $contacttype
     */
    protected function addDomainContact($domain, $contactid, $contacttype) {
        $domaincontact = $this->createElement('domain:contact', $contactid);
        $domaincontact->setAttribute('type', $contacttype);
        $domain->appendChild($domaincontact);
    }

    /**
     *
     * @param eppHost $host
     * @return \domElement
     */
    protected function addDomainHostAttr(eppHost $host) {

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
     * @return \domElement
     */
    protected function addDomainHostObj(eppHost $host) {
        $ns = $this->createElement('domain:hostObj', $host->getHostname());
        return $ns;
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }


}