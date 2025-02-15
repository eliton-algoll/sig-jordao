<?php

namespace App\Facade;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileNameGeneratorFacade
{
    private $fileName = null;
    
    /**
     * 
     * @param UploadedFile $file
     * @param mixed $id
     * @return string
     */
    public function generate(UploadedFile $file)
    {
        $this->fileName = '@DATE@_@HASH@.@EXT@';

        $this->generateDatePart()
            ->generateHashPart()
            ->generateExtensionPart($file);
        
        return $this->fileName;        
    }
    
    /**
     * 
     * @return \App\Facade\FileNameGeneratorFacade
     */
    private function generateDatePart()
    {
        $now = new \DateTime();
        $this->fileName = str_replace('@DATE@', $now->getTimestamp(), $this->fileName);
        return $this;
    }
    
    /**
     * 
     * @return \App\Facade\FileNameGeneratorFacade
     */
    private function generateHashPart()
    {
        $this->fileName = str_replace('@HASH@', hexdec(hash('adler32', microtime())), $this->fileName);
        return $this;
    }
    
    /**
     * 
     * @param UploadedFile $file
     * @return \App\Facade\FileNameGeneratorFacade
     */
    private function generateExtensionPart(UploadedFile $file)
    {
        $this->fileName = str_replace('@EXT@', $file->getClientOriginalExtension(), $this->fileName);
        return $this;
    }
}
