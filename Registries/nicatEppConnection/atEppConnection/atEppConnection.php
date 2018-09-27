<?php

namespace Guanjia\EPP;
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 09.09.2015
 * Time: 10:56
 */
class atEppConnection extends nicatEppConnection {

    /*
    |--------------------------------------------------------------------------
    | atEppConnection
    |--------------------------------------------------------------------------
    |
    | Epp connection for TLD .at
    |
    */

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));
        parent::enableDnssec();
        parent::addExtension('at-ext-epp', atEppConstants::namespaceAtExt);

        parent::addCommandResponse('Guanjia\EPP\atEppCreateContactRequest', 'Guanjia\EPP\atEppCreateResponse');
        parent::addCommandResponse('Guanjia\EPP\atEppUpdateContactRequest', 'Guanjia\EPP\atEppUpdateContactResponse');
        parent::addCommandResponse('Guanjia\EPP\atEppCreateDomainRequest', 'Guanjia\EPP\atEppCreateResponse');
        parent::addCommandResponse('Guanjia\EPP\atEppUpdateDomainRequest', 'Guanjia\EPP\atEppUpdateDomainResponse');
        parent::addCommandResponse('Guanjia\EPP\atEppDeleteRequest', 'Guanjia\EPP\atEppDeleteResponse');
        parent::addCommandResponse('Guanjia\EPP\atEppTransferRequest', 'Guanjia\EPP\atEppTransferResponse');
        parent::addCommandResponse('Guanjia\EPP\eppInfoDomainRequest', 'Guanjia\EPP\atEppInfoDomainResponse');
        parent::addCommandResponse('Guanjia\EPP\eppInfoContactRequest', 'Guanjia\EPP\atEppInfoContactResponse');
    }





}
