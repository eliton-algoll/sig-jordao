<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class LoadFieldAbstractSubscriber implements EventSubscriberInterface, LoadFieldInterface
{
    /**
     * @var string 
     */
    protected $target;
    
    /**
     * @var string
     */
    protected $origin;
    
    /**
     * @param string $target name do campo que será populado
     * @param string $origin name do campo que dispara o carregamento do $target
     */
    public function __construct($target, $origin)
    {
        $this->target = $target;
        $this->origin = $origin;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
    
    /**
     * It can be used to:
     * 
     * Modify the data given during pre-population;
     * Modify a form depending on the pre-populated data (adding or removing fields dynamically).
     * 
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        if(is_null($data)) {
            throw new \InvalidArgumentException('Verifique o segundo parâmetro da função createForm em sua controller!');
        }
        $form = $event->getForm();
        
        $method = 'get' . ucfirst($this->origin);
        
        $param = ($data->$method()) ? $data->$method() : null;
        
        $this->addField($form, $param);
    }
    
    /**
     * It can be used to:
     *
     * Change data from the request, before submitting the data to the form;
     * Add or remove form fields, before submitting the data to the form.
     * 
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();        
     
        if(array_key_exists($this->origin, $data)) {
            $param = $data[$this->origin];
            $this->addField($form, $param);
        }
    }
}
