<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;

class FormatoRelatorioType extends AbstractType
{
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Formato do relatório',
            'choices' => [                
                'Excel' => 'xls',
                'HTML' => 'html',
                'PDF' => 'pdf',
            ],
            'data' => 'pdf',
        ]);
    }
    
    public function getParent()
    {
        return ChoiceType::class;
    }
}
