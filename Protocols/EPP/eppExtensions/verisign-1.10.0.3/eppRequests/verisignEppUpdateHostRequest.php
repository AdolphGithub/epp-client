<?php
namespace Guanjia\EPP;

class verisignEppUpdateHostRequest extends verisignBaseRequest
{
    private $type = eppRequest::TYPE_UPDATE;

    public function __construct($hostname,$data,$sub_product = 'dotCOM')
    {
        parent::__construct();

        $host_data = $this->buildHostData($hostname,$data);

        $host = $this->appendChildes($this->createElement($this->type),[
            'host:'.$this->type => $this->appendChildes($this->setAttributes('host:'.$this->type,[
                'xmlns:host'            =>  'urn:ietf:params:xml:ns:host-1.0',
                'xmlns:xsi'             =>  'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation'    =>  'urn:ietf:params:xml:ns:host-1.0 host-1.0.xsd'
            ]),$host_data)
        ]);

        $this->getCommand()->appendChild($host);
        $this->appendExtension($sub_product);
    }

    /**
     * 生成数据.
     * @param $hostname
     * @param $data
     * @return array
     */
    private function buildHostData($hostname,$data)
    {
        $host_data = [
            'host:name' =>  $hostname,
        ];

        if(array_key_exists('add',$data)) {
            $add_hosts = $this->createElement('host:add');
            foreach($data['add'] as $key => $value) {
                $add_hosts->appendChild($this->setAttributes($this->createElement('host:addr',$value['ip']),[
                    'ip'    =>  $value['type']
                ]));
            }

            $host_data['host:add'] = $add_hosts;
        }

        if(array_key_exists('remove',$data)) {
            $remove_hosts = $this->createElement('host:rem');
            foreach($data['remove'] as $key => $value) {
                $remove_hosts->appendChild($this->setAttributes($this->createElement('host:addr',$value['ip']),[
                    'ip'    =>  $value['type']
                ]));
            }

            $host_data['host:rem'] = $remove_hosts;
        }

        if(array_key_exists('new_host',$data)){
            $host_data['host:chg'] = [
                'host:name' =>  $data['new_host']
            ];
        }

        return $host_data;
    }
}