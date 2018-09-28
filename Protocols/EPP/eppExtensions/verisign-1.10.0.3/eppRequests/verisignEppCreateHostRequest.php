<?php

namespace Guanjia\EPP;


class verisignEppCreateHostRequest extends eppRequest
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

    function __construct($createhostinfo,$sub_contact = 'dotCOM',$type = eppRequest::TYPE_CREATE) {
        parent::__construct();
        $create = $this->createElement($type);
        $this->contact_object = $this->createElement('host:'.$type);
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');

        $create->appendChild($this->contact_object);

        $this->getCommand()->appendChild($create);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($sub_contact);//扩展

        $this->addContacts($createhostinfo);

        $this->addSessionId();
    }

    public function addContacts( eppHost $createinfo){
        if ($createinfo instanceof eppHost) {
            $this->setHost($createinfo);
        } else {
            throw new eppException('createinfo must be of type eppContact on eppCreateHostRequest');
        }
        $this->addSessionId();

    }

    /**
     *
     * @param eppHost $host
     * @return \DOMElement
     * @throws eppException
     */
    public function setHost(eppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('No valid hostname in create host request');
        }
        #
        # Object create structure
        #
        $this->contact_object->appendChild($this->createElement('host:name', $host->getHostname()));
        $addresses = $host->getIpAddresses();
        if (is_array($addresses)) {
            foreach ($addresses as $address => $type) {
                $ipaddress = $this->createElement('host:addr', $address);
                $ipaddress->setAttribute('ip', $type);
                $this->contact_object->appendChild($ipaddress);
            }
        }
        return;
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