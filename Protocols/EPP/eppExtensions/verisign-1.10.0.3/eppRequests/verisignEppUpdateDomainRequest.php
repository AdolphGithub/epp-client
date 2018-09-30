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
        $this->createContact($element,$domainname,$addinfo,$removeinfo,$updateinfo);
//        $element->appendChild();
        $this->getCommand()->appendChild($element);
        // 添加扩展
        $this->setAtExtensions($sub_product);

    }

    /**
     * 创建核心更新内容
     * @param \DOMElement $dom
     * @param $objectname
     * @param $addinfo
     * @param $removeinfo
     * @param $updateinfo
     * @throws eppException
     */
    public function createContact(\DOMElement $dom,$objectname,$addinfo,$removeinfo,$updateinfo){

        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            if (strlen($objectname)) {
                $domainname = $objectname;
            } else {
                throw new eppException("对象名称必须是eppUpdateDomainRequest的有效字符串");
            }
        }

        $domain_update = $this->createElement("domain:update");
        $domain_update = $this->setAttributes($domain_update,[
            'xmlns:domain' =>  'urn:ietf:params:xml:ns:domain-1.0',
            'xmlns:xsi'   =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
        ]);
        $domain_name = $this->createElement('domain:name',$domainname);
        $domain_update->appendChild($domain_name);

        $add_info_ar = $this->addinfo($addinfo, $objectname);
        if( $add_info_ar ){
            $domain_update->appendChild($add_info_ar);
        }
        $remove_info_ar = $this->removeinfo($removeinfo,$objectname);
        if( $remove_info_ar ){
            $domain_update->appendChild($remove_info_ar);
        }
        $update_info_ar = $this->updateinfo($updateinfo,$objectname);
        if( $update_info_ar ){
            $domain_update->appendChild($update_info_ar);
        }
        $dom->appendChild($domain_update);
    }

    /**
     * 添加 info
     * @param $addinfo
     * @param eppDomain $domain
     * @return array|\DOMElement
     */
    public function addinfo($addinfo, eppDomain $domain){
        $addcmd = [];
        if( $addinfo instanceof eppDomain && $addinfo ){
            $contacts = $addinfo->getContacts();
            $contact_ar = [];
            if (is_array($contacts) && $contacts ) {
                foreach ($contacts as $contact) {
                    /* @var eppContactHandle $contact */
                    $contact_ar['domain:contact type=' . $contact->getContactType()] = $contact->getContactHandle();
                }
            }
            $domain_add = $this->createElement('domain:add');

            $hosts = $addinfo->getHosts();
            if (is_array($hosts) && (count($hosts))) {
                foreach ($hosts as $host) {
                    /* @var eppHost $host  */
                    $hostObj_ar[] = $this->createElement('domain:hostObj',$host->getHostname());
                }

                $domain_ns = $this->appendChildes($this->createElement('domain:ns'),$hostObj_ar);
                $domain_add->appendChild($domain_ns);
            }
            $addcmd = $this->appendChildes($domain_add,$contact_ar);
        }

        return $addcmd;
    }

    /**
     * 删除得东西
     * @param $removeinfo
     * @return array|\DOMElement
     */
    public function removeinfo($removeinfo){
        $data = [];
        if( $removeinfo instanceof  eppDomain && $removeinfo){

            $contacts = $removeinfo->getContacts();
            $contact_ar = [];
            if (is_array($contacts) && $contacts ) {
                foreach ($contacts as $contact) {
                    /* @var eppContactHandle $contact */
                    $contact_ar['domain:contact type=' . $contact->getContactType()] = $contact->getContactHandle();
                }
            }
            $domain_add = $this->createElement('domain:rem');

            $hosts = $removeinfo->getHosts();
            if (is_array($hosts) && (count($hosts))) {
                foreach ($hosts as $host) {
                    /* @var eppHost $host  */
                    $hostObj_ar[] = $this->createElement('domain:hostObj',$host->getHostname());
                }

                $domain_ns = $this->appendChildes($this->createElement('domain:ns'),$hostObj_ar);
                $domain_add->appendChild($domain_ns);
            }
            $data = $this->appendChildes($domain_add,$contact_ar);
        }
        return $data;
    }

    /**
     * 更新信息
     * @param $updateinfo
     * @return array|\DOMElement
     */
    public function updateinfo($updateinfo){
        $data = [];
        if( $updateinfo instanceof eppDomain && $updateinfo){
            $domain_chg = $this->createElement('domain:chg');
            if ($updateinfo->getRegistrant()) {
                $domain_registrant = ['domain:registrant'=> $updateinfo->getRegistrant()];
            }

            if (strlen($updateinfo->getAuthorisationCode())) {
                $authInfo_pw = [
                    'domain:authInfo'  => [
                        'domain:pw' => $this->createCDATASection($updateinfo->getAuthorisationCode())
                    ]
                ];
                $domain_registrant[]=$authInfo_pw;
            }
            $data = $this->appendChildes($domain_chg,$domain_registrant);
        }

        return $data;
    }

    protected function setAtExtensions($sub_contact,$secdns_add = null,$secdns_rem = null,$coa_key=null,$coa_attr = null)
    {

        $namestore_ext = $this->createElement('namestoreExt:namestoreExt');
        $this->appendExtension($sub_contact,false);
        $this->setAttributes($namestore_ext,[
            'xmlns:namestoreExt' =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]);

        /**
         * 加密方式
         */
        $Algorithm = [
            '1'=>"RSA/MD5",
            '2'=>"Diffie-Hellman",
            '3'=>"DSA/SHA-1",
            '4'=>"Elliptic Curve",
            '5'=>"RSA/SHA-1",
            '6'=>"DSA-NSEC3-SHA1",
            '7'=>"RSASHA1-NSEC3-SHA1",
            '8'=>"RSA/SHA-256",
            '10'=>"RSA/SHA-512",
            '12'=>"ECC-GOST",
            '13'=>"ECDSA Curve P-256 with SHA-256",
            '14'=>"ECDSA Curve P-384 with SHA-384",
            '252'=>"Indirect",
            '253'=>"Private DNS",
            '254'=>"Private OID",

        ];
        /**
         * 解释类型
         */
        $Digest_Type = [
            '1'  =>  "SHA-1",
            '2'  =>  "SHA-256",
            '3'  =>  "GOST R 34.11-94",
            '4'  =>  "SHA-384",
        ];
        $secdns_rem = [[885,'Diffie-Hellman','SHA-1','8491674BFF957211D129B0DFE9410AF753559D4B'], [885,"RSA/MD5","SHA-384",'8491674BFF957211D129B0DFE9410AF753559D4B'], [885,"DSA/SHA-1", "SHA-256",'8491674BFF957211D129B0DFE9410AF753559D4B'],];
        if( $secdns_rem ){
            foreach ($secdns_rem as $key => $val){
                $secdns_rem_ar[]  = $this->appendChildes($this->createElement('secDNS:dsData'),[
                    'secDNS:keyTag' => $val['0'],
                    'secDNS:alg' =>  array_search($val['1'],$Algorithm),
                    'secDNS:digestType' =>array_search($val[2],$Digest_Type),
                    'secDNS:digest' => "$val[3]",
                ]);
            }
        }
        $secdns_add = [[885,"RSA/SHA-512",'SHA-1','8491674BFF957211D129B0DFE9410AF753559D4B'], ];

        if( $secdns_add ){
            foreach ($secdns_add as $key => $val){
                $secdns_add_ar[]  = $this->appendChildes($this->createElement('secDNS:dsData'),[
                    'secDNS:keyTag' => $val['0'],
                    'secDNS:alg' =>  array_search($val['1'],$Algorithm),
                    'secDNS:digestType' =>array_search($val[2],$Digest_Type),
                    'secDNS:digest' => "$val[3]",
                ]);
            }
        }


       /* $coa_attr = [
            //循环
            'coa:attr'  => [
                'coa:key' => '',
                'coa:value' => '',
            ]
        ];

        $coa_key = [
            //循环
            'coa:key'  =>  '',
        ];
*/

        $secdns_update = $this->createElement('secDNS:update');
        $this->setAttributes($secdns_update,[
            'xmlns:secDNS' => 'urn:ietf:params:xml:ns:secDNS-1.1',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => 'urn:ietf:params:xml:ns:secDNS-1.1 secDNS-1.1.xsd',
        ]);

        if( $secdns_rem_ar ){
            $secDNS_rem  = $this->createElement("secDNS:rem");
            $this->appendChildes($secDNS_rem,$secdns_rem_ar);
            $secdns_update->appendChild($secDNS_rem);
        }

        if( $secdns_add_ar ){
            $secDNS_rem  = $this->createElement("secDNS:add");
            $this->appendChildes($secDNS_rem,$secdns_add_ar);
            $secdns_update->appendChild($secDNS_rem);
        }
        if( $secdns_rem_ar || $secdns_add_ar ){
            $this->getExtension()->appendChild($secdns_update);
        }


        $coa_update = $this->createElement('coa:update');
            $this->setAttributes($coa_update,[
                'xmlns:coa'  =>  'urn:ietf:params:xml:ns:coa-1.0',
                'xmlns:xsi'  =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'  =>  'urn:ietf:params:xml:ns:coa-1.0 coa-1.0.xsd',
            ]);

        if( $coa_attr ){
            $coa_put = $this->createElement('coa:put');

            $this->appendChildes($coa_put,$coa_attr);

            $this->appendChildes($secdns_update,$coa_put);
        }
        if( $coa_key ){
            $this->appendChildes($coa_update,$coa_key);
        }
        if( $coa_key || $coa_attr ){
            $this->getExtension()->appendChild($coa_update);
        }




    }

}