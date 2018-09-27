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

// 新的对应关系
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckDomainRequest','Guanjia\\EPP\\verisignEppCheckDomainResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckHostRequest','Guanjia\\EPP\\verisignEppCheckHostResponse');
$this->addCommandResponse('Guanjia\\EPP\\verisignEppCheckContactRequest','Guanjia\\EPP\\verisignEppCheckContactResponse');