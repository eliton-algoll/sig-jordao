<?php

namespace App\Traits;

/**
 * Serviços de Download
 *
 * @author Emmanuel
 */
trait DownloadResponseTrait
{
    public function responseXls($filename, $content)
    {
        $filename .= date('_d_m_Y');
        
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=$filename.xls");
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->setContent(utf8_decode($content));
        return $response; 
    }
    
}
