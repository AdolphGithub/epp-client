<?php
namespace Guanjia\EPP;


trait atEppCommandTrait
{

    protected function setAtExtensions()
    {

        $this->atEppExtensionChain->setEppRequestExtension($this, $this->getExtension());
        $this->addExtension('xmlns:xsi', atEppConstants::w3SchemaLocation);
    }
}