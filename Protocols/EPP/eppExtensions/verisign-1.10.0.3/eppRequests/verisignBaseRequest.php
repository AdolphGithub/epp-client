<?php
namespace Guanjia\EPP;

class verisignBaseRequest extends eppRequest
{
    public function appendExtension($type = 'dotCOM',$is_insert = true)
    {
        $namestoreExt = $this->setAttributes('namestoreExt:namestoreExt',[
            'xmlns:namestoreExt'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1',
            'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd'
        ]);

        $namestoreExt->appendChild(
            $this->createElement('namestoreExt:subProduct',$type)
        );

        $this->getExtension()->appendChild($namestoreExt);
        // 要加入到command中去.
        if($is_insert) {
            $this->getCommand()->appendChild($this->getExtension());
        }
    }

    /**
     * 批量赋值.
     * @param \DOMElement|string $dom
     * @param array $attributes
     * @return \DOMElement
     */
    public function setAttributes($dom,array $attributes)
    {
        // 自动创建一个对象.
        if(!($dom instanceof \DOMElement)){
            $dom = $this->createElement($dom);
        }

        foreach($attributes as $key=>$value){
            if($value){
                $dom->setAttribute($key,$value);
            }
        }
        return $dom;
    }


    /**
     * 递归创建一堆对象.
     * @param \DOMElement $dom
     * @param array $childes
     * @return \DOMElement
     */
    public function appendChildes(\DOMElement $dom,array $childes)
    {
        foreach($childes as $key=>$value) {
            // 过滤空的.
            if(!$value){
                continue;
            }

            if($value instanceof \DOMElement){
                $dom->appendChild($value);
                continue;
            }

            if(strpos($key,' ')){
                $attributes = explode(' ',$key);
                $key = array_shift($attributes);
            }

            if(is_array($value)){
                $temp_dom = $this->appendChildes($this->createElement($key),$value);
            }else{
                $temp_dom = $this->createElement($key,$value);
            }

            if(isset($attributes)){
                foreach($attributes as $attr){
                    list($attr_key,$attr_value) = explode('=',$attr);
                    $temp_dom->setAttribute($attr_key,$attr_value);
                }
                unset($attributes);
            }

            $dom->appendChild($temp_dom);
        }

        return $dom;
    }

}