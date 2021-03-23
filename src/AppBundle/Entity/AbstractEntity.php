<?php

namespace AppBundle\Entity;

abstract class AbstractEntity
{
    /**
     * 
     * @return array
     */
    public function toArray($parent = null)
    {
        $methods = get_class_methods(get_class($this));
        $array = [];
        
        foreach ($methods as $method) {
            if ('get' === substr($method, 0, 3)) {
                
                $reflect = new \ReflectionMethod($this, $method);
                $className = $reflect->getDeclaringClass()->getName();
                
                if (preg_match('/Proxie/', $className)) {
                    $reflect = new \ReflectionMethod(str_replace('Proxies\__CG__\\', '', $className), $method);
                }
                
                if (!empty($reflect->getParameters()) || 
                    preg_match('/ArrayCollection/', $reflect->getDocComment())
                ) {
                    continue;
                }
                
                $value = $this->$method();
                
                if ($value instanceof \DateTime) {
                    $value = $value->format('d/m/Y');
                }
                if ($value instanceof AbstractEntity && $value != $parent) {                    
                    $array[lcfirst(substr($method, 3))] = $value->toArray($this);
                } elseif (!is_object($value)) {
                    $array[lcfirst(substr($method, 3))] = $value;
                }
            }            
        }        
        
        return $array;
    }
}
