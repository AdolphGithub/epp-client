<?php
// 自动加载.
$responses = glob(dirname(__FILE__) . '/eppResponses/*');

foreach($responses as $response){
    include_once($response);
    $file = pathinfo($response);
    $class_name = str_replace('verisignE','e',$file['filename']);
    $class_name = str_replace('Response','Request',$class_name);
    if(class_exists('Guanjia\\EPP\\' . $class_name)){
        // 重写所有的对应的关系.
        $this->addCommandResponse('Guanjia\\EPP\\'.$class_name,'Guanjia\\EPP\\'.$file['filename']);
    }
}

$requestes = glob(dirname(__FILE__) . '/eppRequests/*');

foreach($requestes as $request){
    include_once($request);
}

// contact
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckContactRequest','Guanjia\\EPP\\verisignEppCheckContactResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCreateContactRequest','Guanjia\\EPP\\verisignEppCreateContactResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppInfoContactRequest','Guanjia\\EPP\\verisignEppInfoContactResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppDeleteContactRequest','Guanjia\\EPP\\verisignEppDeleteContactResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppUpdateContactRequest','Guanjia\\EPP\\verisignEppUpdateContactResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppTransferContactRequest','Guanjia\\EPP\\verisignEppTransferContactResponse');

// host
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCreateHostRequest','Guanjia\\EPP\\verisignEppCreateHostResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckHostRequest','Guanjia\\EPP\\verisignEppCheckHostResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppDeleteHostRequest','Guanjia\\EPP\\verisignEppDeleteHostResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppInfoHostRequest','Guanjia\\EPP\\verisignEppInfoHostResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppUpdateHostRequest','Guanjia\\EPP\\verisignEppUpdateHostResponse');

// domain
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCreateDomainRequest','Guanjia\\EPP\\verisignEppCreateDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckDomainRequest','Guanjia\\EPP\\verisignEppCheckDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppDeleteDomainRequest','Guanjia\\EPP\\verisignEppDeleteDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppInfoDomainRequest','Guanjia\\EPP\\verisignEppInfoDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppUpdateDomainRequest','Guanjia\\EPP\\verisignEppUpdateDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppRenewDomainRequest','Guanjia\\EPP\\verisignEppRenewDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppTransferDomainRequest','Guanjia\\EPP\\verisignEppTransferDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppSyncDomainRequest','Guanjia\\EPP\\verisignEppSyncDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppRestoreDomainRequest','Guanjia\\EPP\\verisignEppRestoreDomainResponse');