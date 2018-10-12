<?php
namespace Guanjia\EPP;


class verisignEppPollAckRequest extends verisignBaseRequest
{


    public function __construct($MessageID)
    {
        parent::__construct();

        $poll = $this->createElement("poll");
        $poll->setAttribute("msgID",$MessageID);
        $poll->setAttribute("op",'ack');
        $this->getCommand()->appendChild($poll);
    }
}