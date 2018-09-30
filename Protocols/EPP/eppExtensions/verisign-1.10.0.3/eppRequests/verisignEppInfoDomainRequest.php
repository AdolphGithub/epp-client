<?php
namespace Guanjia\EPP;

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

    public function __construct($infodomain, $hosts = null, $extension = 'dotCOM',$type = eppRequest::TYPE_INFO)
    {
        parent::__construct();
        $info = $this->createElement($type);
        if ($infodomain instanceof eppDomain) {
            $info->appendChild($this->createContact($infodomain, $hosts));
        }else {
            throw new eppException('infodomainrequest的参数必须是eppDomain对象');
        }
        $this->getCommand()->setAttribute("xmlns","urn:ietf:params:xml:ns:epp-1.0");
        $this->getCommand()->appendChild($info);
        $this->appendExtension($extension);
    }

    /**
     * @param eppDomain $domain
     * @param null $hosts
     * @return \DomElement
     * @throws eppException
     */
    private function createContact(eppDomain $domain, $hosts = null)
    {
//        $delete = $this->appendChildes($this->createElement($this->type),[
//            'domain:info' => $this->appendChildes($this->setAttributes($this->createElement('domain:info'),[
//                'xmlns:contact'            =>  'urn:ietf:params:xml:ns:contact-1.0',
//                'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
//                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd',
//            ]),[
//                'domain:name' =>  $domain->getDomainname()
//            ])
//        ]);
//
//        $this->getCommand()->appendChild($delete);

        $this->contact_object = $this->createElement('domain:info');
        $this->contact_object->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->contact_object->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->contact_object->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');
//

        $dname = $this->createElement('domain:name', $domain->getDomainname());
        if ($hosts) {
            if (($hosts == self::HOSTS_ALL) || ($hosts == self::HOSTS_DELEGATED) || ($hosts == self::HOSTS_NONE) || ($hosts == self::HOSTS_SUBORDINATE)) {
                $dname->setAttribute('hosts', $hosts);
            } else {
                throw new eppException('宿主的内因的参数只能是所有的，没有，del或sub');
            }
            $this->contact_object->appendChild($dname);
        }

        if (!is_null($domain->getAuthorisationCode()))
        {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->contact_object->appendChild($authinfo);
        }
        return $this->contact_object;
    }
}