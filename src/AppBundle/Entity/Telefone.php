<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Pessoa;

/**
 * Telefone
 *
 * @ORM\Table(name="DBPET.TB_TELEFONE")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TelefoneRepository")
 */
class Telefone extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\MaskTrait;
    
    static $arrTpTelefone = [
        3 => 'Celular',
        2 => 'Comercial',
        1 => 'Residencial'
    ];
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_TELEFONE", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_TELEFONE_COSEQTELEFONE", allocationSize=1, initialValue=1)
     * 
     */
    private $coSeqTelefone;
    
    /**
     * @var Pessoa
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pessoa", inversedBy="telefones")
     * @ORM\JoinColumn(name="NU_CPF_CNPJ_PESSOA", referencedColumnName="NU_CPF_CNPJ_PESSOA")
     */
    private $pessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDD", type="string", length=4)
     */
    private $nuDdd;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_TELEFONE", type="string", length=10)
     */
    private $nuTelefone;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_TELEFONE", type="string", length=1)
     */
    private $tpTelefone;

    public function __construct(Pessoa $pessoa, $nuDdd, $nuTelefone, $tpTelefone)
    {
        $this->pessoa     = $pessoa;
        $this->nuDdd      = $nuDdd;
        $this->nuTelefone = $nuTelefone;
        $this->tpTelefone = $tpTelefone;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqTelefone
     *
     * @return int
     */
    public function getCoSeqTelefone()
    {
        return $this->coSeqTelefone;
    }

    /**
     * Get nuDdd
     *
     * @return string
     */
    public function getNuDdd()
    {
        return $this->nuDdd;
    }

    /**
     * Get nuTelefone
     *
     * @return string
     */
    public function getNuTelefone()
    {
        return $this->nuTelefone;
    }
    /**
     * Get nuTelefone sem máscara
     *
     * @return string
     */
    public function getNuTelefoneClean()
    {
        return $this->unmask($this->nuTelefone);
    }

    /**
     * Get tpTelefone
     *
     * @return string
     */
    public function getTpTelefone()
    {
        return $this->tpTelefone;
    }
    
    /**
     * Get Pessoa
     * 
     * @return Pessoa
     */
    public function getPessoa()
    {
        return $this->pessoa;
    }
    
    /**
     * Get Array TpTelefones
     * 
     * @return Array
     */
    public function getTpTelefones()
    {
        return self::$arrTpTelefone;
    }
    
    /**
     * Get String DsTpTelefone
     * @param type $tpTelefone
     * @return string
     */
    public function getDsTpTelefone()
    {
        return self::$arrTpTelefone[$this->tpTelefone];
    }
    
    static function getDsTelefone($tpTelefone)
    {
        if(array_key_exists($tpTelefone, self::$arrTpTelefone)) {
            return self::$arrTpTelefone[$tpTelefone];
        }
    }
    
    /**
     * 
     * @param Pessoa $pessoa
     * @return Telefone
     */
    public function setPessoa(Pessoa $pessoa)
    {
        $this->pessoa = $pessoa;
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function __toArray()
    {
        return array(
            'coSeqTelefone' => $this->getCoSeqTelefone(),
            'nuDdd' => $this->getNuDdd(),
            'nuTelefone' => $this->getNuTelefone(),
            'tpTelefone' => array(
                'cod' => $this->getTpTelefone(),
                'descricao' => $this->getDsTelefone($this->getTpTelefone())
            )
        );
    }
}

