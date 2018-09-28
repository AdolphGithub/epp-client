<?php

namespace Guanjia\EPP;


class verisignEppCreateHostRequest extends eppRequest
{

    use atEppCommandTrait;
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
//        $this->appendExtension($sub_contact);//扩展
        $this->setAtExtensions($sub_contact);
        $this->addContacts($createhostinfo);

        $this->addSessionId();
    }

    public function addContacts( eppHost $createinfo){
        if ($createinfo instanceof eppHost) {
            $this->setHost($createinfo);
        } else {
            throw new eppException('createinfo must be of type eppContact on eppCreateHostRequest');
        }

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


}