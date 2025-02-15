<?php

namespace App\CommandBus;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use App\Repository\RodapeArquivoRetornoPagamentoRepository;
use App\Repository\RetornoPagamentoRepository;
use App\Repository\DetalheArquivoRetornoPagamentoRepository;
use App\Repository\CabecalhoArquivoRetornoPagamentoRepository;
use App\Repository\AutorizacaoFolhaRepository;
use App\Facade\FileUploaderFacade;
use App\Facade\FileNameGeneratorFacade;
use App\Exception\UnexpectedCommandBehaviorException;
use App\Exception\ArquivoRetornoProcessadoException;
use App\Entity\RodapeArquivoRetornoPagamento;
use App\Entity\RetornoPagamento;
use App\Entity\FolhaPagamento;
use App\Entity\DetalheArquivoRetornoPagamento;
use App\Entity\CabecalhoArquivoRetornoPagamento;
use App\Cpb\ArquivoRetornoPagamento;
use App\CommandBus\RecepcionarArquivoRetornoPagamentoCommand;

final class RecepcionarArquivoRetornoPagamentoHandler
{
    /**
     *
     * @var ArquivoRetornoPagamento 
     */
    private $arquivoRetornoPagamento;
    
    /**
     *
     * @var RetornoPagamentoRepository 
     */
    private $retornoPagamentoRepository;
    
    /**
     *
     * @var RodapeArquivoRetornoPagamentoRepository 
     */
    private $rodapeArquivoRetornoPagamentoRepository;
    
    /**
     *
     * @var DetalheArquivoRetornoPagamentoRepository 
     */
    private $detalheArquivoRetornoPagamentoRepository;
    
    /**
     *
     * @var CabecalhoArquivoRetornoPagamentoRepository 
     */
    private $cabecalhoArquivoRetornoPagamentoRepository;
    
    /**
     *
     * @var AutorizacaoFolhaRepository 
     */
    private $autorizacaoFolhaRepository;
    
    /**
     *
     * @var FileUploaderFacade 
     */
    private $fileUploader;
    
    /**
     *
     * @var FileNameGeneratorFacade 
     */
    private $fileNameGenerator;
    
    /**
     * 
     * @param ArquivoRetornoPagamento $arquivoRetornoPagamento
     * @param RetornoPagamentoRepository $retornoPagamentoRepository
     * @param RodapeArquivoRetornoPagamentoRepository $rodapeArquivoRetornoPagamentoRepository
     * @param DetalheArquivoRetornoPagamentoRepository $detalheArquivoRetornoPagamentoRepository
     * @param CabecalhoArquivoRetornoPagamentoRepository $cabecalhoArquivoRetornoPagamentoRepository
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $fileNameGenerator
     */
    public function __construct(
        ArquivoRetornoPagamento $arquivoRetornoPagamento,
        RetornoPagamentoRepository $retornoPagamentoRepository,
        RodapeArquivoRetornoPagamentoRepository $rodapeArquivoRetornoPagamentoRepository,
        DetalheArquivoRetornoPagamentoRepository $detalheArquivoRetornoPagamentoRepository,
        CabecalhoArquivoRetornoPagamentoRepository $cabecalhoArquivoRetornoPagamentoRepository,
        AutorizacaoFolhaRepository $autorizacaoFolhaRepository,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $fileNameGenerator
    ) {
        $this->arquivoRetornoPagamento = $arquivoRetornoPagamento;
        $this->retornoPagamentoRepository = $retornoPagamentoRepository;
        $this->rodapeArquivoRetornoPagamentoRepository = $rodapeArquivoRetornoPagamentoRepository;
        $this->detalheArquivoRetornoPagamentoRepository = $detalheArquivoRetornoPagamentoRepository;
        $this->cabecalhoArquivoRetornoPagamentoRepository = $cabecalhoArquivoRetornoPagamentoRepository;
        $this->autorizacaoFolhaRepository = $autorizacaoFolhaRepository;
        $this->fileUploader = $fileUploader;
        $this->fileNameGenerator = $fileNameGenerator;
    }

