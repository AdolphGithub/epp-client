<?php

$this->addExtension('command-ext', 'http://www.metaregistrar.com/epp/command-ext-1.0');
$this->addExtension('command-ext-domain', 'http://www.metaregistrar.com/epp/command-ext-domain-1.0');
$this->addExtension('command-ext-contact', 'http://www.metaregistrar.com/epp/command-ext-contact-1.0');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppPrivacyRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregEppPrivacyRequest', 'Guanjia\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppAutorenewRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregEppAutorenewRequest', 'Guanjia\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppData/metaregInfoDomainOptions.php');
include_once(dirname(__FILE__) . '/eppRequests/metaregInfoDomainRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregInfoDomainRequest', 'Guanjia\EPP\eppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppAuthcodeRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregEppAuthcodeRequest', 'Guanjia\EPP\eppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppTransferExtendedRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregEppTransferExtendedRequest', 'Guanjia\EPP\eppTransferResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppUpdateContactRequest.php');
$this->addCommandResponse('Guanjia\EPP\metaregEppUpdateContactRequest', 'Guanjia\EPP\eppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/metaregEppInfoContactResponse.php');
$this->addCommandResponse('Guanjia\EPP\eppInfoContactRequest', 'Guanjia\EPP\metaregEppInfoContactResponse');