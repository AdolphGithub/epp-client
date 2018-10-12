<?php

namespace Guanjia\EPP;


class verisignEppPollReqResponse extends verisignBaseResponse
{
    const TYPE_TRANSFER = 'trn';
    const TYPE_CREATE = 'cre';
    const TYPE_INFO = 'inf';
    const TYPE_PAN = 'pan';
    const TYPE_CHECK = 'chk';
    const TYPE_RENEW = 'ren';
    const TYPE_UNKNOWN = 'unknown';

    private $messageType = null;

    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }
    public function getDelDataName() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/polldata:polldata/polldata:resData/polldata:delData/polldata:name');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Return the identifier of the message
     * Use this identifier to acknowledge the poll message
     * @return null|string
     */
    public function getMessageId() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/@id');
    }

    /**
     * Return the date of the message
     * @return null|string
     */
    public function getMessageDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/epp:qDate');
    }

    /**
     * Return the poll message
     * @return null|string
     */
    public function getMessage() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/epp:msg');
    }

    /**
     * return the number of messages that remain
     * @return int|string
     */
    public function getMessageCount() {
        if ($this->getResultCode() == eppResponse::RESULT_NO_MESSAGES) {
            return 0;
        } else {
            return $this->queryPath('/epp:epp/epp:response/epp:msgQ/@count');
        }
    }

    /**
     * Determine the type of poll message
     * TYPE_TRANSFER
     * TYPE_CREATE
     * TYPE_UPDATE
     * TYPE_DELETE
     */
    public function getMessageType() {
        if ($this->messageType) {
            return $this->messageType;
        } else {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_TRANSFER;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CREATE;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:chkData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CHECK;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_INFO;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:panData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_PAN;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:renData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_RENEW;
            }
            return self::TYPE_UNKNOWN;
        }
    }

    /**
     * Retrieve the domain name in this poll message
     * @return null|string
     */
    public function getDomainName() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:name');
    }

    /**
     * If present, retrieve the current status of the domain name in question
     * @return string|null
     */
    public function getDomainStatus() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:status');
        if ($result->length>0) {
            $object = $result[0];
            /* @var $object \domElement */
            return $object->getAttribute('s');
        }
        return null;
    }

    /**
     * Retrieve the plain-text status message of the domain status
     * @return null|string
     */
    public function getDomainStatusText() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:status');
    }


    /**
     * Get the field trStatus, only present in TRANSFER messages
     * @return null|string
     */
    public function getDomainTrStatus() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:trStatus');
    }

    /**
     * Get client transaction id
     * @return null|string
     */
    public function getDomainRequestClientId() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reID');
    }

    /**
     * Get date of the request
     * @return null|string
     */
    public function getDomainRequestDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reDate');
    }


    /**
     * Get expiration date of the domain name
     * @return null|string
     */
    public function getDomainExpirationDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:exDate');
    }

    /**
     * Get date and time this action happened
     * @return null|string
     */
    public function getDomainActionDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acDate');
    }

    /**
     * Retrieve the client that performed the action
     * @return null|string
     */
    public function getDomainActionClientId() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acID');
    }
}