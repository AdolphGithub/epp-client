<?php
namespace Guanjia\EPP;

/**
 * 检查域名.
 * Class verisignEppCheckDomainRequest
 * @package Guanjia\EPP
 */
class verisignEppCheckContactRequest extends verisignBaseRequest
{
    /**
     * @var bool
     */
    private $forcehostattr = false;

    /**
     * ContactObject object to add namespaces to
     * @var \DomElement
     */
    public $contact_object = null;

    function __construct($checkrequest,$sub_contact = 'dotCOM',$type = eppRequest::TYPE_CHECK) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->contact_object = $this->setAttributes('contact:'.$type,[
            'xmlns:contact'     =>  'urn:ietf:params:xml:ns:contact-1.0',
            'xmlns:xsi'         =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'=>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd'
        ]);

        $check->appendChild($this->contact_object);
        $this->getCommand()->appendChild($check);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($sub_contact);
        $this->addContacts($checkrequest);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }

    public function addContacts(array $contacts)
    {
        foreach($contacts as $contact){
            $this->contact_object->appendChild($this->createElement('contact:id', $contact));
        }
    }
}