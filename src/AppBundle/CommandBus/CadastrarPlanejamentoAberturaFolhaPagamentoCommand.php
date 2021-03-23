<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\PlanejamentoAnoFolha;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CadastrarPlanejamentoAberturaFolhaPagamentoCommand
{
    /**
     *
     * @var PlanejamentoAnoFolha
     */
    private $planejamentoAnoFolha;
    
    /**
     *
     * @var integer
     * 
     * @Assert\NotBlank()
     */    
    private $coPublicacao;
    
    /**
     *
     * @var integer
     * 
     * @Assert\NotBlank()
     */
    private $nuAnoReferencia;    
    
    /**
     *
     * @var array
     *      
     */
    private $mesesReferencia = [];
    
    public function __construct()
    {
        for ($i = 1; $i <= 12; $i++) {
            $date = \DateTime::createFromFormat("!m", $i);        
            $this->mesesReferencia[$i] = [
                'label' => $date->format('F'),
                'nuDia' => null,                
            ];
        }
    }
    
    /**
     * 
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $this->coPublicacao = $request->get('coPublicacao');
        $this->nuAnoReferencia = $request->get('nuAnoReferencia');
        if ($request->get('mesesReferencia')) {
            foreach ($request->get('mesesReferencia') as $mes => $dia) {                
                $this->mesesReferencia[$mes]['nuDia'] = $dia;                
            }
        }
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoPublicacao()
    {
        return $this->coPublicacao;
    }
        
    /**
     * 
     * @return PlanejamentoAnoFolha
     */
    public function getPlanejamentoAnoFolha()
    {
        return $this->planejamentoAnoFolha;
    }
        
    /**
     * 
     * @return integer
     */
    public function getNuAnoReferencia()
    {
        return $this->nuAnoReferencia;
    }

    /**
     * 
     * @return array
     */
    public function getMesesReferencia()
    {
        return $this->mesesReferencia;
    }
    
    /**
     * 
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     */
    public function setPlanejamentoAnoFolha(PlanejamentoAnoFolha $planejamentoAnoFolha)
    {        
        $this->planejamentoAnoFolha = $planejamentoAnoFolha;
        $this->coPublicacao = $planejamentoAnoFolha->getPublicacao()->getCoSeqPublicacao();
        $this->nuAnoReferencia = $planejamentoAnoFolha->getNuAno();        
        
        foreach ($planejamentoAnoFolha->getPlanejamentoMesesFolhaAtivos() as $planejamentoMesFolha) {
            $mes = (int) $planejamentoMesFolha->getNuMes();
            $this->mesesReferencia[$mes]['nuDia'] = $planejamentoMesFolha->getNuDiaAbertura();            
        }        
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateMeses(ExecutionContextInterface $context)
    {
        if (count($this->mesesReferencia) !== 12) {
            $context->buildViolation('Quantidade inválida de meses')->addViolation();
        } else {
            $vazios = 0;
            
            foreach ($this->mesesReferencia as $mes => $data) {
                if ($data['nuDia'] == '') {
                    $vazios++;
                }
                if ($data['nuDia'] && !ctype_digit((string) $data['nuDia'])) {
                    $context->buildViolation('Apenas número')
                        ->atPath('mes_' . $mes)
                        ->addViolation();
                }
            }
            
            if ($vazios == count($this->mesesReferencia)) {
                $context->buildViolation('É obrigatório que pelo menos um mês de referência tenha dia de abertura planejado para salvar o Planejamento do Ano de Referência. Operação não permitida.')                    
                    ->addViolation();
            }
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateDiaDoMes(ExecutionContextInterface $context)
    {
        foreach ($this->mesesReferencia as $mes => $data) {
            $date = \DateTime::createFromFormat('Ymd', $this->nuAnoReferencia . str_pad($mes, 2, '0', STR_PAD_LEFT) . '01');            
            if ($date instanceof \DateTime) {
                $date->modify('last day of this month');
                if (ctype_digit((string) $data['nuDia']) && $date->format('d') < $data['nuDia']) {
                    $context->buildViolation('Não é permitido informar um Dia de Abertura maior que o último dia do respectivo mês. Verifique as informações.')
                        ->atPath('mes_' . $mes)
                        ->addViolation();
                }
            }
        }
    }
}
