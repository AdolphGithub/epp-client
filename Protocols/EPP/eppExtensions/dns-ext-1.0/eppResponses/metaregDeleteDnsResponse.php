<?php
namespace Guanjia\EPP;



/**
 * Class metaregDeleteDnsResponse
 * @package Guanjia\EPP
 */
class metaregDeleteDnsResponse extends eppResponse {
    const RESPONSE_BASEXPATH = '/epp:epp/epp:response/epp:resData/dns-ext:delData';

    /**
     * @return string
     */
    public function getName() {
        $xpath = $this->xPath();
        return $xpath->query(self::RESPONSE_BASEXPATH . '/dns-ext:name');
    }
}
