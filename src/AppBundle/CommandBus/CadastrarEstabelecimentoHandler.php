<?php

namespace AppBundle\CommandBus;

use AppBundle\WebServices\Cnes;
use AppBundle\CommandBus\CadastrarEstabelecimentoCommand;
use AppBundle\Repository\ProjetoRepository;

class CadastrarEstabelecimentoHandler
{
    /**
     * @var ProjetoRepository
     */
    private $projetoRepository;
    
    /**
     * @var Cnes
     */
    private $wsCnes;
    
    /**
     * @param ProjetoRepository $projetoRepository
     */
    public function __construct(ProjetoRepository $projetoRepository)
    {
        $this->projetoRepository = $projetoRepository;
//        $this->wsCnes = $wsCnes;
    }

    /**
     * @param CadastrarEstabelecimentoCommand $command
     */
    public function handle(CadastrarEstabelecimentoCommand $command)
    {
//        if (!$this->isCnesValido($command->getCoCnes())) {
//            throw new \InvalidArgumentException('Número do CNES inválido ou inexistente.');
//        }
        
        $projeto = $this->projetoRepository->find($command->getCoProjeto());
        $projeto->addEstabelecimento($command->getCoCnes());
        $this->projetoRepository->add($projeto);
    }
    
    /**
     * @param string $coCnes
     * @return boolean
     */
    private function isCnesValido($coCnes)
    {
        try {
            return (bool) $this->wsCnes->consultarEstabelecimentoSaude($coCnes);
        } catch (\SoapFault $e) {
            return false;
        }
    }
}