<?php
namespace Guanjia\EPP;
class sidnEppPollRequest extends eppPollRequest {
    function __construct($polltype, $messageid = null) {
        parent::__construct($polltype, $messageid);

    }
}