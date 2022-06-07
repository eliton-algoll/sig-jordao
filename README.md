
#########Deploy via ftp://10.1.2.99 , abrir chamado citsmart para replicação #########

#########script para cadastrar um novo usuário##############
#########Senha: 12345678#########

INSERT INTO DBPET.TB_USUARIO(CO_SEQ_USUARIO, NU_CPF, DS_LOGIN, DS_SENHA, ST_REGISTRO_ATIVO, DT_INCLUSAO) 
VALUES (DBPET.SQ_USUARIO_COSEQUSUARIO.NEXTVAL, 'numerodocpf', 'logindousurio', '$2y$12$ahtoR34cf9lryd/bVIKgg.Np19oQp3WZ7b/s6dzzvLhbRuy2fc7Zy', 'S', SYSDATE);                            

INSERT INTO DBPET.TB_PESSOA_PERFIL(CO_SEQ_PESSOA_PERFIL, CO_PERFIL, NU_CPF, ST_REGISTRO_ATIVO, DT_INCLUSAO)
VALUES(DBPET.SQ_PESSOAPERFIL_COSEQPESSOPERF.NEXTVAL, 1, 'numerodocpf', 'S', SYSDATE);