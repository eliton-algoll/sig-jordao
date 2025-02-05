<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\ConsultarValorBolsaCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ConsultarValorBolsaType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tipoConsulta', ChoiceType::class, [
            'choices' => [
                'Valor Vigente' => 'V',
                'Histórico' => 'H',
                'Vigencia Futura' => 'F',
            ],
            'label' => 'Tipo de Consulta',
            'constraints' => [
                new NotBlank([ 'message' => 'Este valor não deve ser vazio.' ])
            ],
        ])->add('publicacao', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
            },
            'choice_label' => function($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',
            'placeholder' => 'Selecione',
            'required' => false,
            'constraints' => [
                new Callback([ 'callback' => [$this, 'validateTipoConsulta'] ])
            ],
        ])->add('tipoParticipante', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'choice_label' => 'dsPerfil',
            'label' => 'Tipo de Participante',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->setMethod('GET');
    }
    
    /**
     * 
     * @param string $data
     * @param ExecutionContextInterface $context
     */
    public function validateTipoConsulta($data, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getViewData();
        
        if (isset($formData['tipoConsulta']) && $formData['tipoConsulta'] == 'H' && !$data) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('publicacao')
                ->addViolation();
        }
    }
}
