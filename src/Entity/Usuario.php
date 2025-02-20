<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="DBPETINFOSD.TB_USUARIO")
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario extends AbstractEntity implements UserInterface, \Serializable
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_USUARIO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_USUARIO_COSEQUSUARIO", allocationSize=1, initialValue=1)
     */
    private $coSeqUsuario;

    /**
     * @var PessoaFisica
     * 
     * @ORM\OneToOne(targetEntity="App\Entity\PessoaFisica", inversedBy="usuario")
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF")
     */
    private $pessoaFisica;
    
    /**
     * @var string
     *
     * @ORM\Column(name="DS_LOGIN", type="string", length=40, unique=true)
     */
    private $dsLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SENHA", type="string", length=64)
     */
    private $dsSenha;

    /**
     * 
     * @param PessoaFisica $pessoaFisica
     * @param string $dsLogin
     * @param string $dsSenha
     */
    public function __construct($pessoaFisica, $dsLogin, $dsSenha)
    {
        $this->pessoaFisica = $pessoaFisica;
        $this->dsLogin = $dsLogin;
        $this->dsSenha = $dsSenha;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Get coSeqUsuario
     *
     * @return int
     */
    public function getCoSeqUsuario()
    {
        return $this->coSeqUsuario;
    }
    
    /**
     * @param PessoaFisica
     */
    public function setPessoaFisica($pessoaFisica)
    {
        $this->pessoaFisica = $pessoaFisica;
        return $this;
    }
    
    /**
     * 
     */
    public function getPessoaFisica()
    {
        return $this->pessoaFisica;
    }

    /**
     * Set dsLogin
     *
     * @param string $dsLogin
     *
     * @return Usuario
     */
    public function setDsLogin($dsLogin)
    {
        $this->dsLogin = $dsLogin;

        return $this;
    }

    /**
     * Get dsLogin
     *
     * @return string
     */
    public function getDsLogin()
    {
        return $this->dsLogin;
    }

    /**
     * Set dsSenha
     *
     * @param string $dsSenha
     *
     * @return Usuario
     */
    public function setDsSenha($dsSenha)
    {
        $this->dsSenha = $dsSenha;

        return $this;
    }

    /**
     * Get dsSenha
     *
     * @return string
     */
    public function getDsSenha()
    {
        return $this->dsSenha;
    }

    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials(){}

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->getDsSenha();
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        $pessoasPerfis = $this->getPessoaFisica()->getPessoasPerfis();
        
        $roles = array('ROLE_USER');
        
        foreach ($pessoasPerfis as $pessoaPerfil) {
            $roles[] = $pessoaPerfil->getPerfil()->getRole();
        }
        
        return array_unique($roles);
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->getDsLogin();
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->coSeqUsuario,
                $this->dsLogin,
                $this->dsSenha
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->coSeqUsuario,
            $this->dsLogin,
            $this->dsSenha
        ) = unserialize($serialized);
    }
    
    /**
     * @param PessoaPerfil $pessoaPerfil
     * @param Projeto|null $projeto
     * @throws \DomainException
     */
    public function checkIdentidade(PessoaPerfil $pessoaPerfil, Projeto $projeto = null)
    {
        $hasAccess = false;
       
        foreach ($this->getPessoaFisica()->getPessoasPerfisAtivos() as $item) {
            if ($item->getCoSeqPessoaPerfil() == $pessoaPerfil->getCoSeqPessoaPerfil()) {
                if ($projeto) {
                    foreach ($item->getProjetosPessoasAtivos() as $item) {
                        if ($item->getProjeto()->getCoSeqProjeto() == $projeto->getCoSeqProjeto()) {
                            $hasAccess = true;
                        }
                    }
                } else {
                    $hasAccess = true;
                }
            }
        }
        
        if (!$hasAccess) {
            throw new \DomainException('Identidade inválida');
        }
    }
}