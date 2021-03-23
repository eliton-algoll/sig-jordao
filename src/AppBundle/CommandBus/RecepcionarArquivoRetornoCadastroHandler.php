<?php

namespace AppBundle\CommandBus;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Repository\RodapeRetornoCriacaoContaRepository;
use AppBundle\Repository\RetornoCriacaoContaRepository;
use AppBundle\Repository\DetalheRetornoCriacaoContaRepository;
use AppBundle\Repository\DadoPessoalRepository;
use AppBundle\Repository\CabecalhoRetornoCriacaoContaRepository;
use AppBundle\Repository\BancoRepository;
use AppBundle\Repository\AgenciaBancariaRepository;
use AppBundle\Facade\FileUploaderFacade;
use AppBundle\Facade\FileNameGeneratorFacade;
use AppBundle\Exception\UnexpectedCommandBehaviorException;
use AppBundle\Exception\ArquivoRetornoProcessadoException;
use AppBundle\Entity\RodapeRetornoCriacaoConta;
use AppBundle\Entity\RetornoCriacaoConta;
use AppBundle\Entity\DetalheRetornoCriacaoConta;
use AppBundle\Entity\CabecalhoRetornoCriacaoConta;
use AppBundle\Cpb\ArquivoRetornoCadastro;

final class RecepcionarArquivoRetornoCadastroHandler
{
    /**
     *
     * @var ArquivoRetornoCadastro 
     */
    private $arquivoRetornoCadastro;
    
    /**
     *
     * @var RetornoCriacaoContaRepository 
     */
    private $retornoCriacaoContaRepository;
    
    /**
     *
     * @var CabecalhoRetornoCriacaoContaRepository 
     */
    private $cabecalhoRetornoPagamentoRepository;
    
    /**
     *
     * @var DetalheRetornoCriacaoContaRepository 
     */
    private $detalheRetornoCriacaoContaRepository;
    
    /**
     *
     * @var RodapeRetornoCriacaoContaRepository 
     */
    private $rodapeRetornoCriacaoContaRepository;
    
    /**
     *
     * @var DadoPessoalRepository 
     */
    private $dadoPessoalRepository;
    
    /**
     *
     * @var BancoRepository
     */
    private $bancoRepository;
    
    /**
     *
     * @var AgenciaBancariaRepository
     */
    private $agenciaBancariaRepository;
    
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
     * @param ArquivoRetornoCadastro $arquivoRetornoCadastro
     * @param RetornoCriacaoContaRepository $retornoCriacaoContaRepository
     * @param CabecalhoRetornoCriacaoContaRepository $cabecalhoRetornoPagamentoRepository
     * @param DetalheRetornoCriacaoContaRepository $detalheRetornoCriacaoContaRepository
     * @param RodapeRetornoCriacaoContaRepository $rodapeRetornoCriacaoContaRepository
     * @param DadoPessoalRepository $dadoPessoalRepository
     * @param BancoRepository $bancoRepository
     * @param AgenciaBancariaRepository $agenciaBancariaRepository
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $fileNameGenerator
     */
    public function __construct(
        ArquivoRetornoCadastro $arquivoRetornoCadastro,
        RetornoCriacaoContaRepository $retornoCriacaoContaRepository,
        CabecalhoRetornoCriacaoContaRepository $cabecalhoRetornoPagamentoRepository,
        DetalheRetornoCriacaoContaRepository $detalheRetornoCriacaoContaRepository,
        RodapeRetornoCriacaoContaRepository $rodapeRetornoCriacaoContaRepository,
        DadoPessoalRepository $dadoPessoalRepository,
        BancoRepository $bancoRepository,
        AgenciaBancariaRepository $agenciaBancariaRepository,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $fileNameGenerator
    ) {
        $this->arquivoRetornoCadastro = $arquivoRetornoCadastro;
        $this->retornoCriacaoContaRepository = $retornoCriacaoContaRepository;
        $this->cabecalhoRetornoPagamentoRepository = $cabecalhoRetornoPagamentoRepository;
        $this->detalheRetornoCriacaoContaRepository = $detalheRetornoCriacaoContaRepository;
        $this->rodapeRetornoCriacaoContaRepository = $rodapeRetornoCriacaoContaRepository;
        $this->dadoPessoalRepository = $dadoPessoalRepository;
        $this->bancoRepository = $bancoRepository;
        $this->agenciaBancariaRepository = $agenciaBancariaRepository;
        $this->fileUploader = $fileUploader;
        $this->fileNameGenerator = $fileNameGenerator;
    }
    
