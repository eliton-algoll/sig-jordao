<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.VW_GERENCIAL_PAGAMENTO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\VwGerencialPagamentoRepository")
 */
class VwGerencialPagamento extends AbstractEntity
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PROJETO", type="integer")
     */
    private $coSeqProjeto;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="CO_SEQ_PUBLICACAO", type="integer")
     */
    private $coSeqPublicacao;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="PROGRAMA_PUBLICACAO", type="string")
     */
    private $dsProgramaPublicacao;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SIPAR_PROJETO", type="string")
     */
    private $nuSiparProjeto;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_MES", type="string")
     */
    private $nuMes;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ANO", type="string")
     */
    private $nuAno;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_REFERENCIA", type="string")
     */
    private $nuReferencia;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_TOTAL_BOLSA_APROVADA", type="integer")
     */
    private $qtTotalBolsaAprovada;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_FOLHA_AUTORIZADA", type="integer")
     */
    private $qtFolhaAutorizada;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_BOLSA_AUTORIZADA", type="integer")
     */
    private $qtBolsaAutorizada;
    
    /**
     *
     * @var float
     * 
     * @ORM\Column(name="QT_VALOR_FOLHA", type="float")
     */
    private $qtValorFolha;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="SECRETARIA_SAUDE", type="string")
     */
    private $noSecretariaSaude;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_INSTITUICAO_PROJETO", type="string")
     */
    private $noInstituicaoProjeto;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="GRUPO_ATUACAO", type="string")
     */
    private $noGrupoAtuacao;
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqProjeto()
    {
        return $this->coSeqProjeto;
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqPublicacao()
    {
        return $this->coSeqPublicacao;
    }
    
    /**
     * 
     * @return string
     */
    public function getDsProgramaPublicacao()
    {
        return $this->dsProgramaPublicacao;
    }

    /**
     * 
     * @return string
     */
    public function getNuSiparProjeto()
    {
        return $this->nuSiparProjeto;
    }

    /**
     * 
     * @return string
     */
    public function getNuMes()
    {
        return $this->nuMes;
    }

    /**
     * 
     * @return string
     */
    public function getNuAno()
    {
        return $this->nuAno;
    }
    
    /**
     * 
     * @return string
     */
    public function getNuReferencia()
    {
        return $this->nuReferencia;
    }
    
    /**
     * 
     * @return integer
     */
    public function getQtTotalBolsaAprovada()
    {
        return $this->qtTotalBolsaAprovada;
    }

    /**
     * 
     * @return integer
     */
    public function getQtFolhaAutorizada()
    {
        return $this->qtFolhaAutorizada;
    }
    
    /**
     * 
     * @return integer
     */
    public function getQtBolsaAutorizada()
    {
        return $this->qtBolsaAutorizada;
    }
    
    /**
     * 
     * @return float
     */
    public function getQtValorFolha()
    {
        return $this->qtValorFolha;
    }

    /**
     * 
     * @return string
     */
    public function getNoSecretariaSaude()
    {
        return $this->noSecretariaSaude;
    }

    /**
     * 
     * @return string
     */
    public function getNoInstituicaoProjeto()
    {
        return $this->noInstituicaoProjeto;
    }

    /**
     * 
     * @return string
     */
    public function getNoGrupoAtuacao()
    {
        return $this->noGrupoAtuacao;
    }
}
