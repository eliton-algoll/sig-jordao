<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddInstituicaoFieldSubscriber extends LoadFieldAbstractSubscriber
{

    public function addField(Form $form, $param)
    {
        $form->add($this->target, EntityType::class, array(
            'label' => 'Instituição',
            'class' => 'AppBundle:Instituicao',
            'choice_label' => 'noInstituicaoProjeto',
            'required' => false,
            'query_builder' => function(EntityRepository $er) use ($param) {
                return $er->createQueryBuilder('i')
                    ->where('i.municipio = :municipio')
                    ->andWhere("i.stRegistroAtivo = 'S'")
                    ->setParameter('municipio', $param)
                    ->orderBy('i.noInstituicaoProjeto', 'ASC')
                ;
            }
        ));
    }

}
