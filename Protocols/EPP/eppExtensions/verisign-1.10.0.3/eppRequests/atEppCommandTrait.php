<?php
namespace Guanjia\EPP;


trait atEppCommandTrait
{

    protected function setAtExtensions($sub_contact)
    {
//        $sub_product = $this->sub_product;
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt', 'http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $namestoreExt->setAttribute('xsi:schemaLocation', 'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd');
        $subProduct = $this->createElement("namestoreExt:subProduct", $sub_contact);
        $namestoreExt->appendChild($subProduct);
        $this->getExtension()->appendChild($namestoreExt);

    }
}