<?php
namespace Guanjia\EPP;

class verisignEppDeleteHostRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_DELETE;

    public function __construct($hostname,$sub_product = 'dotCOM')
    {
        parent::__construct();

        $delete = $this->appendChildes($this->createElement($this->type),[
            'host:' . $this->type => $this->appendChildes($this->setAttributes($this->createElement('host:'.$this->type),[
                'xmlns:host'            =>  'urn:ietf:params:xml:ns:host-1.0',
                'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:host-1.0 host-1.0.xsd',
            ]),[
                'host:name' =>  $hostname
            ])
        ]);

        $this->getCommand()->appendChild($delete);
        $this->appendExtension($sub_product);
    }
}