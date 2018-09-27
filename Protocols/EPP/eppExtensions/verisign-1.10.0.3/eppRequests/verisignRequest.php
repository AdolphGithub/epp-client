<?php
namespace Guanjia\EPP;

class verisignRequest extends eppRequest
{
    public function appendExtension($type = 'dotCOM')
    {
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt','http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $namestoreExt->setAttribute('xsi:schemaLocation','http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd');
        $namestoreExt->appendChild(
            $this->createElement('namestoreExt:subProduct',$type)
        );
        $this->getExtension()->appendChild($namestoreExt);
        $this->getCommand()->appendChild($this->getExtension());
    }
}