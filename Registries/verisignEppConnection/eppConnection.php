<?php
namespace Guajia\EPP;

class verisignEppConnection extends eppConnection
{
    public function __construct($logging = false, $settingsfile = null)
    {
        parent::__construct($logging, $settingsfile);

        parent::useExtension('verisign-1.10.0.3');
        $this->enableDnssec();
        $this->enableRgp();
    }
}