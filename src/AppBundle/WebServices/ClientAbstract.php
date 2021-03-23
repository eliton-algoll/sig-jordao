<?php

namespace AppBundle\WebServices;

abstract class ClientAbstract
{
    /**
     * @var string
     */
    protected $wsdl;
    
    /**
     * @var \SoapClient
     */
    protected $client;
    
    /**
     * @param string $wsdl
     * @param array $options
     * @param string $env
     */
    public function __construct($wsdl, $options = array(), $env = 'prod')
    {
        $this->wsdl = $wsdl;
        
        $defaults = array(
            'exceptions' => true, 
            'encoding' => 'UTF-8', 
            'features' => SOAP_USE_XSI_ARRAY_TYPE,
            'cache_wsdl' => WSDL_CACHE_NONE
        );
        
        if ('dev' == $env) {
            
            $opts = array(
                'http' => array(
                    'proxy' => 'tcp://proxy.saude.gov:80',
                    'request_fulluri' => true
                ),
                'ssl' => array(
                    'SNI_enabled' => true,
                    'SNI_server_name' => parse_url($this->wsdl, PHP_URL_HOST)
                )
            );

            $defaults['stream_context'] = stream_context_create($opts);
            $defaults['proxy_host'] = 'proxy.saude.gov';
            $defaults['proxy_port'] = '80';
        }
        
        $this->client = new \SoapClient($this->wsdl, array_merge($defaults, $options));
    }
    
    /**
     * @return array
     */
    public function getFunctions()
    {
        return $this->client->__getFunctions();
    }
}