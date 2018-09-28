<?php
namespace Guanjia\EPP;

class verisignBaseRequest extends eppRequest
{
    public function appendExtension($type = 'dotCOM')
    {
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');

        $this->setAttributes($namestoreExt,[
            'xmlns:namestoreExt'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ])->appendChild(
            $this->createElement('namestoreExt:subProduct',$type)
        );

        $this->getExtension()->appendChild($namestoreExt);
        $this->getCommand()->appendChild($this->getExtension());
    }

    /**
     * 批量赋值.
     * @param \DOMElement $dom
     * @param array $attributes
     * @return \DOMElement
     */
    public function setAttributes(\DOMElement &$dom,array $attributes)
    {
        foreach($attributes as $key=>$value){
            $dom->setAttribute($key,$value);
        }
        return $dom;
    }


}