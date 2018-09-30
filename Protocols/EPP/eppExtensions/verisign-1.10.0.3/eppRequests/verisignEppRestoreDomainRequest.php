<?php
namespace Guanjia\EPP;

class verisignEppRestoreDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;

    const ACTION_REQUEST = 'request';

    const ACTION_REPORT  = 'report';

    /**
     * 添加request.
     * @param $domain_name
     * @param string $sub_product
     */
    public function doRequest($domain_name,$sub_product = 'dotCOM')
    {
        $update = $this->appendChildes($this->createElement($this->type),[
            'domain:'.$this->type =>  $this->appendChildes($this->setAttributes('domain:'.$this->type,[
                'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
                'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
            ]),[
                'domain:name'   =>  $domain_name,
                'domain:chg'    =>  $this->createElement('domain:chg')
            ])
        ]);


        $this->getCommand()->appendChild($update);

        $this->appendExtension($sub_product,false);

        $restore = $this->appendChildes($this->setAttributes('rgp:'.$this->type,[
            'xmlns:rgp' =>  'urn:ietf:params:xml:ns:rgp-1.0',
            'xmlns:xsi' =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:rgp-1.0 rgp-1.0.xsd'
        ]),[
            'rgp:restore'   =>  $this->setAttributes('rgp:restore',[
                'op'    =>  verisignEppRestoreDomainRequest::ACTION_REQUEST
            ])
        ]);

        $this->getExtension()->appendChild($restore);

        $this->getCommand()->appendChild($this->getExtension());
    }

    /**
     * restore report
     * @param $data
     * @param string $sub_product
     */
    public function doReport($data,$sub_product = 'dotCOM')
    {
        $update = $this->appendChildes($this->createElement($this->type),[
            'domain:'.$this->type =>  $this->appendChildes($this->setAttributes('domain:'.$this->type,[
                'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
                'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd'
            ]),[
                'domain:name'   =>  $data['domain_name'],
                'domain:chg'    =>  $this->createElement('domain:chg')
            ])
        ]);

        $this->getCommand()->appendChild($update);

        $this->appendExtension($sub_product,false);

        $restore = $this->appendChildes($this->setAttributes('rgp:'.$this->type,[
            'xmlns:rgp' =>  'urn:ietf:params:xml:ns:rgp-1.0',
            'xmlns:xsi' =>  'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:rgp-1.0 rgp-1.0.xsd'
        ]),[
            'rgp:restore'   =>  $this->appendChildes($this->setAttributes('rgp:restore',[
                'op'    =>  verisignEppRestoreDomainRequest::ACTION_REQUEST
            ]),[
                'rgp:report'    =>  [
                    'rgp:preData'   =>  $data['preData'],
                    'rgp:postData'  =>  $data['postData'],
                    'rgp:delTime'   =>  $data['delTime'],
                    'rgp:resTime'   =>  $data['resTime'],
                    'rgp:resReason' =>  $data['resReason'],
                    $this->createElement('rgp:statement',array_shift($data['statement'])),
                    $this->createElement('rgp:statement',array_shift($data['statement'])),
                    $this->createElement('rgp:other',isset($data['other'])  ? $data['other'] : '')
                ]
            ])
        ]);

        $this->getExtension()->appendChild($restore);
    }

}