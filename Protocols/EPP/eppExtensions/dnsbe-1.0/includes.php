<?php
$this->addExtension('dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
#
# Load the DNSBE specific additions
#

include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateDomainResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppCreateDomainRequest', 'Guanjia\EPP\dnsbeEppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateContactResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppCreateContactRequest', 'Guanjia\EPP\dnsbeEppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppAuthcodeRequest.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppAuthcodeRequest', 'Guanjia\EPP\eppResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppInfoDomainResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppInfoDomainRequest', 'Guanjia\EPP\dnsbeEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppTransferResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppTransferRequest', 'Guanjia\EPP\dnsbeEppTransferResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppUpdateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppUpdateContactResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppUpdateContactRequest', 'Guanjia\EPP\dnsbeEppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppDeleteDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppDeleteDomainResponse.php');
$this->addCommandResponse('Guanjia\EPP\dnsbeEppDeleteDomainRequest', 'Guanjia\EPP\dnsbeEppDeleteDomainResponse');



