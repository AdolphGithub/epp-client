<?php

namespace Guanjia\EPP;


class verisignEppUpdateDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;
    public function __construct(eppContact $epp_contact,eppContactPostalInfo $epp_postal_info,$sub_product = 'dotCOM')
    {
        parent::__construct();
        $element = $this->createElement($this->type);
        // 批量设值
        $element->appendChild($this->createContact($epp_contact,$epp_postal_info));
        $this->getCommand()->appendChild($element);
        // 添加扩展
        $this->appendExtension($sub_product);
    }
}