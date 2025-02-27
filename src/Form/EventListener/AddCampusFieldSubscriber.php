<?php

namespace App\Form\EventListener;

use Symfony\Component\Form\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddCampusFieldSubscriber extends LoadFieldAbstractSubscriber
{
    public function __construct(string $target, string $origin)
    {
        parent::__construct($target, $origin);
    }

    public function addField(Form $form, $param)
    {
        $form->add($this->target, EntityType::class, array(
            'label' => 'Campus',
            'class' => 'App:CampusInstituicao',
            'choice_label' => 'noCampus',
            'required' => false,
            'query_builder' => function(EntityRepository $er) use ($param){
                return $er->createQueryBuilder('ci')
                    ->where('ci.instituicao = :instituicao')
                    ->andWhere("ci.stRegistroAtivo = 'S'")
                    ->setParameter('instituicao', $param)
                    ->orderBy('ci.noCampus', 'ASC');
            }
        ));
    }
}
