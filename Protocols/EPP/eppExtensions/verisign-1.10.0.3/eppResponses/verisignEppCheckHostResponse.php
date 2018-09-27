<?php
namespace Guanjia\EPP;

class verisignEppCheckHostResponse extends verisignBaseResponse
{
    /**
     *
     * @return array of checked domains with status true/false
     */
    public function getCheckedHosts() {
        $result = null;
        if ($this->getResultCode() == 0) {
            $result = array();
            $xpath = $this->xPath();
            $domains = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $checkedhost = array('hostname' => null, 'available' => false);
                foreach ($childs as $child) {
                    if ($child instanceof \DOMElement) {
                        if ($child->localName=='name') {
                            $available = $child->getAttribute('avail');
                            switch ($available) {
                                case '0':
                                case 'false':
                                    $checkedhost['available'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkedhost['available'] = true;
                                    break;
                            }
                            $checkedhost['hostname'] = $child->nodeValue;
                        }
                    }
                }
                $result[] = $checkedhost;
            }
        }
        return ($result);
    }
}