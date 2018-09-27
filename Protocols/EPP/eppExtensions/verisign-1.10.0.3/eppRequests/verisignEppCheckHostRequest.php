<?php
namespace Guanjia\EPP;

/**
 * 检查域名.
 * Class verisignEppCheckDomainRequest
 * @package Guanjia\EPP
 */
class verisignEppCheckHostRequest extends eppRequest
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
        $this->domainobject = $this->createElement('host:'.$type);
        $this->domainobject->setAttribute('xmlns:host','urn:ietf:params:xml:ns:host-1.0');
        $this->domainobject->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $this->domainobject->setAttribute('xsi:schemaLocation','urn:ietf:params:xml:ns:host-1.0 host-1.0.xsd');
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
        $this->getCommand()->setAttribute('xmlns','urn:ietf:params:xml:ns:epp-1.0');
        $this->appendExtension($checkrequest);
        $this->addHosts($checkrequest);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }

    public function addHosts(array $hosts)
    {
        foreach($hosts as $host){
            $this->domainobject->appendChild($this->createElement('host:name', $host));
        }
    }

    /**
     * 添加扩展.
     * @param $domains
     */
    public function appendExtension($domains)
    {
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt', 'http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $namestoreExt->setAttribute('xsi:schemaLocation', 'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd');
        $subProduct = $this->createElement("namestoreExt:subProduct", $this->getDomainType($domains));
        $namestoreExt->appendChild($subProduct);
        $this->getExtension()->appendChild($namestoreExt);
    }


    private function getDomainType(array $domains)
    {
        $types = [];
        foreach($domains as $domain){
            array_push($types,substr($domain,strrpos($domain,'.') + 1));
        }

        $types = array_unique($types);
        if(count($types) > 1){
            throw new eppException('多余的后缀名');
        }
        return 'dot'.strtoupper($types[0]);
    }
}