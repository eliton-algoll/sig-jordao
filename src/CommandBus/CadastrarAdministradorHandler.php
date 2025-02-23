<?php

namespace App\CommandBus;

use App\Entity\EnderecoWeb;
use App\Repository\PerfilRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Repository\UsuarioRepository;
use App\Repository\PessoaFisicaRepository;
use App\Entity\Usuario;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Symfony\Component\Mime\Email;

class CadastrarAdministradorHandler
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    
    /**
     * @var Environment
     */
    private $templateEngine;
    
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;
    
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     * @var PessoaFisicaRepository
     */
    private $pessoaFisicaRepository;

    /**
     * @var PerfilRepository
     */
    private $perfilRepository;
    
    /**
     * @param MailerInterface $mailer
     * @param EncoderFactoryInterface $encoderFactory
     * @param UsuarioRepository $usuarioRepository
     * @param PessoaFisicaRepository $pessoaFisicaRepository
     */
    public function __construct(
        MailerInterface $mailer,
        Environment $templateEngine,
        EncoderFactoryInterface $encoderFactory, 
        UsuarioRepository $usuarioRepository,
        PessoaFisicaRepository $pessoaFisicaRepository,
        PerfilRepository $perfilRepository
    ) {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->encoderFactory = $encoderFactory;
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
        $this->perfilRepository = $perfilRepository;
    }
    
    /**
     * @param CadastrarAdministradorCommand $command
     */
    public function handle(CadastrarAdministradorCommand $command)
    {

        $user = $this->usuarioRepository->findBy(['dsLogin' => $command->getDsLogin()]);
        if( $user ) {
            throw new \InvalidArgumentException('O Login já está em uso. Selecione outra opção.');
        }

        $senha = bin2hex(random_bytes(4));
        $cpf = preg_replace("/[^0-9]/", "", $command->getNuCpf());
        $encoder = $this->encoderFactory->getEncoder('App\Entity\Usuario');
        $perfil = $this->perfilRepository->find(1);
        $pessoaFisica = $this->pessoaFisicaRepository->find($cpf);


        if (!$pessoaFisica->getUsuario()) {
            $usuario = new Usuario($pessoaFisica, $command->getDsLogin(), $encoder->encodePassword($senha, null));
        } else {
            $usuario = $pessoaFisica->getUsuario()->ativar();
            $usuario->setDsLogin($command->getDsLogin());
        }
        $emails = $usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos();

        $saveEmail = true;
        foreach ($emails as $email) {
            if( $command->getEmail() == $email->getDsEnderecoWeb()) {
                $saveEmail = false;
            }
        }
        if( $saveEmail ) {
            $pessoa = $usuario->getPessoaFisica()->getPessoa();
            $enderecoWeb = new EnderecoWeb($pessoa, $command->getEmail());
            $usuario->getPessoaFisica()->getPessoa()->getEnderecosWeb()->add($enderecoWeb);
        }

        $pessoaFisica->addPerfil($perfil);
        $this->usuarioRepository->add($usuario);


        foreach ($pessoaFisica->getPessoasPerfisAtivos() as $pessoaPerfil) {
            if ($pessoaPerfil->getPerfil()->isAdministrador()) {
                $this->sendEmailConfirmacao($usuario, $senha);
                break;
            }
        }
    }

    public function sendEmailConfirmacao(Usuario $usuario, string $rawPassword)
    {
        $emails = [];
        foreach ($usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos() as $email) {
            $emails[] = $email->getDsEnderecoWeb();
        }

        if (empty($emails)) {
            throw new \RuntimeException('Nenhum e-mail válido encontrado para envio.');
        }

        $htmlBody = $this->templateEngine->render('/participante/email_confirmacao_cadastro_adm.html.twig', [
            'dsLogin' => $usuario->getDsLogin(),
            'dsSenha' => $rawPassword,
        ]);

        $email = (new Email())
            ->from('info.sgtes@saude.gov.br')
            ->to(...$emails)
            ->subject('SIGPET - Confirmação de cadastro')
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}
