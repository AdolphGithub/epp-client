<?php
namespace Guanjia\EPP;

class verisignEppInfoContactRequest extends verisignRequest
{
    /**
     * ContactObject object to add namespaces to
     * @var \DomElement
     */
    public $contact_object = null;

    public function __construct(array $data,$type = eppRequest::TYPE_INFO)
    {
        parent::__construct();
        $info = $this->createElement($type);
        $info->appendChild($this->createContact($data));
        $this->getCommand()->appendChild($info);
        $this->appendExtension($data['type']);
    }

    /**
     * 创建contact
     * @param array $data
     * @return \DomElement
     */
    private function createContact(array $data)
    {
        $this->contact_object = $this->createElement('contact:info');
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');
        $this->contact_object->appendChild($this->createElement('contact:id',$data['contact_id']));

        if(array_key_exists('auth_info',$data) && $data['auth_info'])
        {
            $this->contact_object->appendChild(
                $this->createElement('contact:authInfo')->appendChild(
                    $this->createElement('contact:pw',$data['auth_info'])
                )
            );
        }
        return $this->contact_object;
    }
}