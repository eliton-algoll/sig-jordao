<?php

namespace App\Form\EventListener;

use Symfony\Component\Form\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddSecretariaFieldSubscriber extends LoadFieldAbstractSubscriber
{
    public function addField(Form $form, $param)
    {   
        $form->add($this->target, EntityType::class, array(
            'label' => 'Secretaria de saúde',
            'class' => 'App:PessoaJuridica',
            'choice_value' => 'nuCnpj',
            'choice_label' => function($secretaria){
                return $secretaria->getPessoa()->getNoPessoa();
            },
            'query_builder' => function(EntityRepository $repo) use ($param) {
                return $repo->findSecretariasSaudeByCoMunicipioIbge($param);
            },
            'placeholder' => ''
        ));
    }
}
