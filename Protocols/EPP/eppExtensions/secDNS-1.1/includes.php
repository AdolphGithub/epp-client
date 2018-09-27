<?php
$this->addExtension('secDNS','urn:ietf:params:xml:ns:secDNS-1.1');

include_once(dirname(__FILE__) . '/eppData/eppSecdns.php');

include_once(dirname(__FILE__) . '/eppRequests/eppDnssecUpdateDomainRequest.php');
$this->addCommandResponse('Guanjia\\EPP\\eppDnssecUpdateDomainRequest', 'Guanjia\\EPP\\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppResponses/eppDnssecInfoDomainResponse.php');
$this->addCommandResponse('Guanjia\\EPP\\eppInfoDomainRequest', 'Guanjia\\EPP\\eppDnssecInfoDomainResponse');