    /**
     * 
     * @param RecepcionarArquivoRetornoPagamentoCommand $command
     * @throws UnexpectedCommandBehaviorException
     */
    public function handle(RecepcionarArquivoRetornoPagamentoCommand $command)
    {
        $this->checkArquivoWasProcessado($command->getArquivoRetorno());
        $this->arquivoRetornoPagamento->load($command->getArquivoRetorno());
        
        if ($this->arquivoRetornoPagamento->hasError()) {
            throw UnexpectedCommandBehaviorException::onHandle($this->arquivoRetornoPagamento->getErrors());
        }
        
        $filename = $this->fileNameGenerator->generate($command->getArquivoRetorno());
        
        $retornoPagamento = new RetornoPagamento(
            $command->getFolhaPagamento(),
            $command->getArquivoRetorno()->getClientOriginalName(),
            $filename,
            $this->arquivoRetornoPagamento->getTotal(),
            $this->arquivoRetornoPagamento->getTotalPago(),
            $this->arquivoRetornoPagamento->getTotalNaoPago()
        );
        
        $cabecalho = new CabecalhoArquivoRetornoPagamento($retornoPagamento, $this->arquivoRetornoPagamento->getHeader());
        $rodape = new RodapeArquivoRetornoPagamento($retornoPagamento, $this->arquivoRetornoPagamento->getTrailer());
        
        $this->addDetalhes($command->getFolhaPagamento(), $retornoPagamento);
        $this->retornoPagamentoRepository->add($retornoPagamento);
        $this->cabecalhoArquivoRetornoPagamentoRepository->add($cabecalho);
        $this->rodapeArquivoRetornoPagamentoRepository->add($rodape);
        
        $this->fileUploader->upload($command->getArquivoRetorno(), $filename);
    }
    
    /**
     * 
     * @param UploadedFile $file
     * @throws UnexpectedCommandBehaviorException
     */
    private function checkArquivoWasProcessado(UploadedFile $file)
    {
        try {
            $this->retornoPagamentoRepository->getByArquivoOriginal($file->getClientOriginalName());
            throw new ArquivoRetornoProcessadoException();
        } catch (ArquivoRetornoProcessadoException $ex) {
            throw UnexpectedCommandBehaviorException::onHandle($ex->getMessage());
        } catch (\Exception $e) {}
    }
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @param RetornoPagamento $retornoPagamento
     * @throws UnexpectedCommandBehaviorException
     */
    private function addDetalhes(FolhaPagamento $folhaPagamento, RetornoPagamento $retornoPagamento)
    {
        $errors = array();
        
        foreach ($this->arquivoRetornoPagamento->getDetalhes() as $detalhe) {
            try {
                $autorizacaoFolha = $this->autorizacaoFolhaRepository->getByFolhaAndPartialCpf($folhaPagamento, $detalhe->getPartialCpf());
                $detalheArquivoRetornoPagamento = new DetalheArquivoRetornoPagamento($retornoPagamento, $detalhe);
                $autorizacaoFolha->setDetalheArquivoRetornoPagamento($detalheArquivoRetornoPagamento);
                
                $this->detalheArquivoRetornoPagamentoRepository->add($detalheArquivoRetornoPagamento);            
                $this->autorizacaoFolhaRepository->add($autorizacaoFolha);
            } catch (NoResultException $nre) {
                array_push($errors, 'O profissional da linha '. $detalhe->getLinha() .' não está na folha '. $folhaPagamento->getNuMesAno() . ' - ' . $folhaPagamento->getNuSipar());
            } catch (NonUniqueResultException $nure) {
                array_push($errors, 'Mais de um profissional foi encontrado na folha com o início de CPF '. $detalhe->getPartialCpf());
            }
        }
        
        if (0 < count($errors)) {
            throw UnexpectedCommandBehaviorException::onHandle($errors);
        }
    }
}
