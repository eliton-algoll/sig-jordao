<?php

namespace App\Twig;

use Twig\Extension\GlobalsInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\ORM\EntityManager;
use App\Entity\SituacaoFolha;
use App\Entity\Publicacao;
use App\Entity\FolhaPagamento;
use App\Cpb\DicionarioCpb;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    use \App\Traits\MaskTrait;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private static $arrMeses = [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro',
    ];

    /**
     * @param Router $router
     */
    public function __construct(Router $router, TokenStorageInterface $token, EntityManager $em)
    {
        $this->router = $router;
        $this->tokenStorage = $token;
        $this->em = $em;
    }


    public function getGlobals(): array
    {
        return [
            'globals_pper' => $this->getPessoaPerfilAutenticado(),
        ];
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('tpTelefone', array($this, 'tipoTelefoneFilter')),
            new TwigFilter('strst', array($this, 'strst'), array('is_safe' => array('html'))),
            new TwigFilter('strstparecer', array($this, 'strstparecer'), array('is_safe' => array('html'))),
            new TwigFilter('undefined', array($this, 'undefined'), array('is_safe' => array('html'))),
            new TwigFilter('cpf', array($this, 'cpf')),
            new TwigFilter('strmes', array($this, 'strmes')),
            new TwigFilter('strtipofolha', array($this, 'strtipofolha')),
            new TwigFilter('int', array($this, 'toInt')),
            new TwigFilter('float', array($this, 'toFloat')),
            new TwigFilter('publicacao', array($this, 'tpPublicacao')),
            new TwigFilter('sitcmd', array($this, 'descricaoSitCmd')),
            new TwigFilter('resume', array($this, 'resume')),
            new TwigFilter('replaceTokenModeloCertificado', array($this, 'replaceTokenModeloCertificado'))
        );
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('showFolhaPagamentoButtons', array($this, 'showFolhaPagamentoButtons')),
            new TwigFunction('ascDesc', array($this, 'ascDesc')),
        );
    }

    /**
     * @return PessoaPerfil
     */
    private function getPessoaPerfilAutenticado()
    {
        $pessoaPerfil = null;
        $token = $this->tokenStorage->getToken();
        if ($token instanceof UsernamePasswordToken && $token->hasAttribute('coPessoaPerfil')) {
            $id = $token->getAttribute('coPessoaPerfil');
            if ($id) {
                $pessoaPerfil = $this->em->find('App:PessoaPerfil', $id);
            }
        }
        return $pessoaPerfil;
    }

    /**
     * @param FolhaPagamento $folha
     * @return string
     */
    public function showFolhaPagamentoButtons(FolhaPagamento $folha)
    {
        $id = $folha->getCoSeqFolhaPagamento();
        
        $actions = array();
        
        $homologarUrl = $this->router->generate('folha_pagamento_homologar', array('folha' => $id));
        
        $downloadXlsRoute = $this->router->generate('folha_pagamento_download_xls', array('folha' => $id));        
        $downloadXls = "&nbsp;<a href='$downloadXlsRoute' target='_blank' title='Imprimir folha de pagamento homologada'><span class='glyphicon glyphicon-list-alt'></span></a>&nbsp;";               
        $actions[] = "&nbsp;<a href='javascript:void(0)' class='historico-tramitacao-status' data-folha='$id' title='Histórico do status da folha'><span class='glyphicon glyphicon-time'></span></a>&nbsp;";
        $map = array(
            SituacaoFolha::ABERTA => "&nbsp;<a class='fechar-folha' href='javascript:;' data-folha='$id' title='Fechar Folha de Pagamento'><span class='glyphicon glyphicon-minus-sign'></span></a>&nbsp;",
            SituacaoFolha::FECHADA => "&nbsp;<a href='$homologarUrl' title='Homologar Folha de Pagamento'><span class='glyphicon glyphicon-check'></span></a>&nbsp;",
            SituacaoFolha::HOMOLOGADA => $downloadXls,
            SituacaoFolha::HOMOLOGADA => $downloadXls . "&nbsp;<a class='enviar-folha-para-fundo' href='javascript:;' data-folha='$id' title='Enviar Folha de Pagamento para Fundo Nacional de Saúde'><span class='glyphicon glyphicon-send'></span></a>&nbsp;",
            SituacaoFolha::ENVIADA_FUNDO => $downloadXls . "&nbsp;<a class='informar-ordem-bancaria' href='javascript:;' data-folha='$id' title='Informar ordem bancária'><span class='glyphicon glyphicon-transfer'></span></a>&nbsp;",
            SituacaoFolha::ORDEM_BANCARIA_EMITIDA => $downloadXls,
            SituacaoFolha::CANCELADA => null,
        );
        
        $actions[] = $map[$folha->getSituacao()->getCoSeqSituacaoFolha()];
        
        if (!$folha->isMensal()) {
            if ($folha->isAberta()) {
                $actions[] = "<a href='". $this->router->generate('folha_pgto_suplementar_edit', array('folhaPagamento' => $id)) ."' class='glyphicon glyphicon-pencil' title='Editar Folha de Pagamento Complementar'></a>&nbsp;";
            }
            if ($folha->isAberta() || $folha->isFechada() || $folha->isHomologada()) {
                $actions[] = "<a style='cursor: pointer;' class='glyphicon glyphicon-ban-circle btn-cancelar-folha-suplementar' folha-pagamento='". $id ."' title='Cancelar Folha de Pagamento Complementar.'></a>&nbsp;";
            }
        }
        
        return implode('&nbsp;', $actions);
    }

    /**
     *
     * @param array $params
     * @return string
     */
    public function ascDesc(array $params)
    {
        $sort = 'ASC';
        if (isset($params['sort']) && !empty($params['sort'])) {
            if ($params['sort'] == 'ASC') {
                $sort = 'DESC';
            } else {
                $sort = 'ASC';
            }
        }

        return $sort;
    }

    /**
     * @param string $tpTelefone
     * @return string
     */
    public function tipoTelefoneFilter($tpTelefone)
    {
        return \App\Entity\Telefone::getDsTelefone($tpTelefone);
    }

    /**
     * strst Retorna a string correspondente ao status
     *
     * @param string $stRegistroAtivo
     * @param boolean $span
     * @return string|html
     */
    public function strst($stRegistroAtivo, $span = true)
    {
        $str = 'undefined';
        if ($stRegistroAtivo == 'S') {
            $str = 'Sim';
            $class = 'text-success';
        }
        if ($stRegistroAtivo == 'N') {
            $str = 'Não';
            $class = 'text-danger';
        }
        if ($span) {
            $str = "<span class='$class'><b>$str</b></span>";
        }

        return $str;
    }

    /**
     * strstparecer Retorna a string correspondente ao parecer
     *
     * @param string $stParecer
     * @param boolean $span
     * @return string|html
     */
    public function strstparecer($stParecer, $span = true)
    {
        $str = 'Aguardando parecer';
        if ($stParecer == 'S') {
            $str = 'Deferida';
            $class = 'text-success';
        }
        if ($stParecer == 'N') {
            $str = 'Indeferida';
            $class = 'text-danger';
        }
        if ($span) {
            $str = "<span class='$class'><b>$str</b></span>";
        }

        return $str;
    }

    /**
     * @param string $cpf
     * @return string
     */
    public function cpf($cpf)
    {
        if (empty($cpf)) {
            return 'Não disponível';
        }
        return $this->maskcpf($cpf);
    }

    /**
     * @param integer $nuMes
     * @return string
     */
    public function strmes($nuMes)
    {
        $key = abs($nuMes) - 1;
        $strmes = ' - ';
        if (isset(self::$arrMeses[$key])) {
            $strmes = self::$arrMeses[$key];
        }

        return $strmes;
    }

    /**
     * @param type $inicial
     */
    public function strtipofolha($inicial)
    {
        if ($inicial == 'M') {
            $tipoFolha = 'Mensal';
        }
        if ($inicial == 'S') {
            $tipoFolha = 'Suplementar';
        }
        return $tipoFolha;
    }

    /**
     * @param string $str
     * @return string
     */
    public function undefined($str)
    {
        if (empty($str)) {
            return 'não informado';
        }
        return $str;
    }

    /**
     *
     * @param string $str
     * @return integer
     */
    public function toInt($str)
    {
        return (int)$str;
    }

    /**
     *
     * @param string $str
     * @param integer $precision
     * @return float
     */
    public function toFloat($str, $precision = 2)
    {
        return (float)($precision > 0) ? substr($str, 0, -$precision) : $str;
    }

    /**
     *
     * @param string $tpPublicacao
     * @return string
     */
    public function tpPublicacao($tpPublicacao)
    {
        return Publicacao::getDescricaoTipoByTpPublicacao($tpPublicacao);
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public function descricaoSitCmd($value)
    {
        $descricao = DicionarioCpb::getValueDescricao('SIT-CMD', $value);

        if ($descricao) {
            return current(array_keys($descricao));
        }

        return null;
    }

    /**
     * @param $value
     * @param int $length
     * @return string
     */
    public function resume($value, $length = 100)
    {
        $val = [
            "<span title=\"${value}\">",
            substr($value, 0, $length),
            "...",
            "</span>",
        ];

        return (strlen($value) > $length) ? implode('', $val) : $value;
    }

    public function replaceTokenModeloCertificado($value, $projetoPessoa, $qtCargaHoraria) {
        return str_replace([
            '<NOME PARTICIPANTE>',
            '<PERFIL PARTICIPANTE>',
            '<DATA INÍCIO PARTICIPAÇÃO>',
            '<DATA TÉRMINO PARTICIPAÇÃO>',
            '<CARGA HORÁRIA CUMPRIDA>'
        ], [
            $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa(),
            $projetoPessoa->getPessoaPerfil()->getPerfil()->getDsPerfil(),
            $projetoPessoa->getDtInclusao()->format('d/m/Y'),
            $projetoPessoa->isAtivo() ?
                $projetoPessoa->getProjeto()->getPublicacao()->getDtFimVigencia()->format('d/m/Y') :
                $projetoPessoa->getDtDesligamento()->format('d/m/Y'),
            $qtCargaHoraria
        ], $value);

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
