<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SecretariaSaude
 *
 * @ORM\Table(name="DBPET.TB_SECRETARIA_SAUDE")
 * @ORM\Entity(repositoryClass="App\Repository\SecretariaSaudeRepository")
 */
class SecretariaSaude extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_SECRETARIA_SAUDE", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_SECRETSAUDE_COSEQSECRETSAUD", allocationSize=1, initialValue=1)
     */
    private $coSeqSecretariaSaude;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Projeto", inversedBy="secretarias")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var PessoaJuridica
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaJuridica")
     * @ORM\JoinColumn(name="NU_CNPJ", referencedColumnName="NU_CNPJ")
     */
    private $pessoaJuridica;

    /**
     * @param Projeto $projeto
     * @param PessoaJuridica $pessoaJuridica
     */
    public function __construct(Projeto $projeto, PessoaJuridica $pessoaJuridica)
    {
        $this->projeto = $projeto;
        $this->pessoaJuridica = $pessoaJuridica;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqSecretariaSaude
     *
     * @return int
     */
    public function getCoSeqSecretariaSaude()
    {
        return $this->coSeqSecretariaSaude;
    }

    /**
     * Get projeto
     *
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * Get pessoaJuridica
     *
     * @return PessoaJuridica
     */
    public function getPessoaJuridica()
    {
        return $this->pessoaJuridica;
    }
}

