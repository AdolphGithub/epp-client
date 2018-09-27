<?php
namespace Guanjia\EPP;

class eppDomainRequest extends eppRequest {

    /**
     * @var bool
     */
    private $forcehostattr = false;

    /**
     * DomainObject object to add namespaces to
     * @var \DomElement
     */
    public $domainobject = null;

    function __construct($checkrequest,$type = eppRequest::TYPE_CHECK) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->domainobject = $this->createElement('domain:'.$type);
        if (!$this->rootNamespaces()) {
            $this->domainobject->setAttribute('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0');
        }
        $this->domainobject->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->domainobject->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd');
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }
}