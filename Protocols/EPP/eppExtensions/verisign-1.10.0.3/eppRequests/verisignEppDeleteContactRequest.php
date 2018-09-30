<?php
namespace Guanjia\EPP;

class verisignEppDeleteContactRequest extends verisignBaseRequest
{
    public function __construct($contact_id,$extension = 'dotCOM',$type = eppRequest::TYPE_DELETE)
    {
        parent::__construct();
        $this->createContact($contact_id,$type);
        $this->appendExtension($extension);
    }


    private function createContact($contact_id,$type)
    {
        $delete = $this->createElement($type);

        $contact_delete = $this->setAttributes('contact:'. $type,[
            'xmlns:contact' =>  'urn:ietf:params:xml:ns:contact-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd'
        ]);

        $contact_delete->appendChild(
            $this->createElement('contact:id',$contact_id)
        );
        $delete->appendChild($contact_delete);
        $this->getCommand()->appendChild($delete);
    }
}