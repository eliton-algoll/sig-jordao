<?php

namespace AppBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait DataInclusaoTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_INCLUSAO", type="datetime", nullable=false)
     * @Assert\Type("\DateTime")
     */
    private $dtInclusao;
    
    /**
     * @param \DateTime $dtInclusao
     * @return $this
     */
    public function setDtInclusao(\DateTime $dtInclusao)
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getDtInclusao()
    {
        return $this->dtInclusao;
    }
}
