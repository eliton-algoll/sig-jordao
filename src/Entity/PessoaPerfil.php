<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PessoaPerfil
 *
 * @ORM\Table(name="DBPETINFOSD.TB_PESSOA_PERFIL")
 * @ORM\Entity(repositoryClass="App\Repository\PessoaPerfilRepository")
 */
class PessoaPerfil extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PESSOA_PERFIL", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PESSOAPERFIL_COSEQPESSOPERF", allocationSize=1, initialValue=1)
     */
    private $coSeqPessoaPerfil;

    /**
     * @var Perfil
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Perfil")
     * @ORM\JoinColumn(name="CO_PERFIL", referencedColumnName="CO_SEQ_PERFIL")
     */
    private $perfil;
    
    /**
     * @var PessoaFisica
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaFisica", inversedBy="pessoasPerfis", cascade={"persist"})
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF")
     */
    private $pessoaFisica;
    
    /**
     * @var ArrayCollection<ProjetoPessoa>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoPessoa", mappedBy="pessoaPerfil", cascade={"persist"})
     */
    private $projetosPessoas;
    
    /**
     * @param Perfil $perfil
     * @param PessoaFisica $pessoaFisica
     */
    public function __construct(Perfil $perfil, PessoaFisica $pessoaFisica)
    {
        $this->perfil = $perfil;
        $this->pessoaFisica = $pessoaFisica;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->projetosPessoas = new ArrayCollection();
    }

    /**
     * Get coSeqPessoaPerfil
     *
     * @return int
     */
    public function getCoSeqPessoaPerfil()
    {
        return $this->coSeqPessoaPerfil;
    }

    /**
     * Get perfil
     *
     * @return Perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * @param Perfil|mixed|null $perfil
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
    }

    /**
     * Get pessoaFisica
     *
     * @return PessoaFisica
     */
    public function getPessoaFisica()
    {
        return $this->pessoaFisica;
    }
    
    /**
     * @return ArrayCollection<ProjetoPessoa>
     */
    public function getProjetosPessoas()
    {
        return $this->projetosPessoas;
    }
    
    /**
     * @return ArrayCollection<ProjetoPessoa>
     */
    public function getProjetosPessoasAtivos()
    {
        return $this->projetosPessoas->filter(function ($projetoPessoa) {
            return $projetoPessoa->isAtivo();
        });
    }
    
    private function inativarAllProjetosPessoas()
    {
        foreach ($this->projetosPessoas->toArray() as $projetoPessoa) {
            $projetoPessoa->inativar();
        }
    }
    
    /**
     * @param Projeto $projeto
     * @return boolean
     */
    public function isProjetoVinculado(Projeto $projeto)
    {
        foreach ($this->projetosPessoas->toArray() as $projetoVinculado) {
            if ($projetoVinculado->getProjeto() == $projeto) {
                return $projetoVinculado;
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param Projeto $projeto
     * @param string $stVoluntarioProjeto
     * @return ProjetoPessoa
     */
    public function addProjetoPessoa(Projeto $projeto, $stVoluntarioProjeto = 'N', $coEixoAtuacao = null, $genero = null, $filenameBank = null, $filenameMatricula = null)
    {
        $this->inativarAllProjetosPessoas();

        if ($projetoVinculado = $this->isProjetoVinculado($projeto)) {
            $projetoVinculado->ativar();
        } else {
            $projetoVinculado = new ProjetoPessoa($projeto, $this, $stVoluntarioProjeto, $coEixoAtuacao, $genero, $filenameBank, $filenameMatricula);
            $this->projetosPessoas->add($projetoVinculado);
        }
        
        return $projetoVinculado;
    }
    
    /**
     * @return Projeto[]
     */
    public function getProjetos()
    {
        $projetos = array();
        
        foreach ($this->getProjetosPessoasAtivos() as $projetoPessoa) {
            $projetos[] = $projetoPessoa->getProjeto();
        }
        
        return $projetos;
    }
 
    /**
     * @param Projeto $projeto
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa($projeto)
    {
        $projetosPessoa = $this->projetosPessoas->filter(function ($projetoPessoa) use ($projeto) {
            if ($projetoPessoa->getProjeto() == $projeto) {
                return $projetoPessoa;
            }
        });
        
        return $projetosPessoa->first();
    }
}