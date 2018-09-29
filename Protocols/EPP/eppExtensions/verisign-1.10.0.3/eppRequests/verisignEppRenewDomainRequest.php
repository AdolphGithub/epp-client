<?php
namespace Guanjia\EPP;

class verisignEppRenewDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_RENEW;

    public function __construct($domain_info,$codes,$other_domain = [],$sub_product = 'dotCOM')
    {
        parent::__construct();

        $domain_renew = $this->appendChildes($this->setAttributes('domain:'.$this->type,[
            'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
        ]),[
            'domain:name'   =>  $domain_info['domain_name'],
            'domain:curExpDate' =>  $domain_info['current_expire_date'],
            'domain:period unit=y'  =>  $domain_info['period']
        ]);

        $renew = $this->createElement($this->type);
        $renew->appendChild($domain_renew);
        $this->getCommand()->appendChild($renew);
        $this->createExtension($sub_product,$codes,$other_domain);
    }

    /**
     * 创建extension.
     * @param $sub_product
     * @param $codes
     * @param array $other_domain
     */
    public function createExtension($sub_product,$codes,$other_domain = [])
    {
        $namestore = $this->appendChildes($this->setAttributes('namestoreExt:namestoreExt',[
            'xmlns:namestoreExt'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]),[
            'namestoreExt:subProduct'   =>  strtoupper($sub_product)
        ]);

        $this->getExtension()->appendChild($namestore);

        if($codes) {

        }

        if(count($other_domain) > 0){
            foreach($other_domain as $domain){
                $this->getExtension()
                    ->appendChild($this->appendChildes($this->createElement('relDom:domain'),[
                    'relDom:name'   =>  $domain['domain_name'],
                    'relDom:curExpDate' =>  $domain['current_expire_date'],
                    'relDom:period unit=y' =>  $domain['period'],
                ]));
            }
        }

        $this->getCommand()->appendChild($this->getExtension());
    }
}