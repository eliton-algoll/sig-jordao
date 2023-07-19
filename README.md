## CONFIGURAÇÃO DE AMBIENTE LOCAL
O sistema em produção está rodando com o PHP 5.6

#### Projeto no repositório GIT. (https://gitlab.saude.gov.br/datasus-legados/sigpet)
    * Crie uma branch a partir da develop.

#### Para configurar o ambiente loca é necessário rodar as dependências do composer com o comando
```
php bin/composer.phar install
```
Iniciando o servidor symfony local
```
symfony server:start
```



### DEPLOYS
Para deploy em Homologação/Produção rodar os comandos abaixo para criação dos arquivos JS.
```
php bin/console assets:install
```
```
php bin/console assetic:dump
```
```
php bin/console assetic:dump --env=prod --no-debug (para prod)
``` 


#### script para cadastrar um novo usuário
O usuário terá a senha: 12345678 <br>
SQL
``` 
INSERT INTO DBPET.TB_USUARIO(CO_SEQ_USUARIO, NU_CPF, DS_LOGIN, DS_SENHA, ST_REGISTRO_ATIVO, DT_INCLUSAO) 
VALUES (DBPET.SQ_USUARIO_COSEQUSUARIO.NEXTVAL, 'numerodocpf', 'logindousurio', '$2y$12$ahtoR34cf9lryd/bVIKgg.Np19oQp3WZ7b/s6dzzvLhbRuy2fc7Zy', 'S', SYSDATE);                            

INSERT INTO DBPET.TB_PESSOA_PERFIL(CO_SEQ_PESSOA_PERFIL, CO_PERFIL, NU_CPF, ST_REGISTRO_ATIVO, DT_INCLUSAO)
VALUES(DBPET.SQ_PESSOAPERFIL_COSEQPESSOPERF.NEXTVAL, 1, 'numerodocpf', 'S', SYSDATE);
``` 
