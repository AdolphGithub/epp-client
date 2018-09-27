<?php
namespace Guanjia\EPP;

/**
 * Class metaregDeleteDnsRequest
 * @package Guanjia\EPP
 */
class metaregDeleteDnsRequest extends metaregDnsRequest {
    /**
     * EppDeleteDnsRequest constructor.
     *
     * @param eppDomain $domain
     * @throws eppException
     */
    public function __construct(eppDomain $domain) {
        parent::__construct(eppRequest::TYPE_DELETE);
        if (!strlen($domain->getDomainname())) {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        $dname = $this->createElement('dns-ext:name', $domain->getDomainname());
        $this->dnsObject->appendChild($dname);
    }


}
