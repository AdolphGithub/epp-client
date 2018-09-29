<?php
namespace Guanjia\EPP;

class verisignEppSyncDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;

    public function __construct($domain_name,array $date,$sub_product = 'dotCOM')
    {
        parent::__construct();

        $update = $this->appendChildes($this->createElement($this->type),[
            'domain:'.$this->type => $this->appendChildes($this->setAttributes('domain:'.$this->type,[
                'xmlns:domain'  => 'urn:ietf:params:xml:ns:domain-1.0',
                'xmlns:xsi'     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
            ]),[
                'domain:name'   =>  $domain_name,
                'domain:chg'    =>  $this->createElement('domain:chg')
            ])
        ]);

        $this->getCommand()->appendChild($update);

        $this->appendExtension($sub_product,false);

        $sync = $this->appendChildes($this->setAttributes('sync:update',[
            'xmlns:sync'    =>  'http://www.verisign.com/epp/sync-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign.com/epp/sync-1.0 sync-1.0.xsd'
        ]),[
            'sync:expMonthDay'  =>  sprintf('--%2d-%2d',$date['month'],$date['day'])
        ]);

        $this->getExtension()->appendChild($sync);

        $this->getCommand()->appendChild($this->getExtension());
    }
}