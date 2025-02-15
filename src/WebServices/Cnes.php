<?php

namespace App\WebServices;

class Cnes extends ClientAbstract
{
    /**
     * @var string
     */
    protected $username = 'CNES.PUBLICO';

    /**
     * @var string
     */
    protected $password = 'cnes#2015public';
    
    /**
     * @param string $env
     */
    public function __construct($env = 'prod')
    {
        $wsdl = 'http://servicos.saude.gov/cnes/CnesService/v1r0?wsdl';
        
        $options = array('soap_version' => SOAP_1_2);
        
        parent::__construct($wsdl, $options, $env);
        
        $this->setSecurityHeader();
    }
    
    protected function setSecurityHeader()
    {
        $headerBody = <<<XML
<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
    <wsse:UsernameToken wsu:Id="Id-0001334008436683-000000002c4a1908-1" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
        <wsse:Username>$this->username</wsse:Username>
        <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">$this->password</wsse:Password>
    </wsse:UsernameToken>
</wsse:Security>   
XML;

        $header = new \SoapHeader(
            'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 
            'Security', 
            new \SoapVar($headerBody, XSD_ANYXML),
            true
        );

        $this->client->__setSoapHeaders(array($header));        
    }
    
    /**
     * @param string $codigoCnes
     * @return \stdClass
     */
    public function consultarEstabelecimentoSaude($codigoCnes)
    {
        $params = new \stdClass();

        $params->CodigoCNES = new \stdClass();
        $params->CodigoCNES->codigo = $codigoCnes;
        
        return $this->client->consultarEstabelecimentoSaude($params);
    }
}