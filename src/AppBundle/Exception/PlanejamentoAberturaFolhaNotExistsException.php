<?php

namespace AppBundle\Exception;

final class PlanejamentoAberturaFolhaNotExistsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Planejamento de Abertura de folha não existe.');
    }
}
