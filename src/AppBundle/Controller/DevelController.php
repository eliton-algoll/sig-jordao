<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class DevelController extends ControllerAbstract 
{
    private function _getEnv()
    {
        return $this->get('kernel')->getEnvironment();
    }
    
    private function _getRootDir()
    {
        $rootdir = str_replace('/', DIRECTORY_SEPARATOR ,$this->get('kernel')->getRootdir());
        $rootdir = str_replace('app', '', $rootdir);

        return str_replace(' ','\\ ', $rootdir);
    }
    
    private function _getConsoleCommand()
    {
        return 'cd '.$this->_getRootDir().' && php '.$this->_getRootDir().'bin'.DIRECTORY_SEPARATOR.'console';
    }
    
    
    /**
     * @Route("devel", name="devel")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('devel/index.html.twig');
    }
    
    /**
     * @Route("/devel/console", name="devel_console")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function consoleAction(Request $request) 
    {
        
        $cacheClear    = $this->_getConsoleCommand() . ' cache:clear --env=' . $this->_getEnv();
        $asseticDump   = $this->_getConsoleCommand() . ' assetic:dump --env=' . $this->_getEnv();
        $assetsInstall = $this->_getConsoleCommand() . ' assets:install --env=' . $this->_getEnv();
        
        exec($cacheClear, $out1);
        exec($asseticDump, $out2);
        exec($assetsInstall, $out3);
        
        VarDumper::dump('cache:clear');
        VarDumper::dump($out1);
        VarDumper::dump('assetic:dump');
        VarDumper::dump($out2);
        VarDumper::dump('assets:install');
        VarDumper::dump($out3);
        
        return new Response('Fim');
    }
    
    /**
     * @Route("devel/all-logs", name="devel_all_logs")
     * @return \AppBundle\Controller\Response
     */
    public function allLogsAction()
    {
        $command = 'cd ' . $this->_getRootDir() . ' && cat var' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $this->_getEnv() . '*';
        
        exec($command, $out);
        
        VarDumper::dump($command);
        VarDumper::dump($out);
        
        return new Response('Fim');
        
        $out = "\n". $command . "\n".  implode("\n", $out);
        
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="error_' . date('d_m_Y_H_i_s') . '.log"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->setContent($out);
        
        return $response;
    }

    /**
     * @Route("devel/errors-log", name="devel_errors_log")
     * @return \AppBundle\Controller\Response
     */
    public function errorsLogAction()
    {
        echo "<pre>";
        $out = file_get_contents(__DIR__ . '/../../../var/logs/errors.log');
        echo $out;
        echo "</pre>";
        exit();

        return new Response('Fim');
    }
    
    /**
     * @Route("devel/last-logs", name="devel_last_logs")
     * @return \AppBundle\Controller\Response
     */
    public function lastLogsAction()
    {
        $command = 'cd ' . $this->_getRootDir() . ' && tail var' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $this->_getEnv() .'*';
        
        exec($command, $out);
        
        VarDumper::dump($command);
        VarDumper::dump($out);
        
        return new Response('Fim');
    }
    
    /**
     * @Route("devel/files", name="devel_files")
     * @return Response
     */
    public function filesAction()
    {
        $command1 = 'cd ' . $this->_getRootDir() . ' && cd app && ls -laR';
        $command2 = 'cd ' . $this->_getRootDir() . ' && cd src && ls -laR';
        $command3 = 'cd ' . $this->_getRootDir() . ' && cd web/css && ls -laR';
        $command4 = 'cd ' . $this->_getRootDir() . ' && cd web/js && ls -laR';
        
        exec($command1, $out1);
        exec($command2, $out2);
        exec($command3, $out3);
        exec($command4, $out4);
        
        VarDumper::dump($command1);
        VarDumper::dump($out1);
        VarDumper::dump($command2);
        VarDumper::dump($out2);
        VarDumper::dump($command3);
        VarDumper::dump($out3);
        VarDumper::dump($command4);
        VarDumper::dump($out4);
        
        return new Response('Fim');
    }
    
    /**
     * @Route("devel/delete-cache", name="devel_delete_cache")
     * @return Response
     */
    public function deleteCacheAction()
    {
        $command = 'cd ' . $this->_getRootDir() . ' && cd var' . DIRECTORY_SEPARATOR . 'cache && rm -rf * 2>&1';
        
        exec($command, $out);
        
        VarDumper::dump($command);
        VarDumper::dump($out);
        
        return new Response('Fim');
    }
    
    /**
     * @Route("devel/svn-files/r/{r}", name="devel_svn_files")
     * @return Response 
     */
    public function svnFilesAction(Request $request)
    {
        $commandSvnInfo = 'cd ' . $this->_getRootDir() . ' && svn info';
        
        exec($commandSvnInfo, $outSvnInfo);
        
        $haystack = array_values($outSvnInfo);
        $matches  = preg_grep('/^Last Changed Rev: \d+/i', $haystack);
        $matches  = reset($matches);
        $revision = preg_replace('/[^[\d]/', '', $matches);
        
        VarDumper::dump($commandSvnInfo);
        VarDumper::dump($outSvnInfo);
        VarDumper::dump($revision);
        
        if($r = $request->attributes->get('r')) {
        
            $commandDiff = 'cd ' . $this->_getRootDir() . ' && svn diff --summarize -r' . $revision . ':' . $r . ' --username emmanuel.garcia@saude.gov.br --password 32244134 --force-interactive 2>&1';
            
            echo exec($commandDiff, $outDiff);
            
            VarDumper::dump($commandDiff);
            VarDumper::dump($outDiff);
        }
        
        return new Response('Fim');
    }
}
