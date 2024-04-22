<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\ConfirmarGrupoTutorialCommand;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\ProjetoPessoaCursoGraduacao;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Security("is_granted(['COORDENADOR_PROJETO', 'ROLE_PREVIOUS_ADMIN'])")
 */
final class ConfirmarGrupoTutorialController extends ControllerAbstract
{

    /**
     * @Route("confirmar-grupo-tutorial", name="confirmar_grupo_tutorial")
     */
    public function indexAction()
    {
        $projeto = $this->getProjetoAutenticado();

        return $this->render('confirmar_grupo_atuacao/index.html.twig', compact('projeto'));
    }

    /**
     * @Route(
     *     "confirmar-grupo-tutorial/grid/{grupoAtuacao}",
     *     name="confirmar_grupo_tutorial_grid",
     *     options={"expose"=true}
     * )
     * @Security("is_granted('IS_GRUPO_BELONGS_PROJETO', grupoAtuacao)")
     *
     * @param GrupoAtuacao $grupoAtuacao
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gridGrupoAction(GrupoAtuacao $grupoAtuacao)
    {
        $projetosPessaGrupoAtuacao = $this->get('app.projeto_pessoa_grupo_atuacao_repository')
            ->findByGrupoAtuacao($grupoAtuacao);

        return $this->render('confirmar_grupo_atuacao/grid-grupo-tutorial.html.twig',
            compact('projetosPessaGrupoAtuacao', 'grupoAtuacao')
        );
    }

    /**
     * @Route(
     *     "confirmar-grupo-tutorial/validate/{grupoAtuacao}",
     *     name="confirmar_grupo_tutorial_validade",
     *     options={"expose"=true}
     * )
     * @Security("is_granted('IS_GRUPO_BELONGS_PROJETO', grupoAtuacao)")
     *
     * @param GrupoAtuacao $grupoAtuacao
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function validateGrupoAction(GrupoAtuacao $grupoAtuacao)
    {
        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);

        $validator = $this->get('validator');
        $errors = $validator->validate($command);

        $response = new \stdClass();
        $response->status = ($errors->count() === 0) ? true : false;
        $response->errors = [];

        if (0 !== $errors->count()) {
            foreach ($errors as $error) {
                $response->errors[] = $error->getMessage();
            }
        }

        return new JsonResponse($response);
    }

//    /**
//     * @Route(
//     *     "confirmar-grupo-tutorial/confirmar/{grupoAtuacao}",
//     *     name="confirmar_grupo_tutorial_confirmar",
//     *     options={"expose"=true}
//     * )
//     *
//     * @param GrupoAtuacao $grupoAtuacao
//     * @return RedirectResponse
//     */
//    public function confirmarGrupoAction(GrupoAtuacao $grupoAtuacao)
//    {
//        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);
//
//        try {
//            $this->getBus()->handle($command);
//            $this->addFlash('success',
//                sprintf('O grupo %s foi confirmado com sucesso.', $grupoAtuacao->getNoGrupoAtuacao()));
//        } catch (InvalidCommandException $e) {
//            $erros = [];
//            if (method_exists($e, 'getViolations')) {
//                foreach ($e->getViolations() as $violation) {
//                    $erros[] = $violation->getMessage();
//                }
//            }
//
//            $this->addFlash('danger', implode('\\n', $erros));
//        }
//
//        return $this->redirectToRoute('confirmar_grupo_tutorial');
//    }

//    /**
//     * @Route(
//     *     "confirmar-grupo-tutorial/confirmar",
//     *     name="confirmar_grupo_tutorial_confirmar",
//     *     options={"expose"=true}
//     * )
//     *
//     * @param GrupoAtuacao $grupoAtuacao
//     * @return RedirectResponse
//     */
// RedirectResponse
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Security("is_granted('COORDENADOR_PROJETO')")
     * @Route("confirmar-grupo-tutorial/confirmar", name="confirmar_grupo_tutorial_confirmar", options={"expose"=true})
     * @Method({"POST"})
     */
    public function confirmarGruposAction(Request $request)
    {
        // Cria o objeto
        $response = new \stdClass();
        $response->status = true;

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        try {
            // Precisa receber 1 ou mais grupos
            $payload = $request->request->get('payload', []);

            // Extrai os objetos do banco de dados
            $gruposAtuacao = [];

            // Salva os temas abordados enviados
            foreach ($payload as $grupoTutorialPayload) {
                $items = $em->getRepository(GrupoAtuacao::class)->findById($grupoTutorialPayload['id']);

                foreach($items as $grupoAtuacao) {
                    // Converte para número inteiro o tema enviado
                    array_walk($grupoTutorialPayload['temasAbordados'], function(&$id) {
                        $id = (int)$id;
                    });

                    // Converte o vetor no formato JSON
                    $grupoAtuacao->setDsTemaAbordado(json_encode($grupoTutorialPayload['temasAbordados']));

                    // Persiste na base de dados
                    $em->persist($grupoAtuacao);
                    $em->flush();

                    // Salva para uso futuro
                    $gruposAtuacao[] = $grupoAtuacao;
                }
            }

            // Validação das regras de negócio
            $errors = [];

            $sqlTotalParticipante = <<<SQL
SELECT
    COUNT(*) AS total
FROM
    dbpet.tb_projeto_pessoa t0_
    LEFT JOIN dbpet.tb_projeto t2_ ON t0_.co_projeto = t2_.co_seq_projeto
    LEFT JOIN dbpet.tb_pessoa_perfil t5_ ON t0_.co_pessoa_perfil = t5_.co_seq_pessoa_perfil
    LEFT JOIN dbpet.tb_perfil t3_ ON t5_.co_perfil = t3_.co_seq_perfil
    LEFT JOIN dbpessoa.tb_pessoa_fisica t6_ ON t5_.nu_cpf = t6_.nu_cpf
    LEFT JOIN dbpessoa.tb_pessoa t1_ ON t6_.nu_cpf = t1_.nu_cpf_cnpj_pessoa
    LEFT JOIN dbpet.tb_dado_pessoal t7_ ON t6_.nu_cpf = t7_.nu_cpf
    LEFT JOIN dbpet.tb_publicacao t8_ ON t2_.co_publicacao = t8_.co_seq_publicacao
    LEFT JOIN dbpet.tb_programa t9_ ON t8_.co_programa = t9_.co_seq_programa
    LEFT JOIN dbpet.rl_projetopessoa_grupoatuacao r10_ ON (t0_.co_seq_projeto_pessoa = r10_.co_projeto_pessoa) AND ((t9_.tp_programa = 2) AND (r10_.st_registro_ativo = 'S'))
    LEFT JOIN dbpet.tb_grupo_atuacao t4_ ON (r10_.co_grupo_atuacao = t4_.co_seq_grupo_atuacao) AND (t4_.st_registro_ativo = 'S')
WHERE
    t1_.st_registro_ativo = 'S'
    AND t3_.co_seq_perfil = :coPerfil
    AND t0_.st_registro_ativo = 'S'
    AND t4_.co_seq_grupo_atuacao = :coGrupoAtuacao
    AND t0_.st_voluntario_projeto = 'N'
SQL;

            $sqlTotalCursoParticipante = <<<SQL
SELECT
    COUNT(DISTINCT r20_.co_curso_graduacao) AS total
FROM
    dbpet.tb_projeto_pessoa t0_
    LEFT JOIN dbpet.tb_projeto t2_ ON t0_.co_projeto = t2_.co_seq_projeto
    LEFT JOIN dbpet.tb_pessoa_perfil t5_ ON t0_.co_pessoa_perfil = t5_.co_seq_pessoa_perfil
    LEFT JOIN dbpet.tb_perfil t3_ ON t5_.co_perfil = t3_.co_seq_perfil
    LEFT JOIN dbpessoa.tb_pessoa_fisica t6_ ON t5_.nu_cpf = t6_.nu_cpf
    LEFT JOIN dbpessoa.tb_pessoa t1_ ON t6_.nu_cpf = t1_.nu_cpf_cnpj_pessoa
    LEFT JOIN dbpet.tb_dado_pessoal t7_ ON t6_.nu_cpf = t7_.nu_cpf
    LEFT JOIN dbpet.tb_publicacao t8_ ON t2_.co_publicacao = t8_.co_seq_publicacao
    LEFT JOIN dbpet.tb_programa t9_ ON t8_.co_programa = t9_.co_seq_programa
    LEFT JOIN dbpet.rl_projetopessoa_grupoatuacao r10_ ON (t0_.co_seq_projeto_pessoa = r10_.co_projeto_pessoa) AND ((t9_.tp_programa = 2) AND (r10_.st_registro_ativo = 'S'))
    LEFT JOIN dbpet.tb_grupo_atuacao t4_ ON (r10_.co_grupo_atuacao = t4_.co_seq_grupo_atuacao) AND (t4_.st_registro_ativo = 'S')
    LEFT JOIN dbpet.rl_projpes_cursograduacao r20_ ON (t0_.co_seq_projeto_pessoa = r20_.co_projeto_pessoa) AND (r20_.st_registro_ativo = 'S')
WHERE
    t1_.st_registro_ativo = 'S'
    AND t3_.co_seq_perfil = :coPerfil
    AND t0_.st_registro_ativo = 'S'
    AND t4_.co_seq_grupo_atuacao = :coGrupoAtuacao
SQL;

            $sqlValidacaoSituacaoEstudante= <<<SQL
SELECT
    t11_.no_pessoa AS nopessoa,
    t10_.st_aluno_regular AS alunoregular,
    t10_.st_declaracao_curso_penultimo AS penultimo
FROM
    dbpet.tb_projeto_pessoa t0_
    LEFT JOIN dbpet.tb_projeto t2_ ON t0_.co_projeto = t2_.co_seq_projeto
    LEFT JOIN dbpet.tb_pessoa_perfil t5_ ON t0_.co_pessoa_perfil = t5_.co_seq_pessoa_perfil
    LEFT JOIN dbpet.tb_perfil t3_ ON t5_.co_perfil = t3_.co_seq_perfil
    LEFT JOIN dbpessoa.tb_pessoa_fisica t6_ ON t5_.nu_cpf = t6_.nu_cpf
    LEFT JOIN dbpessoa.tb_pessoa t1_ ON t6_.nu_cpf = t1_.nu_cpf_cnpj_pessoa
    LEFT JOIN dbpet.tb_dado_pessoal t7_ ON t6_.nu_cpf = t7_.nu_cpf
    LEFT JOIN dbpet.tb_publicacao t8_ ON t2_.co_publicacao = t8_.co_seq_publicacao
    LEFT JOIN dbpet.tb_programa t9_ ON t8_.co_programa = t9_.co_seq_programa
    LEFT JOIN dbpet.rl_projetopessoa_grupoatuacao r10_ ON (t0_.co_seq_projeto_pessoa = r10_.co_projeto_pessoa) AND ((t9_.tp_programa = 2) AND (r10_.st_registro_ativo = 'S'))
    LEFT JOIN dbpet.tb_grupo_atuacao t4_ ON (r10_.co_grupo_atuacao = t4_.co_seq_grupo_atuacao) AND (t4_.st_registro_ativo = 'S')
    LEFT JOIN dbpet.tb_dado_academico t10_ ON (t0_.co_seq_projeto_pessoa = t10_.co_projeto_pessoa) AND (t10_.st_registro_ativo = 'S')
    LEFT JOIN dbpessoa.tb_pessoa t11_ ON t6_.nu_cpf = t11_.nu_cpf_cnpj_pessoa
WHERE
    t1_.st_registro_ativo = 'S'
    AND t3_.co_seq_perfil = :coPerfil
    AND t0_.st_registro_ativo = 'S'
    AND t4_.co_seq_grupo_atuacao = :coGrupoAtuacao
    AND t4_.co_eixo_atuacao = 'A'
    AND ((t10_.st_aluno_regular <> 'S') OR (t10_.st_declaracao_curso_penultimo <> 'S'))
SQL;

            // Realiza as validações
            foreach ($gruposAtuacao as $grupoAtuacao) { // aka Grupo Tutorial
                /*

                // echo $grupoAtuacao->getNoGrupoAtuacao() . "\n";

                // No fluxo de Confirmar Grupo Tutorial, o sistema deverá verificar a composição do Grupo, permitindo a
                // conclusão apenas quando o grupo possuir 12 Participantes, sendo eles 2 Preceptores, 1 Tutor, 1
                // Coordenador de grupo e 8 Estudantes. Hoje já temos exceção para quantidade mínima, mas não temos para
                // o limite máximo de participantes. Caso o grupo exceda o limite máximo de algum dos Perfis, deverá ser
                // apresentada a seguinte mensagem: "O Grupo [Número do grupo] excedeu o limite máximo de Participantes
                // com o Perfil [Nome do Perfil]. Encontrados: [X número de Participantes encontrados com o Perfil].
                // Limite: [Quantidade limite de participantes daquele referido Perfil]."
                $limites = [
                    'preceptor' => 2,
                    'tutor' => 1,
                    'coordenadorGrupo' => 1,
                    'estudante' => 8,
                ];
                $totais = [
                    'preceptor' => 0,
                    'tutor' => 0,
                    'coordenadorGrupo' => 0,
                    'estudante' => 0,
                ];

                // Cada grupo tutorial deverá ser composto por 8 (oito) estudantes bolsistas
                $records = $conn->fetchAll($sqlTotalParticipante, array(
                    'coPerfil' => 6, // Estudante
                    'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                ));

                $estudantesEncontrados = 0;

                try {
                    $estudantesEncontrados = $records[0]['TOTAL'];
                } catch (\Exception $ex) {
                    // Do nothing.
                }

                $totais['estudante'] = $estudantesEncontrados;

                // echo "Estudantes Encontrados: " . $estudantesEncontrados . "\n";

                if ($estudantesEncontrados != 8) {
                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Cada Grupo Tutorial deverá ser composto por 8 (oito) estudantes bolsistas. Encontrados: ' . $estudantesEncontrados;
                }

                // echo "Eixo: " . $grupoAtuacao->getCoEixoAtuacao() . "\n";

                // RGN169
                $records = $conn->fetchAll($sqlTotalCursoParticipante, array(
                    'coPerfil' => 6, // Estudante
                    'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                ));

                $cursosGraduacaoEncontrados = 0;

                try {
                    $cursosGraduacaoEncontrados = $records[0]['TOTAL'];
                } catch (\Exception $ex) {
                    // Do nothing.
                }

                // echo "Cursos Graduacao Estudantes: " . $cursosGraduacaoEncontrados . "\n";

                switch ($grupoAtuacao->getCoEixoAtuacao()) {

                    case 'G': // Gestão em Saúde
                        if ($cursosGraduacaoEncontrados < 3) {
                            $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Grupo Tutorial deverá possuir estudantes bolsistas distribuídos, em no mínimo, 3 (três) cursos de graduação distintos. Encontrados: ' . $cursosGraduacaoEncontrados;
                        }
                        break;

                    case 'A': // Assistência à Saúde
                        if ($cursosGraduacaoEncontrados < 2) {
                            $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Grupo Tutorial deverá possuir estudantes bolsistas distribuídos, em no mínimo, 2 (dois) cursos de graduação distintos. Encontrados: ' . $cursosGraduacaoEncontrados;
                        }

                        // Validação das situações dos estudantes
                        $records = $conn->fetchAll($sqlValidacaoSituacaoEstudante, array(
                            'coPerfil' => 6, // Estudante
                            'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                        ));

                        try {
                            foreach ($records as $record) {
                                $nome = $record['NOPESSOA'];
                                $alunoRegular = $record['ALUNOREGULAR'];
                                $penultimo = $record['PENULTIMO'];

                                if ($alunoRegular != 'S') {
                                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - O Estudante ' . $nome . ' precisa estar regular em seu curso de graduação. Verifique o cadastro do Participante.';
                                }

                                if ($penultimo != 'S') {
                                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - O Estudante ' . $nome . ' precisa estar cursando os dois últimos anos da graduação. Verifique o cadastro do Participante.';
                                }
                            }
                        } catch (\Exception $ex) {
                            // Do nothing.
                        }
                        break;

                }

                // Cada Grupo Tutorial deverá ser composto por 1 (um) tutor bolsista
                $records = $conn->fetchAll($sqlTotalParticipante, array(
                    'coPerfil' => 5, // Tutor
                    'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                ));

                $tutoresEncontrados = 0;

                try {
                    $tutoresEncontrados = $records[0]['TOTAL'];
                } catch (\Exception $ex) {
                    // Do nothing.
                }

                $totais['tutor'] = $tutoresEncontrados;

                // echo "Tutores Encontrados: " . $tutoresEncontrados . "\n";

                if ($tutoresEncontrados != 1) {
                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Cada Grupo Tutorial deverá ser composto por 1 (um) tutor bolsista. Encontrados: ' . $tutoresEncontrados;
                }

                // Cada Grupo Tutorial deverá ser composto por 1 (um) coordenador de grupo bolsista
                $records = $conn->fetchAll($sqlTotalParticipante, array(
                    'coPerfil' => 3, // Coordenador de Grupo
                    'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                ));

                $coordenadoresEncontrados = 0;

                try {
                    $coordenadoresEncontrados = $records[0]['TOTAL'];
                } catch (\Exception $ex) {
                    // Do nothing.
                }

                $totais['coordenadorGrupo'] = $coordenadoresEncontrados;

                // echo "Coordenadores Encontrados: " . $coordenadoresEncontrados . "\n";

                if ($coordenadoresEncontrados != 1) {
                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Cada Grupo Tutorial deverá ser composto por 1 (um) coordenador de grupo bolsista. Encontrados: ' . $coordenadoresEncontrados;
                }

                // Cada Grupo Tutrial deverá ser composto por no mínimo 2 (dois) e no máximo 4 (quatro) preceptores bolsistas
                $records = $conn->fetchAll($sqlTotalParticipante, array(
                    'coPerfil' => 4, // Preceptores
                    'coGrupoAtuacao' => $grupoAtuacao->getCoSeqGrupoAtuacao(),
                ));

                $preceptoresEncontrados = 0;

                try {
                    $preceptoresEncontrados = $records[0]['TOTAL'];
                } catch (\Exception $ex) {
                    // Do nothing.
                }

                $totais['preceptor'] = $preceptoresEncontrados;

                // echo "Preceptores Encontrados: " . $preceptoresEncontrados . "\n";

                if (($preceptoresEncontrados < 2) || ($preceptoresEncontrados > 4)) {
                    $errors[] = $grupoAtuacao->getNoGrupoAtuacao() . ' - Cada Grupo Tutorial deverá ser composto por no mínimo 2 (dois) e no máximo 4 (quatro) preceptores bolsistas. Encontrados: ' . $preceptoresEncontrados;
                }

                // Não houve erro aparente
                if (count($errors) == 0) {

                    // Precisa validar os limites

                    if ($totais['preceptor'] > $limites['preceptor']) {
                        $errors[] = 'O Grupo ' . $grupoAtuacao->getNoGrupoAtuacao() . ' excedeu o limite máximo de Participantes com o Perfil Preceptor. Encontrados: ' . $totais['preceptor'] . ' Limite: ' . $limites['preceptor'];
                    }

                    if ($totais['tutor'] > $limites['tutor']) {
                        $errors[] = 'O Grupo ' . $grupoAtuacao->getNoGrupoAtuacao() . ' excedeu o limite máximo de Participantes com o Perfil Tutor. Encontrados: ' . $totais['tutor'] . ' Limite: ' . $limites['tutor'];
                    }

                    if ($totais['coordenadorGrupo'] > $limites['coordenadorGrupo']) {
                        $errors[] = 'O Grupo ' . $grupoAtuacao->getNoGrupoAtuacao() . ' excedeu o limite máximo de Participantes com o Perfil Coordenador de Grupo. Encontrados: ' . $totais['coordenadorGrupo'] . ' Limite: ' . $limites['coordenadorGrupo'];
                    }

                    if ($totais['estudante'] > $limites['estudante']) {
                        $errors[] = 'O Grupo ' . $grupoAtuacao->getNoGrupoAtuacao() . ' excedeu o limite máximo de Participantes com o Perfil Estudante. Encontrados: ' . $totais['estudante'] . ' Limite: ' . $limites['estudante'];
                    }
                }
                */
            }

            if (count($errors) > 0) {
                $response->status = false;
                $response->errors = $errors;
            } else {
                foreach ($gruposAtuacao as $grupoAtuacao) { // aka Grupo Tutorial
                    $grupoAtuacao->confirmar();

                    // Persiste na base de dados
                    $em->persist($grupoAtuacao);
                    $em->flush();
                }

                $response->message = 'O(s) grupo(s) foi(ram) confirmado(s) com sucesso.';
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";

            $erros = [];

            if (method_exists($e, 'getViolations')) {
                foreach ($e->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }

            $response->status = false;
            $response->error = implode('\\n', $erros);
        }

        return new JsonResponse($response);
    }

}
