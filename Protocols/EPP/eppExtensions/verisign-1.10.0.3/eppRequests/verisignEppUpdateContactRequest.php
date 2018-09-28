<?php
namespace Guanjia\EPP;

class verisignEppUpdateContactRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;

    public function __construct(eppContact $epp_contact,eppContactPostalInfo $epp_postal_info,$sub_product = 'dotCOM')
    {
        parent::__construct();
        $element = $this->createElement($this->type);
        // 批量设值
        $element->appendChild($this->createContact($epp_contact,$epp_postal_info));
        $this->getCommand()->appendChild($element);
        // 添加扩展
        $this->appendExtension($sub_product);
    }

    /**
     * create contact params
     * @param eppContact $epp_contact
     * @param eppContactPostalInfo $epp_postal_info
     * @return \DOMElement
     */
    private function createContact(eppContact $epp_contact,eppContactPostalInfo $epp_postal_info)
    {
        $contact_update = $this->createElement('contact:'.$this->type);
        $contact_update = $this->setAttributes($contact_update,[
            'xmlns:contact' =>  'urn:ietf:params:xml:ns:contact-1.0',
            'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd'
        ]);
        return $this->appendContacts($contact_update,$epp_contact,$epp_postal_info);
    }

    /**
     * 设置其他参数.s
     * @param \DOMElement $dom
     * @param eppContact $epp_contact
     * @param eppContactPostalInfo $epp_postal_info
     * @return \DOMElement
     */
    private function appendContacts(\DOMElement $dom,eppContact $epp_contact,eppContactPostalInfo $epp_postal_info)
    {
        $contact_addr = $this->createElement('contact:addr');

        foreach($epp_postal_info->getStreets() as $street){
            $contact_addr->appendChild($this->createElement('contact:street',$street));
        }

        $contact_addr = $this->appendChildes($contact_addr,[
            'contact:city'  =>  $epp_postal_info->getCity(),
            'contact:sp'    =>  $epp_postal_info->getProvince(),
            'contact:pc'    =>  $epp_postal_info->getZipcode(),
            'contact:cc'    =>  $epp_postal_info->getCountrycode()
        ]);

        return $this->appendChildes($dom,[
            'contact:id'    =>  $epp_contact->getId(),
            'contact:chg'   =>  [
                'contact:postalInfo type=' . $epp_postal_info->getType()    =>  [
                    'contact:name'  =>  $epp_postal_info->getName(),
                    'contact:org'   =>  $epp_postal_info->getOrganisationName(),
                    'contact:addr'  =>  $contact_addr,
                ],
                'contact:voice'     =>  $epp_contact->getVoice(),
                'contact:fax'       =>  $epp_contact->getFax(),
                'contact:email'     =>  $epp_contact->getEmail(),
                // 暂时没有呢.
                'contact:authInfo'  =>  [
                    'contact:pw'    =>  $epp_contact->getPassword()
                ]
            ]
        ]);
    }
}