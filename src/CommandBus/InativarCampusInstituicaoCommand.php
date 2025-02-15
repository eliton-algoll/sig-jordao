<?php

namespace App\CommandBus;

use App\Entity\CampusInstituicao;
use App\Entity\Instituicao;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarCampusInstituicaoCommand
{
    /**
     *
     * @var CampusInstituicao
     */
    private $campusInstituicao;

    /**
     *
     * @param CampusInstituicao $campusInstituicao
     */
    public function __construct(CampusInstituicao $campusInstituicao)
    {
        $this->campusInstituicao = $campusInstituicao;
    }

    /**
     *
     * @return CampusInstituicao
     */
    public function getCampusInstituicao()
    {
        return $this->campusInstituicao;
    }

}
