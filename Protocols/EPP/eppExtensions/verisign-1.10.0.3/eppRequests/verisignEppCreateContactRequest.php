<?php
namespace Guanjia\EPP;


class verisignEppCreateContactRequest extends verisignBaseRequest
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
        $this->contact_object = $this->createElement('contact:'.$type);
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
        $this->setContactId($contact->getId());
        $this->setPostalInfo($contact->getPostalInfo(0));
        $this->setVoice($contact->getVoice());
        $this->setFax($contact->getFax());
        $this->setEmail($contact->getEmail());
        $this->setPassword($contact->getPassword());
        $this->setDisclose($contact->getDisclose());
    }

    /**
     *
     * @param eppContact $contact
     * @throws eppException
     */
    public function setContact(eppContact $contact) {
        #
        # Object create structure
        #
        $this->setContactId($contact->getId());
        $this->setPostalInfo($contact->getPostalInfo(0));
        $this->setVoice($contact->getVoice());
        $this->setFax($contact->getFax());
        $this->setEmail($contact->getEmail());
        $this->setPassword($contact->getPassword());
//        $this->setDisclose($contact->getDisclose());
    }

    /**
     * Create the contact:id field
     * @param $contactid
     */
    public function setContactId($contactid) {
        $this->contact_object->appendChild($this->createElement('contact:id', $contactid));
    }

    /**
     * Set the postalinfo information in the contact
     * @param eppContactPostalInfo $postal
     * @throws eppException
     */
    public function setPostalInfo(eppContactPostalInfo $postal) {
        $postalinfo = $this->createElement('contact:postalInfo');
        if (!$postal instanceof eppContactPostalInfo) {
            throw new eppException('PostalInfo必须在eppCreateContact请求中填写');
        }
        if ($postal->getType()==eppContact::TYPE_AUTO) {
            //如果所有字段都是ascii码, type = int (international) else type = loc (localization)
            if ((self::isAscii($postal->getName())) && (self::isAscii($postal->getOrganisationName())) && (self::isAscii($postal->getStreet(0)))) {
                $postal->setType(eppContact::TYPE_INT);
            } else {
                $postal->setType(eppContact::TYPE_LOC);
            }
        }
        $postalinfo->setAttribute('type', $postal->getType());
        $postalinfo->appendChild($this->createElement('contact:name', $postal->getName()));
        if ($postal->getOrganisationName()) {
            $postalinfo->appendChild($this->createElement('contact:org', $postal->getOrganisationName()));
        }
        $postaladdr = $this->createElement('contact:addr');
        $count = $postal->getStreetCount();
        for ($i = 0; $i < $count; $i++) {
            $postaladdr->appendChild($this->createElement('contact:street', $postal->getStreet($i)));
        }
        $postaladdr->appendChild($this->createElement('contact:city', $postal->getCity()));
        if ($postal->getProvince()) {
            $postaladdr->appendChild($this->createElement('contact:sp', $postal->getProvince()));
        }
        $postaladdr->appendChild($this->createElement('contact:pc', $postal->getZipcode()));
        $postaladdr->appendChild($this->createElement('contact:cc', $postal->getCountrycode()));
        $postalinfo->appendChild($postaladdr);
        $this->contact_object->appendChild($postalinfo);
    }

    /**
     * @param $voice
     */
    public function setVoice($voice) {
        if ($voice) {
            $this->contact_object->appendChild($this->createElement('contact:voice', $voice));
        }
    }

    public function setFax($fax) {
        if ($fax) {
            $this->contact_object->appendChild($this->createElement('contact:fax', $fax));
        }
    }

    public function setEmail($email) {
        if ($email) {
            $this->contact_object->appendChild($this->createElement('contact:email', $email));
        }
    }

    public function setPassword($password) {
        if (is_null($password))
        {
            $uniqid = md5(uniqid(microtime(true),true));
            $password = "AuthInfo-".substr($uniqid,12);
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $password));
            $this->contact_object->appendChild($authinfo);
        }else{
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $password));
            $this->contact_object->appendChild($authinfo);
        }
    }

    public function setDisclose($contactdisclose) {
        if (!is_null($contactdisclose)) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contactdisclose);
            $name = $this->createElement('contact:name');
            if ($contactdisclose==1) {
                $name->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($name);
            $org = $this->createElement('contact:org');
            if ($contactdisclose==1) {
                $org->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($org);
            $addr = $this->createElement('contact:addr');
            if ($contactdisclose==1) {
                $addr->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($addr);
            $disclose->appendChild($this->createElement('contact:voice'));
            $disclose->appendChild($this->createElement('contact:email'));
            $this->contact_object->appendChild($disclose);
        }
    }
}