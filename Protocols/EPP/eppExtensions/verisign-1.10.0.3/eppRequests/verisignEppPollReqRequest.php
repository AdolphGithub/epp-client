<?php

namespace Guanjia\EPP;


class verisignEppPollReqRequest extends verisignBaseRequest
{

    public function __construct()
    {
        parent::__construct();

        $poll = $this->createElement("poll");
        $poll->setAttribute("op",'req');
        $this->getCommand()->appendChild($poll);
    }
}