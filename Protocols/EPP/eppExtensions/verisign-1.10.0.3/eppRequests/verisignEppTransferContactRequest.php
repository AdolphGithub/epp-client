<?php
namespace Guanjia\EPP;

class verisignEppTransferContactRequest extends verisignBaseRequest
{
    const ACTION_REQUEST    = 'request';
    const ACTION_QUERY      = 'query';
    const ACTION_APPROVE    = 'approve';
    const ACTION_REJECT     = 'reject';
    const ACTION_CANCEL     = 'cancel';

    private $type = 'transfer';

    public function __construct($contact_id,$password,$action = 'request',$sub_product = 'dotCOM')
    {
        parent::__construct();

        $transfer = $this->appendChildes($this->setAttributes($this->createElement($this->type),[
            'op'    =>  $action
        ]),[
            'contact:transfer'  =>  $this->appendChildes($this->setAttributes('contact:'. $this->type,[
                'xmlns:contact'     =>  'urn:ietf:params:xml:ns:contact-1.0',
                'xmlns:xsi'         =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'=>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd',
            ]),[
                'contact:id'    =>  $contact_id,
                'contact:authInfo'  =>  [
                    'contact:pw'    =>  $password
                ]
            ])
        ]);

        $this->getCommand()->appendChild($transfer);
        $this->appendExtension($sub_product);
    }
}