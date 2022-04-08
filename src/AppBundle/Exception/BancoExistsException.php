<?php

namespace AppBundle\Exception;

final class BancoExistsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Já existe o banco cadastrado.');
    }
}
