<?php
$this->addExtension('rgp','urn:ietf:params:xml:ns:rgp-1.0');

include_once(dirname(__FILE__) . '/eppRequests/eppRgpRestoreRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/eppRgpRestoreResponse.php');
$this->addCommandResponse('Guanjia\\EPP\\eppRgpRestoreRequest', 'Guanjia\\EPP\\eppRgpRestoreResponse');
