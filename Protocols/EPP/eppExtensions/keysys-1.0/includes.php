<?php
$this->addExtension('keysys','http://www.key-systems.net/epp/keysys-1.0');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppUpdateDomainRequest.php');
$this->addCommandResponse('Guanjia\EPP\rrpproxyEppUpdateDomainRequest', 'Guanjia\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppWhoisPrivacyRequest.php');
$this->addCommandResponse('Guanjia\EPP\rrpproxyEppWhoisPrivacyRequest', 'Guanjia\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppTrusteeRequest.php');
$this->addCommandResponse('Guanjia\EPP\rrpproxyEppTrusteeRequest', 'Guanjia\EPP\eppUpdateDomainResponse');