    /**
     * 
     * @param RecepcionarArquivoRetornoCadastroCommand $command
     */
    public function handle(RecepcionarArquivoRetornoCadastroCommand $command)
    {
        $this->checkArquivoWasProcessado($command->getArquivoRetorno());
        $this->arquivoRetornoCadastro->load($command->getArquivoRetorno());
        
        if ($this->arquivoRetornoCadastro->hasError()) {
            throw UnexpectedCommandBehaviorException::onHandle($this->arquivoRetornoCadastro->getErrors());
        }
        
        $filename = $this->fileNameGenerator->generate($command->getArquivoRetorno());
        
        $retornoCriacaoConta = new RetornoCriacaoConta(
            $command->getPublicacao(),
            $command->getArquivoRetorno()->getClientOriginalName(),
            $filename,
            $this->arquivoRetornoCadastro->getTotal(),
            $this->arquivoRetornoCadastro->getTotalContaCriada(),
            $this->arquivoRetornoCadastro->getTotalContaNaoCriada()
        );
        
        foreach ($this->arquivoRetornoCadastro->getDetalhes() as $detalhe) {
            
            $detalheRetornoCriacaoCont = new DetalheRetornoCriacaoConta($retornoCriacaoConta, $detalhe);
            
            try {
                $dadoPessoal =  $this->dadoPessoalRepository->getByCpfAndPublicacao($detalhe->getNuCpf(), $command->getPublicacao());
                $banco = $this->bancoRepository->find($this->arquivoRetornoCadastro->getHeader()->getIdBanco());
                $agencia = $this->agenciaBancariaRepository->findOneByAgenciaSemDv($this->arquivoRetornoCadastro->getHeader()->getIdBanco(), $detalhe->getIdOrgaoPagador());
                
                $dadoPessoal->setBanco($banco);
                $dadoPessoal->setAgencia($agencia);
                $this->dadoPessoalRepository->add($dadoPessoal);
            } catch (\Exception $ex) {                
                $detalheRetornoCriacaoCont->putAsNaoBeneficiarioPrograma();
            }
            
            $this->detalheRetornoCriacaoContaRepository->add($detalheRetornoCriacaoCont);
        }
        
        $this->retornoCriacaoContaRepository->add($retornoCriacaoConta);
        $this->cabecalhoRetornoPagamentoRepository->add(
            new CabecalhoRetornoCriacaoConta($retornoCriacaoConta, $this->arquivoRetornoCadastro->getHeader())
        );
        $this->rodapeRetornoCriacaoContaRepository->add(
            new RodapeRetornoCriacaoConta($retornoCriacaoConta, $this->arquivoRetornoCadastro->getTrailer())
        );
        
        $this->fileUploader->upload($command->getArquivoRetorno(), $filename);
    }
    
    /**
     * 
     * @param UploadedFile $file
     * @throws ArquivoRetornoProcessadoException
     */
    private function checkArquivoWasProcessado(UploadedFile $file)
    {
        try {
            $this->retornoCriacaoContaRepository->getByArquivoOriginal($file->getClientOriginalName());
            throw new ArquivoRetornoProcessadoException();
        } catch (ArquivoRetornoProcessadoException $ex) {
            throw UnexpectedCommandBehaviorException::onHandle($ex->getMessage());
        } catch (\Exception $e) {}
    }
}
