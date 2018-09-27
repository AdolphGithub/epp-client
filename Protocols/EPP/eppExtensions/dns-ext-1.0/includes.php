<?php
$this->addExtension('dns-ext', 'http://www.metaregistrar.com/epp/dns-ext-1.0');
include_once(dirname(__FILE__) . '/eppRequests/metaregDnsRequest.php');

include_once(dirname(__FILE__) . '/eppRequests/metaregCreateDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregCreateDnsResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregCreateDnsRequest', 'Guanjia\EPP\metaregCreateDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregDeleteDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregDeleteDnsResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregDeleteDnsRequest', 'Guanjia\EPP\metaregDeleteDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregUpdateDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregUpdateDnsResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregUpdateDnsRequest', 'Guanjia\EPP\metaregUpdateDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregInfoDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregInfoDnsResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregInfoDnsRequest', 'Guanjia\EPP\metaregInfoDnsResponse');
