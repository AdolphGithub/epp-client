<?php
require('../Loader.php');

\Guanjia\Loader::load();

use Guanjia\EPP\eppConnection;
use Guanjia\EPP\eppException;
use Guanjia\EPP\eppPollRequest;
use Guanjia\EPP\eppResponse;
/*
 * This script polls for new messages in the EPP system
 * The messages tell you if a domain name was transferred away to another provider
 * Or it tells you that your credit balance is low
 * Please use the pollack function to acknowledge a message and remove it from the queue
 */


try {
    echo "Polling for messages\n";
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            $messageid = poll($conn);
            if ($messageid) {
                pollack($conn, $messageid);
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Guanjia\EPP\eppConnection
 * @return null|string
 */
function poll($conn) {
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_REQ, 0);
        if ($response = $conn->request($poll)) {
            /* @var $response Guanjia\EPP\eppPollResponse */
            if ($response->getResultCode() == eppResponse::RESULT_MESSAGE_ACK) {
                echo $response->saveXML();
                echo $response->getMessageCount() . " messages waiting in the queue\n";
                echo "Picked up message " . $response->getMessageId() . ': ' . $response->getMessage() . "\n";
                return $response->getMessageId();
            } else {
                echo $response->getResultMessage() . "\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param $conn eppConnection
 * @param $messageid string
 */
function pollack($conn, $messageid) {
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
        if ($response = $conn->request($poll)) {
            /* @var $response Guanjia\EPP\eppPollResponse */
            echo "Message $messageid is acknowledged\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}