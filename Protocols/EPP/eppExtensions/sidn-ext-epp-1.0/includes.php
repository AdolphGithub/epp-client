<?php
$this->addExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');

include_once(dirname(__FILE__) . '/eppResponses/sidnEppResponse.php');

// Create contact with additional parameters
include_once(dirname(__FILE__) . '/eppRequests/sidnEppCreateContactRequest.php');
$this->addCommandResponse('Guanjia\EPP\sidnEppCreateContactRequest', 'Guanjia\EPP\eppCreateContactResponse');

// Renew domain name with renew extension (this is not an extension????)
include_once(dirname(__FILE__) . '/eppRequests/sidnEppRenewRequest.php');
$this->addCommandResponse('Guanjia\EPP\sidnEppRenewRequest', 'Guanjia\EPP\eppRenewResponse');


include_once(dirname(__FILE__) . '/eppRequests/sidnEppPollRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/sidnEppPollResponse.php');
$this->addCommandResponse('Guanjia\EPP\sidnEppPollRequest', 'Guanjia\EPP\sidnEppPollResponse');

include_once(dirname(__FILE__) . '/eppResponses/sidnEppCheckResponse.php');
$this->addCommandResponse('Guanjia\EPP\eppCheckRequest', 'Guanjia\EPP\sidnEppCheckResponse');

include_once(dirname(__FILE__) . '/eppRequests/sidnEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/sidnEppInfoDomainResponse.php');
$this->addCommandResponse('Guanjia\EPP\sidnEppInfoDomainRequest', 'Guanjia\EPP\sidnEppInfoDomainResponse');
