<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\Projeto;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CadastrarParticipanteCommand
{
    protected $projeto;

    /**
     * @var string
     * @Assert\NotBlank()
     * @AppAssert\Sei()
     */
    protected $nuSei;
    
    /**
     * @var array
     * @Assert\NotBlank()
     */
    protected $perfil;
    
    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $stVoluntarioProjeto;

    /**
     * @var string
     */
    protected $stAlunoRegular;

    /**
     * @var string
     */
    protected $coEixoAtuacao;

    /**
     * @var string
     */
    protected $stDeclaracaoCursoPenultimo;

    /**
     * @var string 
     * @Assert\NotBlank()
     */
    protected $nuCpf;

    /**
     * @var string
     * @Assert\Length(max = 50)
     * @Assert\Type(
     *     type="string"
     * )
     */
    protected $genero;
    
    /**
     * @var string
     * #@ A s s e r t \ T y p e (
     * #    t y p e = " n u m e r i c "
     * #)
     */
    protected $coBanco;
    
    /**
     * @var string
     * @Assert\Length(max = 6)
     */
    protected $coAgenciaBancaria;

    /**
     * @var string
     * @Assert\Length(max = 10)
     */
    protected $coConta;

    /**
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *      maxSize = "5M",
     *      maxSizeMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 05 (cinco) Megabytes. Selecione novo arquivo e refaça a operação.",
     *      mimeTypes = {"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 05 (cinco) Megabytes. Selecione novo arquivo e refaça a operação. "
     * )
     */
    private $noDocumentoBancario;


    /**
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *      maxSize = "5M",
     *      maxSizeMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 05 (cinco) Megabytes. Selecione novo arquivo e refaça a operação.",
     *      mimeTypes = {"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 05 (cinco) Megabytes. Selecione novo arquivo e refaça a operação. "
     * )
     */
    private $noDocumentoMatricula;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Length(max = 50)
     * @Assert\Type(
     *     type="string"
     * )
     */
    protected $noLogradouro;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Length(max = 7)
     * @Assert\Type(
     *     type="numeric"
     * )
     */
    protected $nuLogradouro;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Length(max = 160)
     * @Assert\Type(
     *     type="string"
     * )
     */
    protected $dsComplemento;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Length(max = 30)
     * @Assert\Type(
     *     type="string"
     * )
     */
    protected $noBairro;
    
    /**
     * @var integer 
     * @Assert\NotBlank()
     */
    protected $coUf;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     */
    protected $coMunicipioIbge;
    
    /**
     * @var integer
     */
    protected $categoriaProfissional;
    
    /**
     * @var string
     * @Assert\Length(min = 1, max = 7)
     * @Assert\Type(
     *     type="numeric"
     * )
     */
    protected $coCnes;
    
    /**
     * @var integer
     */
    protected $titulacao;
    
    /**
     * @var object
     */
    protected $cursoGraduacao;
    
    /**
     * @var integer
     * @Assert\Length(min = 4, max = 4)     
     */
    protected $nuAnoIngresso;
    
    /**
     * @var string
     * @Assert\Length(max = 30)
     */
    protected $nuMatriculaIES;
    
    /**
     * @var integer
     * @Assert\Regex("/\d{1,2}?/")     
     */
    protected $nuSemestreAtual;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $dsEnderecoWeb;
    
    /**
     * @var string 
     * @Assert\NotBlank()
     * @Assert\Length(max = 8)
     */
    protected $coCep;
    
    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(min = 1)
     * @Assert\Type(
     *     type="array"
     * )
     */
    protected $telefones;
    
    /**
     * @var array
     */
    protected $cursosLecionados;
    
    /**
     * @var array
     */
    protected $areaTematica;

    /**
     * @var GrupoAtuacao|null
     */
    protected $grupoTutorial;

    /**
     * CadastrarParticipanteCommand constructor.
     */
    public function __construct()
    {
        $this->stVoluntarioProjeto = 'N';
    }

    /**
     * @param Projeto $projeto
     * @return CadastrarParticipanteCommand
     */
    public function setProjeto(Projeto $projeto = null)
    {
       $this->projeto = $projeto;
       return $this;
    }

    /**
     * @param string $nuSei
     */
    public function setNuSei($nuSei)
    {
        $this->nuSei = $nuSei;
    }
    
    /**
     * @param array $perfil
     * @return CadastrarParticipanteCommand
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
        return $this;
    }
    
    /**
     * @param string $stVoluntarioProjeto
     * @return CadastrarParticipanteCommand
     */
    public function setStVoluntarioProjeto($stVoluntarioProjeto)
    {
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;
        return $this;
    }

    /**
     * @param string $stAlunoRegular
     * @return CadastrarParticipanteCommand
     */
    public function setStAlunoRegular($stAlunoRegular)
    {
        $this->stAlunoRegular = $stAlunoRegular;
        return $this;
    }

    /**
     * @param string $coEixoAtuacao
     * @return CadastrarParticipanteCommand
     */
    public function setCoEixoAtuacao($coEixoAtuacao)
    {
        $this->coEixoAtuacao = $coEixoAtuacao;
        return $this;
    }

    /**
     * @param string $stDeclaracaoCursoPenultimo
     * @return CadastrarParticipanteCommand
     */
    public function setStDeclaracaoCursoPenultimo($stDeclaracaoCursoPenultimo)
    {
        $this->stDeclaracaoCursoPenultimo = $stDeclaracaoCursoPenultimo;
        return $this;
    }

    /**
     * @param string $nuCpf
     * @return CadastrarParticipanteCommand
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
        return $this;
    }

    /**
     * @param $genero
     * @return CadastrarParticipanteCommand
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
        return $this;
    }


    /**
     * @param $coBanco
     * @return CadastrarParticipanteCommand
     */
    public function setCoBanco($coBanco)
    {
        $this->coBanco = $coBanco;
        return $this;
    }

    /**
     * @param $coAgenciaBancaria
     * @return CadastrarParticipanteCommand
     */
    public function setCoAgenciaBancaria($coAgenciaBancaria)
    {
        $this->coAgenciaBancaria = $coAgenciaBancaria;
        return $this;
    }

    /**
     * @param $coConta
     * @return CadastrarParticipanteCommand
     */
    public function setCoConta($coConta)
    {
        $this->coConta = $coConta;
        return $this;
    }

    /**
     * @param string $noLogradouro
     * @return CadastrarParticipanteCommand
     */
    public function setNoLogradouro($noLogradouro)
    {
        $this->noLogradouro = $noLogradouro;
        return $this;
    }
    
    /**
     * @param integer $nuLogradouro
     * @return CadastrarParticipanteCommand
     */
    public function setNuLogradouro($nuLogradouro)
    {
        $this->nuLogradouro = $nuLogradouro;
        return $this;
    }

    /**
     * @param string $dsComplemento
     * @return CadastrarParticipanteCommand
     */
    public function setDsComplemento($dsComplemento)
    {
        $this->dsComplemento = $dsComplemento;
        return $this;
    }

    /**
     * @param string $noBairro
     * @return CadastrarParticipanteCommand
     */
    public function setNoBairro($noBairro)
    {
        $this->noBairro = $noBairro;
        return $this;
    }

    /**
     * @param integer $coUf
     * @return CadastrarParticipanteCommand
     */
    public function setCoUf($coUf)
    {
        $this->coUf = $coUf;
        return $this;
    }

    /**
     * @param string $coMunicipioIbge
     * @return CadastrarParticipanteCommand
     */
    public function setCoMunicipioIbge($coMunicipioIbge)
    {
        $this->coMunicipioIbge = $coMunicipioIbge;
        return $this;
    }

    /**
     * @param integer|null $categoriaProfissional
     * @return CadastrarParticipanteCommand
     */
    public function setCategoriaProfissional($categoriaProfissional)
    {
        $this->categoriaProfissional = $categoriaProfissional;
        return $this;
    }
    
    /**
     * 
     * @param string $coCnes
     * @return CadastrarParticipanteCommand
     */
    public function setCoCnes($coCnes)
    {
        $this->coCnes = $coCnes;
        return $this;
    }

    /**
     * @param integer $titulacao
     * @return CadastrarParticipanteCommand
     */
    public function setTitulacao($titulacao)
    {
        $this->titulacao = $titulacao;
        return $this;
    }

    /**
     * @param integer $cursoGraduacao
     * @return CadastrarParticipanteCommand
     */
    public function setCursoGraduacao($cursoGraduacao)
    {
        $this->cursoGraduacao = $cursoGraduacao;
        return $this;
    }

    /**
     * @param integer $nuAnoIngresso
     * @return CadastrarParticipanteCommand
     */
    public function setNuAnoIngresso($nuAnoIngresso)
    {
        $this->nuAnoIngresso = $nuAnoIngresso;
        return $this;
    }

    /**
     * @param string $nuMatriculaIES
     * @return CadastrarParticipanteCommand
     */
    public function setNuMatriculaIES($nuMatriculaIES)
    {
        $this->nuMatriculaIES = $nuMatriculaIES;
        return $this;
    }

    /**
     * @param integer $nuSemestreAtual
     * @return CadastrarParticipanteCommand
     */
    public function setNuSemestreAtual($nuSemestreAtual)
    {
        $this->nuSemestreAtual = $nuSemestreAtual;
        return $this;
    }
    
    /**
     * @param string $dsEnderecoWeb
     * @return CadastrarParticipanteCommand
     */
    public function setDsEnderecoWeb($dsEnderecoWeb)
    {
        $this->dsEnderecoWeb = $dsEnderecoWeb;
        return $this;
    }
    
    /**
     * @param string $coCep
     * @return CadastrarParticipanteCommand
     */
    public function setCoCep($coCep)
    {
        $this->coCep = $coCep;
        return $this;
    }

    
    /**
     * @param array $telefones
     * @return CadastrarParticipanteCommand
     */
    public function setTelefones($telefones)
    {
        $this->telefones = $telefones;
        return $this;
    }
    
    /**
     * @param array $cursosLecionados
     * @return CadastrarParticipanteCommand
     */
    public function setCursosLecionados($cursosLecionados)
    {
        $this->cursosLecionados = $cursosLecionados;
        return $this;
    }
    
    /**
     * @param array $areaTematica
     * @return CadastrarParticipanteCommand
     */
    public function setAreaTematica($areaTematica)
    {
        $this->areaTematica = $areaTematica;
        return $this;
    }

    /**
     * @param int $grupoTutorial
     * @return CadastrarParticipanteCommand
     */
    public function setGrupoTutorial($grupoTutorial)
    {
        $this->grupoTutorial = $grupoTutorial;
        return $this;
    }
    
    /**
     * @return Projeto|null
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * @return string
     */
    public function getNuSei()
    {
        return $this->nuSei;
    }
    
    /**
     * @return array
     */
    public function getPerfil()
    {
        return $this->perfil;
    }
    
    /**
     * @return string
     */
    public function getStVoluntarioProjeto()
    {
        return $this->stVoluntarioProjeto;
    }

    /**
     * @return string
     */
    public function getStAlunoRegular()
    {
        return $this->stAlunoRegular;
    }

    /**
     * @return string
     */
    public function getCoEixoAtuacao()
    {
        return $this->coEixoAtuacao;
    }

    /**
     * @return string
     */
    public function getStDeclaracaoCursoPenultimo()
    {
        return $this->stDeclaracaoCursoPenultimo;
    }

    /**
     * @return string
     */
    public function getNuCpf()
    {
        return preg_replace("/[^0-9]/", '', $this->nuCpf);
    }

    /**
     * @return string
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * @return integer
     */
    public function getCoBanco()
    {
        return $this->coBanco;
    }

    /**
     * @return integer
     */
    public function getCoAgenciaBancaria()
    {
        return $this->coAgenciaBancaria;
    }

    /**
     * @return integer
     */
    public function getCoConta()
    {
        return $this->coConta;
    }

    /**
     * @return string
     */
    public function getNoLogradouro()
    {
        return $this->noLogradouro;
    }

    /**
     * @return integer
     */
    public function getNuLogradouro()
    {
        return $this->nuLogradouro;
    }

    /**
     * @return string
     */
    public function getDsComplemento()
    {
        return $this->dsComplemento;
    }

    /**
     * @return string
     */
    public function getNoBairro()
    {
        return $this->noBairro;
    }

    public function getCoUf()
    {
        return $this->coUf;
    }

    /**
     * @return string
     */
    public function getCoMunicipioIbge()
    {
        return $this->coMunicipioIbge;
    }

    /**
     * @return integer
     */
    public function getCategoriaProfissional()
    {
        return $this->categoriaProfissional;
    }
    
    /**
     * @return string
     */
    public function getCoCnes()
    {
        return $this->coCnes;
    }

    /**
     * @return integer
     */
    public function getTitulacao()
    {
        return $this->titulacao;
    }

    /**
     * @return integer
     */
    public function getCursoGraduacao()
    {
        return $this->cursoGraduacao;
    }

    /**
     * @return integer
     */
    public function getNuAnoIngresso()
    {
        return $this->nuAnoIngresso;
    }

    /**
     * @return string
     */
    public function getNuMatriculaIES()
    {
        return $this->nuMatriculaIES;
    }

    /**
     * @return integer
     */
    public function getNuSemestreAtual()
    {
        return $this->nuSemestreAtual;
    }
    
    /**
     * @return string
     */
    public function getDsEnderecoWeb()
    {
        return $this->dsEnderecoWeb;
    }
    
    /**
     * @return string
     */
    public function getCoCep()
    {
        return $this->coCep;
    }

    /**
     * @return array
     */
    public function getTelefones()
    {
        return $this->telefones;
    }

    /**
     * @return array
     */
    public function getCursosLecionados()
    {
        return $this->cursosLecionados;
    }

    /**
     * @return array
     */
    public function getAreaTematica()
    {
        return $this->areaTematica;
    }

    /**
     *
     * @return UploadedFile
     */
    public function getNoDocumentoBancario()
    {
        return $this->noDocumentoBancario;
    }

    /**
     *
     * @param UploadedFile $noDocumentoBancario
     */
    public function setNoDocumentoBancario(UploadedFile $noDocumentoBancario = null)
    {
        $this->noDocumentoBancario = $noDocumentoBancario;
    }
    /**
     *
     * @return UploadedFile
     */
    public function getNoDocumentoMatricula()
    {
        return $this->noDocumentoMatricula;
    }

    /**
     *
     * @param UploadedFile $noDocumentoMatricula
     */
    public function setNoDocumentoMatricula(UploadedFile $noDocumentoMatricula = null)
    {
        $this->noDocumentoMatricula = $noDocumentoMatricula;
    }

    /**
     * @return int
     */
    public function getGrupoTutorial()
    {
        return $this->grupoTutorial;
    }

    /**
     * @return bool
     */
    public function isVoluntario()
    {
        return 'S' == $this->stVoluntarioProjeto;
    }

    /**
     * @return bool
     */
    public function isProjetoGrupoTutorial()
    {
        return ($this->projeto instanceof Projeto) ? $this->projeto->getPublicacao()->getPrograma()->isGrupoTutorial() : false;
    }
    
    /**
     * @Assert\Callback
     * 
     * @param ExecutionContextInterface $context
     */
    public function validateCategoriaProfissional(ExecutionContextInterface $context)
    {
        $perfil = ($this->getPerfil() instanceof Perfil) ? $this->getPerfil()->getCoSeqPerfil() : $this->getPerfil();

        $hasError = function ($perfil, $categorias) {
            return !in_array($perfil, [Perfil::PERFIL_ESTUDANTE, Perfil::PERFIL_ORIENTADOR_MEDIO, Perfil::PERFIL_COORDENADOR_PROJETO]) && !$categorias;
        };
        
        if (
            ($hasError($perfil, $this->categoriaProfissional) && !$this->isProjetoGrupoTutorial()) ||
            ($hasError($perfil, $this->categoriaProfissional) && $this->isProjetoGrupoTutorial() && !$this->isVoluntario())
        ) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('categoriaProfissional')
                ->addViolation();
        }
    }
    
    /**
     * @Assert\Callback
     * 
     * @param ExecutionContextInterface $context
     */
    public function validateCursosLecionados(ExecutionContextInterface $context)
    {
        $perfil = ($this->getPerfil() instanceof Perfil) ? $this->perfil->getCoSeqPerfil() : $this->getPerfil();
        
        if (
            in_array($perfil, [Perfil::PERFIL_COORDENADOR_GRUPO, Perfil::PERFIL_TUTOR]) &&
            (!$this->cursosLecionados) &&
            !$this->isProjetoGrupoTutorial()
        ) {            
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('cursosLecionados')
                ->addViolation();            
        }        
    }

    /**
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function validateGrupoAtuacao(ExecutionContextInterface $context)
    {
        $perfil = ($this->getPerfil() instanceof Perfil) ? $this->getPerfil()->getCoSeqPerfil() : $this->getPerfil();

        $hasError = function ($perfil, $areaTematica) {
            return !in_array($perfil, [Perfil::PERFIL_COORDENADOR_PROJETO]) && !$areaTematica;
        };

        /*
        if (
            ($hasError($perfil, $this->areaTematica) && !$this->isProjetoGrupoTutorial()) ||
            ($hasError($perfil, $this->areaTematica) && $this->isProjetoGrupoTutorial() && !$this->isVoluntario())
        ) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('areaTematica')
                ->addViolation();
        }
        */
    }

    /**
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function validateGrupoTutorial(ExecutionContextInterface $context)
    {
        $perfil = ($this->getPerfil() instanceof Perfil) ? $this->getPerfil()->getCoSeqPerfil() : $this->getPerfil();

        if (
            $this->projeto instanceof Projeto &&
            $this->projeto->getPublicacao()->getPrograma()->isGrupoTutorial() &&
            !$this->grupoTutorial &&
            !in_array($perfil, [Perfil::PERFIL_COORDENADOR_PROJETO, Perfil::PERFIL_ORIENTADOR_SUPERIOR, Perfil::PERFIL_ORIENTADOR_MEDIO])
        ) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('grupoTutorial')
                ->addViolation();
        }
    }
}
