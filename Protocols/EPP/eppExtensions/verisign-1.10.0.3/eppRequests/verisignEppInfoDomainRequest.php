<?php
namespace Guanjia\Epp;

class verisignEppInfoDomainRequest extends verisignBaseRequest
{
    /**
     * ContactObject object to add namespaces to
     * @var \DomElement
     */
    public $contact_object = null;

    const HOSTS_ALL = 'all';
    const HOSTS_DELEGATED = 'del';
    const HOSTS_SUBORDINATE = 'sub';
    const HOSTS_NONE = 'none';

    public function __construct($infodomain, $hosts = null, $namespacesinroot = true,$type = eppRequest::TYPE_INFO)
    {
        parent::__construct();
        $info = $this->createElement($type);
        $info->appendChild($this->createContact($infodomain));
        $this->getCommand()->appendChild($info);
        $this->appendExtension($type);
    }

    /**
     * 创建contact
     * @param array $data
     * @return \DomElement
     */
    private function createContact(array $data)
    {
        $this->contact_object = $this->createElement('domain:info');
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');

        $this->contact_object->appendChild(
            $this->createElement('domain:name',$data['contact_id']));

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