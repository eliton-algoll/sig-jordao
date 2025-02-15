<?php

namespace App\Facade;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploaderFacade
{
    /**
     *
     * @var string
     */
    private $uploadDir;
    
    /**
     * 
     * @param string $uploadDir
     */
    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;        
    }

    /**
     *
     * @return type
     * @throws \InvalidArgumentException
     */
    public function getUploadDir()
    {
        if (!is_dir($this->uploadDir)) {
            if (false === @mkdir($this->uploadDir, 0777)) {
                throw new \InvalidArgumentException(sprintf('The directory %s cannot be created.', $this->uploadDir));
            }
        }
        return $this->uploadDir;
    }
        
    /**
     * 
     * @param UploadedFile $file
     * @param string $newName
     */
    public function upload(UploadedFile $file, $newName)
    {
        $file->move($this->getUploadDir(), $newName);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function convertToBase64($filename)
    {
        $filepath = $this->getUploadDir() . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($filepath) || !is_file($filepath)) {
            throw new \RuntimeException('O arquivo ' . $filename . ' não foi encontrado.');
        }
        return base64_encode(file_get_contents($filepath));
    }
}
