<?php

namespace AppBundle\Exception;

final class UnexpectedCommandBehaviorException extends \Exception
{
    /**
     * 
     * @param mixed $message
     * @return \static
     */
    static public function onHandle($message)
    {
        if (is_array($message)) {
            $message = implode('\\n', $message);
        } elseif (is_object($message) && method_exists($message, '__toString')) {
            $message = $message->__toString();
        }
        
        return new static($message);
    }
}
