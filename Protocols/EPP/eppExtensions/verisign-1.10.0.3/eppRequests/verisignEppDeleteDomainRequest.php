<?php
namespace Guanjia\EPP;

class verisignEppDeleteDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_DELETE;

    public function __construct($domain_name,$codes,$domain_names = [],$sub_product = 'dotCOM')
    {
        parent::__construct();

        $domain_delete = $this->appendChildes($this->setAttributes('domain:'.$this->type,[
            'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
        ]),[
            'domain:name'   =>  $domain_name
        ]);

        $delete = $this->createElement($this->type);
        $delete->appendChild($domain_delete);
        $this->getCommand()->appendChild($delete);
        $this->createExtension($sub_product,$codes,$domain_names);
    }

    /**
     * 创建extension标签.
     * @param $sub_product
     * @param $codes
     * @param $domain_names
     */
    private function createExtension($sub_product,$codes,$domain_names)
    {
        $namestore = $this->appendChildes($this->setAttributes('namestoreExt:namestoreExt',[
            'xmlns:namestoreExt'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]),[
            'namestoreExt:subProduct'   =>  strtoupper($sub_product)
        ]);

        $this->getExtension()->appendChild($namestore);

        // 创建codes
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

        // 创建domain
        if(count($domain_names)) {
            $relDom = $this->setAttributes('relDom:delete', [
                'xmlns:relDom' => 'http://www.verisign.com/epp/relatedDomain-1.0',
            ]);

            foreach ($domain_names as $domain) {
                $relDom->appendChild($this->createElement('relDom:name', $domain));
            }

            $this->getExtension()->appendChild($relDom);
        }

        $this->getCommand()->appendChild($this->getExtension());
    }
}