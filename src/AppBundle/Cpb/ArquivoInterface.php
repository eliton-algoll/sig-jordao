<?php

namespace AppBundle\Cpb;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ArquivoInterface
{
    public function getHeader();
    
    public function getDetalhes();
    
    public function getTrailer();
    
    /**
     * 
     * @param UploadedFile $file
     */
    public function load(UploadedFile $file);
    
    /**
     * @return boolean
     */
    public function isLoaded();
}
