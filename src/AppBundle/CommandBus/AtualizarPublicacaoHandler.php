<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\TipoQuantitativoPublicacao;
use AppBundle\Entity\QuantidadePublicacao;
use AppBundle\Repository\PublicacaoRepository;
use AppBundle\Repository\TipoQuantitativoPublicacaoRepository;

class AtualizarPublicacaoHandler
{
    /**
     * @var PublicacaoRepository
     */
    private $publicacaoRepository;
    
    /**
     * @var TipoQuantitativoPublicacaoRepository
     */
    private $tipoQuantitativoRepository;
    
    /**
     * @param PublicacaoRepository $publicacaoRepository
     * @param TipoQuantitativoPublicacaoRepository $tipoQuantRepository
     */
    public function __construct($publicacaoRepository, $tipoQuantRepository)
    {
        $this->publicacaoRepository = $publicacaoRepository;
        $this->tipoQuantitativoRepository = $tipoQuantRepository;
    }

    /**
     * @param AtualizarPublicacaoCommand $command
     */
    public function handle(AtualizarPublicacaoCommand $command)
    {
        $publicacao = $this->publicacaoRepository->find($command->getCoSeqPublicacao());
        $publicacao
            ->setTpPublicacao($command->getTpPublicacao())
            ->setNuPublicacao($command->getNuPublicacao())
            ->setDsPublicacao($command->getDsPublicacao())
            ->setDtPublicacao($command->getDtPublicacao())
            ->setDtFimVigencia($command->getDtFimVigencia())
            ->setDtInicioVigencia($command->getDtInicioVigencia());
            
        $novasQuantidades = array(
            TipoQuantitativoPublicacao::QTD_MINIMA_BOLSISTAS_GRUPO => $command->getQtdMinimaBolsistasGrupo(),
            TipoQuantitativoPublicacao::QTD_MAXIMA_BOLSISTAS_GRUPO => $command->getQtdMaximaBolsistasGrupo()
        );
        
        foreach ($novasQuantidades as $tipo => $quantidade) {
            if ($publicacao->hasQuantidadeByTipo($tipo)) {
                $publicacao->getQuantidadeByTipo($tipo)->setQtParticipante($quantidade);
            } else {
                $publicacao->addQuantidade(
                    new QuantidadePublicacao(
                        $publicacao, 
                        $this->tipoQuantitativoRepository->find($tipo), 
                        $quantidade
                    )
                );
            }
        }
        
        $this->publicacaoRepository->add($publicacao);
    }
}