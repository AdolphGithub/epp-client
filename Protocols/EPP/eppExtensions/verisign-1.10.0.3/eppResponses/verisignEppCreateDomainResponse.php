<?php
namespace Guanjia\EPP;


class verisignEppCreateDomainResponse extends verisignBaseResponse
{
    /**
     * DOMAIN CREATE RESPONSES
     */
    public function getDomainCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:crDate');
    }


    public function getDomainExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:exDate');
    }


    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:name');
    }

    public function getDomain() {
        $return = new eppDomain($this->getDomainName());
        return $return;
    }
}