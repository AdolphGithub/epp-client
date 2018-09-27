<?php
$this->addExtension("dnssi", "http://www.arnes.si/xml/epp/dnssi-1.2");
#
# For use with the SI connection
#
include_once(dirname(__FILE__) . '/eppData/siEppContactPostalInfo.php');
include_once(dirname(__FILE__) . '/eppRequests/siEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/siEppCreateDomainRequest.php');

$this->addCommandResponse('Guanjia\EPP\siEppCreateDomainRequest', 'Guanjia\EPP\eppCreateDomainResponse');
$this->addCommandResponse('Guanjia\EPP\siEppCreateContactRequest', 'Guanjia\EPP\eppCreateContactResponse');
