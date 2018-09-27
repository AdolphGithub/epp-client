<?php

namespace Guanjia\EPP;


class verisignEppCreateContactResponse extends verisignBaseResponse
{
    /**
     * CONTACT CREATE RESPONSES
     */

    /**
     *
     * @return string contact_id
     */
    public function getContactId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
    }

    /**
     *
     * @return string create_date
     */
    public function getContactCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:crDate');
    }

    /**
     *
     * @return eppContactHandle contacthandle
     */
    public function getContactHandle() {
        if ($handle = $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:id')) {
            return new eppContactHandle($handle);
        } else {
            return null;
        }
    }

}