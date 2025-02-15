<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Perfil
 *
 * @ORM\Table(name="DBPET.TB_PERFIL")
 * @ORM\Entity(repositoryClass="App\Repository\PerfilRepository")
 */
class Perfil extends AbstractEntity implements RoleInterface
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;     
    
    const ROLE_ADMINISTRADOR = 'ADMINISTRADOR';
    const ROLE_COORDENADOR_PROJETO = 'COORDENADOR_PROJETO';
    const ROLE_COORDENADOR_GRUPO = 'COORDENADOR_GRUPO';
    const ROLE_PRECEPTOR = 'PRECEPTOR';
    const ROLE_TUTOR = 'TUTOR';
    const ROLE_ESTUDANTE = 'ESTUDANTE';

    const ROLE_ORIENTADOR = 'ORIENTADOR';

    const PERFIL_ADMINISTRADOR = 1;
    const PERFIL_COORDENADOR_PROJETO = 2;
    const PERFIL_COORDENADOR_GRUPO = 3;
    const PERFIL_PRECEPTOR = 4;
    const PERFIL_TUTOR = 5;
    const PERFIL_ESTUDANTE = 6;
    const PERFIL_ORIENTADOR_SUPERIOR = 7;
    const PERFIL_ORIENTADOR_MEDIO = 8;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PERFIL", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PERFIL_COSEQPERFIL", allocationSize=1, initialValue=1)
     */
    private $coSeqPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_PERFIL", type="string", length=30)
     */
    private $dsPerfil;
    
    /**
     * @var string 
     * 
     * @ORM\Column(name="NO_ROLE", type="string")
     */
    private $noRole;

    /**
     * @param string $dsPerfil
     */
    public function __construct($dsPerfil)
    {
        $this->dsPerfil = $dsPerfil;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Get coSeqPerfil
     *
     * @return int
     */
    public function getCoSeqPerfil()
    {
        return $this->coSeqPerfil;
    }

    /**
     * Get dsPerfil
     *
     * @return string
     */
    public function getDsPerfil()
    {
        return $this->dsPerfil;
    }

    /**
     * @return string
     */
    public function getNoRole()
    {
        return $this->noRole;
    }
    
    /**
     * @return string
     */
    public function getRole() {
        return $this->getNoRole();
    }
    
    /**
     * @return boolean
     */
    public function isAdministrador()
    {
        return $this->getNoRole() === self::ROLE_ADMINISTRADOR;
    }
    
    /**
     * @return boolean
     */
    public function isEstudante()
    {
        return $this->getNoRole() === self::ROLE_ESTUDANTE;
    }
    
    /**
     * @return boolean
     */
    public function isTutor()
    {
        return $this->getNoRole() === self::ROLE_TUTOR;
    }
    
    /**
     * @return boolean
     */
    public function isPreceptor()
    {
        return $this->getNoRole() === self::ROLE_PRECEPTOR;
    }
    
    /**
     * @return boolean
     */
    public function isCoordenadorGrupo()
    {
        return $this->getNoRole() === self::ROLE_COORDENADOR_GRUPO;
    }
    
    /**
     * @return boolean
     */
    public function isCoordenadorProjeto()
    {
        return $this->getNoRole() === self::ROLE_COORDENADOR_PROJETO;
    }

    /**
     * @return boolean
     */
    public function isOrientadorProjeto()
    {
        return $this->getNoRole() === self::ROLE_ORIENTADOR;
    }
}