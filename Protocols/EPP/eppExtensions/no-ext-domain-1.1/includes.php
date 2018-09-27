<?php
$this->addExtension('no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');

// Domain
include_once(dirname(__FILE__) . '/eppData/noridEppDomain.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppDomainRequestTrait.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppResponseTrait.php');

// Domain Create/Withdraw
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateDomainResponse.php');
$this->addCommandResponse('Guanjia\\EPP\\noridEppCreateDomainRequest', 'Guanjia\\EPP\\noridEppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppWithdrawDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppWithdrawDomainResponse.php');
$this->addCommandResponse('Guanjia\\EPP\\noridEppWithdrawDomainRequest', 'Guanjia\\EPP\\noridEppWithdrawDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppTransferResponse.php');
$this->addCommandResponse('Guanjia\\EPP\\noridEppTransferRequest', 'Guanjia\\EPP\\noridEppTransferResponse');




