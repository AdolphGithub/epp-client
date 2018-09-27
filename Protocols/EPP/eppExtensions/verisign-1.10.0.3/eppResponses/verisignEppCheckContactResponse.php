<?php
namespace Guanjia\EPP;

class verisignEppCheckContactResponse extends verisignBaseResponse
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
            $domains = $xpath->query('/epp:epp/epp:response/epp:resData/contact:chkData/contact:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $contact = ['contact_id' => null, 'available' => false];
                foreach ($childs as $child) {
                    if ($child instanceof \DOMElement) {
                        if ($child->localName=='id') {
                            $available = $child->getAttribute('avail');
                            switch ($available) {
                                case '0':
                                case 'false':
                                    $contact['available'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $contact['available'] = true;
                                    break;
                            }
                            $contact['id'] = $child->nodeValue;
                        }
                    }
                }
                $result[] = $contact;
            }
        }
        return ($result);
    }
}