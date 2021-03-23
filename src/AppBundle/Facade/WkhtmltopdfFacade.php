<?php

namespace AppBundle\Facade;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Monolog\Logger;

class WkhtmltopdfFacade
{
    private $enabledOptions = [
        'orientation',
        'encoding',
        'grayscale',
        'margin-bottom',
        'margin-left',
        'margin-right',
        'margin-top',
        /* Headers And Footer Options: */
        'footer-center',
        'footer-font-name',
        'footer-font-size',
        'footer-html',
        'footer-left',
        'footer-line',
        'no-footer-line',
        'footer-right',
        'footer-spacing',
        'header-center',
        'header-font-name',
        'header-font-size',
        'header-html',
        'header-left',
        'header-line',
        'no-header-line',
        'header-right',
        'header-spacing',
    ];
    
    private $options = [
        'encoding' => 'utf-8',
        'lowquality' => null,
    ];
    
    private $fixEncode = 'pt_BR.utf-8';

    private $binary;

    private $tmpDir;
    
    private $tmpFiles = [];
    
    /**
     *
     * @var Logger 
     */
    private $logger;
    
    /**
     *
     * @var string
     */
    private $environment;
    
    /**
     * 
     * @param Logger $logger
     */
    public function __construct(Logger $logger, $environment)
    {
        $this->logger = $logger;
        $this->environment = $environment;
    }

    /**
     * @return bool
     */
    public function isWindows() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * @return string
     */
    public function getBinary()
    {
        if ($this->isWindows()) {
            return 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf';
        }
        return $this->binary;
    }

    /**
     * @param string $binary
     * @return string
     * @return $this
     */
    public function setBinary($binary)
    {
        $this->binary = realpath($binary);
        return $this;
    }
    
    /**
     *
     * @param string $name
     * @param string $value
     * @return $this
     * @see https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
     */
    public function setOption($name, $value = null)
    {
        if (!in_array($name, $this->enabledOptions)) {            
            throw new \InvalidArgumentException(sprintf('The option %s is not a valid option.', $name));
        }
        $this->options[$name] = $value;
        
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTmpDir()
    {
        if (!$this->tmpDir) {
            $this->tmpDir = sys_get_temp_dir();
        }
        return $this->tmpDir;
    }

    /**
     * @param string $dir
     * @return $this
     */
    public function setTmpDir($dir)
    {
        $this->tmpDir = realpath($dir);
        return $this;
    }

    /**
     * @param string $fixEncode
     * @return $this
     */
    public function setFixEncode($fixEncode)
    {
        $this->fixEncode = $fixEncode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFixEncode()
    {
        return $this->fixEncode;
    }

    /**
     * 
     * @param string $html
     * @param string $filename
     * @param array $options
     * @param bool $download
     * @return Response
     */
    public function generate($html, $filename, $options = [], $download = true)
    {
        $this->options = array_merge($this->options, $options);
        
        try {
            $tmpDest = $this->generateTmpFile(null, 'pdf');
            $tmpSource = $this->generateTmpFile($html, 'html');

            $this->runCommand($this->buildCommand($tmpSource, $tmpDest));
            
            if (!file_exists($tmpDest)) {
                throw new \RuntimeException(sprintf('The temporary file %s do not exists.', $tmpDest));
            }

            $content = file_get_contents($tmpDest);
            $this->removeTmpFiles();

            $contentDisposition = $download ? 'attachment' : 'inline';

            return new Response($content, Response::HTTP_OK, [
                'Content-type' => 'application/pdf',
                'Content-Disposition' => $contentDisposition . '; filename="'.$this->fixOutputFilename($filename).'.pdf"',
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->removeTmpFiles();
            
            return new Response('Ocorreu um erro na execução.');
        }
    }
    
    /**
     * 
     * @param string $content
     * @param string $extension
     * @return string
     * @throws \RuntimeException
     */
    private function generateTmpFile($content, $extension)
    {
        $dir = rtrim($this->getTmpDir(), DIRECTORY_SEPARATOR);
        
        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('The temporary folder %s can not be created\n', $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new \RuntimeException(sprintf('The folder %s is not writable\n', $dir));
        }
        
        $filename = $dir . DIRECTORY_SEPARATOR . uniqid('wkhtmltopdf') . '.' . $extension;
        
        if (null !== $content) {
            if (false === file_put_contents($filename, $content)) {
                throw new \RuntimeException(sprintf('The file %s can not be created.\n', $filename));
            }            
        }
        
        $this->tmpFiles[] = $filename;
        
        return $filename;
    }

    /**
     * @param $sourceFilename
     * @param $destFilename
     * @return string
     */
    private function buildCommand($sourceFilename, $destFilename)
    {
        $options = '';
        
        if ($this->options) {
            foreach ($this->options as $option => $value) {
                if ($value !== null && $value !== '') {
                    $value = ' ' . escapeshellarg($value);
                }
                $options .= ' --' . $option . $value;
            }
        }


        if ($this->isWindows()) {
            $command = escapeshellarg($this->getBinary());
        } else {
            $command = 'cd ' . dirname($this->getBinary()) . ' && LC_CTYPE=' . $this->fixEncode . ' ' . basename($this->getBinary());
        }
        $command .= $options . ' ' . escapeshellarg($sourceFilename) . ' ' . escapeshellarg($destFilename);
        
        return $command;
    }
    
    private function runCommand($command)
    {
        exec($command);
        $this->logger->debug($command);        
    }
    
    private function removeTmpFiles()
    {
        if ($this->tmpFiles) {
            foreach ($this->tmpFiles as $filename) {
                if (file_exists($filename)) {
                    @unlink($filename);
                }
            }
        }
    }
    
    /**
     * Prevent the output name to have duplicate extension
     * 
     * @param string $filename
     * @return string
     */
    private function fixOutputFilename($filename)
    {
        $filename = explode('.', $filename);
        return strtolower(utf8_decode(current($filename)));
    }
}
