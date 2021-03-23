<?php

namespace AppBundle\Report;

use AppBundle\Entity\Publicacao;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class GerencialPagamentoFilter
{
    public static $customizacaoMainData = [
        'Mês/Ano de Referência' => 'vw.nuReferencia',
        'Programa/Publicação' => 'vw.dsProgramaPublicacao',
        'Número SEI' => 'vw.nuSiparProjeto',        
    ];
    
    public static $customizacaoDetalheProjeto = [
        'Grupos de atuação no projeto' => 'vw.noGrupoAtuacao',
        'Instituição de Ensino' => 'vw.noInstituicaoProjeto',
        'Secretaria de Saúde' => 'vw.noSecretariaSaude',
    ];
    
    public static $customizacaoTotalizadores = [
        'Quantidade Total de bolsas aprovadas' => 'vw.qtTotalBolsaAprovada',
        'Quantidade de folhas homologadas' => 'vw.qtFolhaAutorizada',
        'Quantidade de bolsas homologadas' => 'vw.qtBolsaAutorizada',
        'Valor da Folha de Pagamento' => 'vw.qtValorFolha',
    ];
    
    /**
     *
     * @var Publicacao
     */
    private $publicacao;
    
    /**
     *
     * @var string
     */
    private $nuSipar;
    
    /**
     *
     * @var string
     */
    private $nuMes;
    
    /**
     *
     * @var string
     */
    private $nuAno;
    
    /**
     *
     * @var array
     */
    private $from_customizacao;
    
    /**
     *
     * @var array
     */
    private $to_customizacao;
    
    /**
     * 
     * @return array
     */
    public static function getCustomizacaoData()
    {
        return self::$customizacaoMainData + self::$customizacaoDetalheProjeto + self::$customizacaoTotalizadores;
    }
    
    /**
     * 
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * 
     * @return string
     */
    public function getNuSipar()
    {
        return $this->nuSipar;
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
     * @return array
     */
    public function getFromCustomizacao()
    {
        return $this->from_customizacao;
    }
    
    /**
     * 
     * @return array
     */
    public function getToCustomizacao()
    {
        return $this->to_customizacao;
    }

    /**
     * 
     * @param Publicacao $publicacao
     */
    public function setPublicacao(Publicacao $publicacao)
    {
        $this->publicacao = $publicacao;
    }

    /**
     * 
     * @param string $nuSipar
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
    }

    /**
     * 
     * @param string $nuMes
     */
    public function setNuMes($nuMes)
    {
        $this->nuMes = $nuMes;
    }

    /**
     * 
     * @param string $nuAno
     */
    public function setNuAno($nuAno)
    {
        $this->nuAno = $nuAno;
    }

    /**
     * 
     * @param array $to_customizacao
     */
    public function setToCustomizacao($to_customizacao)
    {
        $this->to_customizacao = $to_customizacao;
    }
    
    /**
     * 
     * @param array $from_customizacao
     */
    public function setFromCustomizacao($from_customizacao)
    {
        $this->from_customizacao = $from_customizacao;
    }
            
    /**
     * 
     * @return string
     */
    public function getSelectPart()
    {
        $select = [];
        
        foreach ($this->to_customizacao as $key => $custom) {
            if (in_array($custom, self::$customizacaoTotalizadores)) {
                $alias = explode('.', $custom);
                $select[$key] = 'SUM(' . $custom . ') as ' . end($alias);
            } else {                
                $select[$key] = $custom;
            }
        }
        
        return implode(', ', $select);
    }
    
    /**
     * 
     * @return string
     */
    public function getGroupByPart()
    {
        $group = [];
        
        foreach ($this->to_customizacao as $key => $custom) {
            if (!in_array($custom, self::$customizacaoTotalizadores)) {
                $group[$key] = $custom;
            }
        }
        
        return implode(', ', $group);
    }
    
    /**
     * 
     * @return array
     */
    public function getTitles()
    {
        $titles = [];        
        
        foreach ($this->to_customizacao as $key => $value) {
            $titles[$key] = array_search($value, self::getCustomizacaoData());
        }
        
        return $titles;
    }
    
    /**
     *      
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateAno(ExecutionContextInterface $context)
    {
        $now = new \DateTime();
        if ($this->nuAno && $this->nuAno > $now->format('Y')) {
            $context->buildViolation('O ano informado não pode ser maior que o ano atual.')
                ->atPath('nuAno')
                ->addViolation();
        } elseif ($this->nuAno && $this->nuAno < 1900) {
            $context->buildViolation('O ano está fora do intervalo aceito (1900 – 2100).')
                ->atPath('nuAno')
                ->addViolation();
        }
    }
    
    /**
     *      
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateReferencia(ExecutionContextInterface $context)
    {
        if ($this->nuMes && !$this->nuAno) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('nuAno')
                ->addViolation();
        } elseif (!$this->nuMes && $this->nuAno) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('nuMes')
                ->addViolation();
        }
    }
    
    /**
     *      
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateCustomizacao(ExecutionContextInterface $context)
    {
        $check = function($array1, $array2) {
            $return = false;
            foreach ($array1 as $key => $v)  {                
                if (in_array($v, $array2)) {
                    $return = true;
                }
            }  
            return $return;
        };
        
        if ((!$check(self::$customizacaoMainData, $this->to_customizacao) && !$check(self::$customizacaoDetalheProjeto, $this->to_customizacao)) || 
            !$check(self::$customizacaoTotalizadores, $this->to_customizacao)
        ) {
            $context->buildViolation('Para emissão do Relatório Gerencial de Pagamento é obrigatório selecionar para customização pelo menos uma informação principal (Grupo de Atuação do Projeto, Instituição de Ensino, Mês/Ano de Referência, Número SEI, Programa/Publicação, Secretaria de Saúde) e um totalizador (Quantidade Total de Bolsas aprovadas, Quantidade de Folhas autorizadas, Quantidade de Bolsas autorizadas, Valor da Folha de Pagamento). Operação não permitida.')
                ->atPath('to_customizacao')
                ->addViolation();
        }
    }
    
    /**
     *      
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateFiltros(ExecutionContextInterface $context)
    {
        if (
            !$this->publicacao &&
            !$this->nuSipar &&
            !$this->nuMes &&
            !$this->nuAno
        ) {
            $context->buildViolation('Para realizar a pesquisa é necessário que pelo menos um dos filtros seja informado. Operação não permitida.')
                ->atPath('publicacao')
                ->addViolation();                
        }
    }
}
