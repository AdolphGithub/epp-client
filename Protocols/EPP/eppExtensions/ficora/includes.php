<?php
$this->addExtension('ficora','http://www.ficora.fi/epp/ficora');

include_once(dirname(__FILE__) . '/eppData/ficoraEppContactPostalInfo.php');
include_once(dirname(__FILE__) . '/eppData/ficoraEppDomain.php');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppRenewRequest.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppRenewRequest', 'Guanjia\EPP\eppRenewResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppInfoDomainResponse.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppInfoDomainRequest', 'Guanjia\EPP\ficoraEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppInfoContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppInfoContactResponse.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppInfoContactRequest', 'Guanjia\EPP\ficoraEppInfoContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppUpdateContactRequest.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppUpdateContactRequest', 'Guanjia\EPP\eppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCheckBalanceRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckBalanceResponse.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppCheckBalanceRequest', 'Guanjia\EPP\ficoraEppCheckBalanceResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppUpdateDomainRequest.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppUpdateDomainRequest', 'Guanjia\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckContactResponse.php');
$this->addCommandResponse('Guanjia\EPP\eppCheckContactRequest', 'Guanjia\EPP\ficoraEppCheckContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCreateContactRequest.php');
$this->addCommandResponse('Guanjia\EPP\ficoraEppCreateContactRequest', 'Guanjia\EPP\eppCreateContactResponse');
