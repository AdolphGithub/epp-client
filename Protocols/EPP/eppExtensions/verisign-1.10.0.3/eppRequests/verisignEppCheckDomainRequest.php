<?php
namespace Guanjia\EPP;

/**
 * 检查域名.
 * Class verisignEppCheckDomainRequest
 * @package Guanjia\EPP
 */
class verisignEppCheckDomainRequest extends verisignRequest
{
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
        $this->domainobject->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->domainobject->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd');
        $this->domainobject->setAttribute('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0');
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($this->getHostType($checkrequest));
        $this->addDomains($checkrequest);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }

    public function addDomains(array $domains)
    {
        foreach($domains as $domain){
            $this->domainobject->appendChild($this->createElement('domain:name', $domain));
        }
    }


    private function getHostType(array $hosts)
    {
        $types = [];
        foreach($hosts as $host){
            array_push($types,substr($host,strpos($host,'.') + 1));
        }

        $types = array_unique($types);
        if(count($types) > 1){
            throw new eppException('多余的后缀名');
        }
        return 'dot'.strtoupper($types[0]);
    }
}