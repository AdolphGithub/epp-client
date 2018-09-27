<?php
$this->addExtension('polldata', 'http://www.metaregistrar.com/epp/polldata-1.0');

include_once(dirname(__FILE__) . '/eppResponses/metaregEppPollResponse.php');
$this->addCommandResponse('Guanjia\EPP\eppPollRequest', 'Guanjia\EPP\metaregEppPollResponse');