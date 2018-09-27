<?php
$this->addExtension('ssl','http://www.metaregistrar.com/epp/ssl-1.0');

include_once(dirname(__FILE__) . '/eppData/metaregSslValidation.php');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslCreateRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslCreateResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregSslCreateRequest', 'Guanjia\EPP\metaregSslCreateResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslRenewRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslRenewResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregSslRenewRequest', 'Guanjia\EPP\metaregSslRenewResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslInfoResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregSslInfoRequest', 'Guanjia\EPP\metaregSslInfoResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslDeleteRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslDeleteResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregSslDeleteRequest', 'Guanjia\EPP\metaregSslDeleteResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslReissueRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslReissueResponse.php');
$this->addCommandResponse('Guanjia\EPP\metaregSslReissueRequest', 'Guanjia\EPP\metaregSslReissueResponse');


