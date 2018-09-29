<?php
namespace Guanjia\EPP;

class verisignEppTransferDomainRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_TRANSFER;

    const ACTION_REQUEST    = 'request';
    const ACTION_QUERY      = 'query';
    const ACTION_APPROVE    = 'approve';
    const ACTION_REJECT     = 'reject';
    const ACTION_CANCEL     = 'cancel';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * transfer request.
     * @todo 没有使用codes
     * @param $data
     * @param $codes
     * @param string $sub_product
     * @param array $other_domains
     */
    public function doRequest($data,$codes,$sub_product = 'dotCOM',$other_domains = [])
    {
        $auth_attr = [];
        if(array_key_exists('infoRoleid',$data)) {
            $auth_attr['roid']  = $data['infoRoleid'];
        }

        $domain_request = $this->appendChildes($this->setAttributes($this->type,[
            'op'    =>  verisignEppTransferDomainRequest::ACTION_REQUEST
        ]),[
            'domain:'. $this->type   =>  $this->appendChildes($this->setAttributes('domain:'.$this->type,[
                'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
                'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'     =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd',
            ]),[
                'domain:name'   =>  $data['domain_name'],
                'domain:period unit=y'  =>  $data['period'],
                'domain:authInfo'   =>  [
                    'domain:pw'     => $this->setAttributes($this->createElement('domain:pw',$data['password']),$auth_attr)
                ]
            ])
        ]);

        $this->getCommand()->appendChild($domain_request);

        $this->appendExtension($sub_product,false);

        if(count($codes) > 0) {
            $verification_codes = [];

            while($code = array_shift($codes)) {
                array_push($verification_codes,$this->createElement('verificationCode:code',$code));
            }

            $verification_code = $this->appendChildes($this->setAttributes('verificationCode:encodedSignedCode',[
                'xmlns:verificationCode'    =>  'urn:ietf:params:xml:ns:verificationCode-1.0'
            ]),[
                'verificationCode:code' => $codes
            ]);

            $this->getExtension()->appendChild($verification_code);
        }

        if(count($other_domains) > 0) {
            $rel_dom = $this->setAttributes('relDom:' . $this->type,[
                'xmlns:relDom'  =>  'http://www.verisign.com/epp/relatedDomain-1.0'
            ]);

            foreach($other_domains as $key=> $domain) {
                $rel_dom->appendChild($this->appendChildes($this->createElement('relDom:domain'),[
                    'relDom:name'   =>  $domain['domain_name'],
                    'relDom:authInfo'   =>  [
                        'relDom:pw'     =>  $this->setAttributes($this->createElement('relDom:pw',$domain['password']),[
                            'rolid'     =>  isset($domain['infoRoleid']) ? $domain['infoRoleid'] : ''
                        ])
                    ]
                ]));
            }

            $this->getExtension()->appendChild($rel_dom);
        }

        $this->getCommand()->appendChild($this->getExtension());
    }

    /**
     * transfer query.
     * @param $domain_name
     * @param string $sub_product
     * @param array $other_domains
     */
    public function doQuery($domain_name,$sub_product = 'dotCOM',$other_domains = [])
    {
        $this->doBasicContent($domain_name,$sub_product,$other_domains,verisignEppTransferDomainRequest::ACTION_QUERY);
    }

    /**
     * transfer approve.
     * @param $domain_name
     * @param string $sub_product
     * @param array $other_domains
     */
    public function doApprove($domain_name,$sub_product = 'dotCOM',$other_domains = [])
    {
        $this->doBasicContent($domain_name,$sub_product,$other_domains,verisignEppTransferDomainRequest::ACTION_APPROVE);
    }

    /**
     * transfer approve.
     * @param $domain_name
     * @param string $sub_product
     * @param array $other_domains
     */
    public function doCancel($domain_name,$sub_product = 'dotCOM',$other_domains = [])
    {
        $this->doBasicContent($domain_name,$sub_product,$other_domains,verisignEppTransferDomainRequest::ACTION_CANCEL);
    }

    /**
     * transfer approve.
     * @param $domain_name
     * @param string $sub_product
     * @param array $other_domains
     */
    public function doReject($domain_name,$sub_product = 'dotCOM',$other_domains = [])
    {
        $this->doBasicContent($domain_name,$sub_product,$other_domains,verisignEppTransferDomainRequest::ACTION_REJECT);
    }


    /**
     * 填写信息.
     * @param $domain_name
     * @param $sub_product
     * @param array $other_domains
     * @param string $action
     */
    protected function doBasicContent($domain_name,$sub_product,$other_domains = [],$action = verisignEppTransferDomainRequest::ACTION_QUERY)
    {
        $domain_request = $this->appendChildes($this->setAttributes($this->type,[
            'op'    =>  $action
        ]),[
            'domain:'. $this->type   =>  $this->appendChildes($this->setAttributes('domain:'.$this->type,[
                'xmlns:domain'  =>  'urn:ietf:params:xml:ns:domain-1.0',
                'xmlns:xsi'     =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'     =>  'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd',
            ]),[
                'domain:name'   =>  $domain_name
            ])
        ]);

        $this->getCommand()->appendChild($domain_request);

        $this->appendExtension($sub_product,false);

        if(count($other_domains) > 0)
        {
            $rel_dom = $this->setAttributes('relDom:' . $this->type,[
                'xmlns:relDom'  =>  'http://www.verisign.com/epp/relatedDomain-1.0'
            ]);

            foreach($other_domains as $key=> $domain) {
                $rel_dom->appendChild($this->appendChildes($this->createElement('relDom:domain'),[
                    'relDom:name'   =>  $domain['domain_name']
                ]));
            }

            $this->getExtension()->appendChild($rel_dom);
        }

        $this->getCommand()->appendChild($this->getExtension());
    }
}