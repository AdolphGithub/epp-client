<?php

namespace Guanjia\EPP;

/**
 * Created by PhpStorm.
 * User: adolph
 * Date: 18-9-26
 * Time: 上午10:45
 */
class verisignBaseResponse extends eppResponse
{
    protected $success_codes = [
        '1000','1500'
    ];

    /**
     * 重写他的getResultCode
     * @return string
     */
    public function getResultCode()
    {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/@code');
        if ($result && in_array($result,$this->success_codes)) {
            return '0';
        } else {
            return $result;
        }
    }
}