<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddMunicipioFieldSubscriber extends LoadFieldAbstractSubscriber
{
    public function addField(Form $form, $param)
    {
        $form->add($this->target, EntityType::class, array(
            'label' => 'Município',
            'class' => 'AppBundle:Municipio',
            'choice_label' => 'noMunicipio',
            'query_builder' => function(EntityRepository $er) use ($param) {
                return $er->createQueryBuilder('m')
                    ->where('m.coUfIbge = :coUfIbge')
                    ->andWhere("m.stRegistroAtivo = 'S'")
                    ->setParameter('coUfIbge', $param)
                    ->orderBy('m.noMunicipio', 'ASC')
                ;
            },
            'placeholder' => ''
        ));
    }
}
