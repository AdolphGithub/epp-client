<?php

namespace Guanjia\EPP;


class verisignEppCreateDomainRequest extends eppRequest
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

    function __construct($createrequest,$sub_contact = 'dotCOM',$type = eppRequest::TYPE_CREATE) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->contact_object = $this->createElement('domain:'.$type);
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');
        $check->appendChild($this->contact_object);
        $this->getCommand()->appendChild($check);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($sub_contact);
        $this->addContacts($createrequest);
    }


    public function addContacts(eppContact $contact){

    }


    /**
     * 添加扩展.
     * @param string $sub_product
     */
    public function appendExtension($sub_product = 'dotCom')
    {
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt', 'http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $namestoreExt->setAttribute('xsi:schemaLocation', 'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd');
        $subProduct = $this->createElement("namestoreExt:subProduct", $sub_product);
        $namestoreExt->appendChild($subProduct);
        $this->getExtension()->appendChild($namestoreExt);
    }
}