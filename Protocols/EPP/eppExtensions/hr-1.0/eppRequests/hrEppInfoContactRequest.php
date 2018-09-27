<?php
namespace Guanjia\EPP;

class hrEppInfoContactRequest extends eppInfoContactRequest {
    function __construct($infocontact) {
        parent::__construct($infocontact);
        $this->addSessionId();
    }
}
