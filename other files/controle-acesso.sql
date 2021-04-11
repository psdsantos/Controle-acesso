CREATE TABLE Coordenacao (
  Cod_coordenacao INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Nome VARCHAR(50)  NOT NULL    ,
  Sigla VARCHAR(10)  NOT NULL    ,
PRIMARY KEY(cod_coordenacao));
      

            
CREATE TABLE Turma (
  Cod_turma INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Coordenacao_cod_coordenacao INTEGER UNSIGNED NOT NULL    ,
  Nome VARCHAR(50)  NOT NULL    ,
  Sigla VARCHAR(10)  NOT NULL    ,
PRIMARY KEY(Cod_turma)    ,
INDEX Turma_FKIndex1(Coordenacao_cod_coordenacao)  ,
  FOREIGN KEY(Coordenacao_cod_coordenacao)
    REFERENCES Coordenacao(cod_coordenacao)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);

            

CREATE TABLE Categoria (
  Cod_categoria INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Descricao VARCHAR(50)  NOT NULL    ,
PRIMARY KEY(cod_categoria));


-- ------------------------------------------------------------
-- Status_usuario
-- 0 - Inativo
-- 1 - Ativo
-- ------------------------------------------------------------

CREATE TABLE Usuario (
  matricula INTEGER UNSIGNED  NOT NULL  ,
  Categoria_cod_categoria INTEGER UNSIGNED  NOT NULL  ,
  Coordenacao_cod_coordenacao INTEGER UNSIGNED  NOT NULL  ,
  Nome VARCHAR(70)  NOT NULL  ,
  Rfid CHAR(20)  NOT NULL  ,
  Senha CHAR(10)  NOT NULL UNIQUE  ,
  Status_usuario INTEGER UNSIGNED  NOT NULL    ,
PRIMARY KEY(matricula)  ,
INDEX Usuario_FKIndex1(Coordenacao_cod_coordenacao)  ,
INDEX Usuario_FKIndex2(Categoria_cod_categoria),
  FOREIGN KEY(Coordenacao_cod_coordenacao)
    REFERENCES Coordenacao(cod_coordenacao)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(Categoria_cod_categoria)
    REFERENCES Categoria(cod_categoria)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
COMMENT = 'Status_usuario  0 - Inativo  1 - Ativo' ;



CREATE TABLE Requisitante (
  Cod_requisitante INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Coordenacao_cod_coordenacao INTEGER UNSIGNED  NOT NULL  ,
  Turma_Cod_turma INTEGER UNSIGNED  NOT NULL  ,
  Nome VARCHAR(70)  NOT NULL  ,
PRIMARY KEY(cod_requisitante)  ,
INDEX Requisitante_FKIndex1(Turma_Cod_turma)  ,
INDEX Requisitante_FKIndex2(Coordenacao_cod_coordenacao),
  FOREIGN KEY(Turma_Cod_turma)
    REFERENCES Turma(Cod_turma)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(Coordenacao_cod_coordenacao)
    REFERENCES Coordenacao(cod_coordenacao)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE Autorizacao (
  Cod_autorizacao INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Requisitante_cod_requisitante INTEGER UNSIGNED  NOT NULL  ,
  Usuario_matricula INTEGER UNSIGNED  NOT NULL  ,
  Data_validade DATE  NOT NULL  ,
  Hora_inicial TIME  NOT NULL  ,
  Hora_final TIME  NOT NULL  ,
  Senha CHAR(12)  NOT NULL  ,
  Laboratorio INTEGER UNSIGNED  NOT NULL    ,
  Obs VARCHAR(500)  NULL    ,
PRIMARY KEY(Cod_Autorizacao)  ,
INDEX Autorizacao_FKIndex1(Usuario_matricula)  ,
INDEX Autorizacao_FKIndex2(Requisitante_cod_requisitante),
  FOREIGN KEY(Usuario_matricula)
    REFERENCES Usuario(matricula)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(Requisitante_cod_requisitante)
    REFERENCES Requisitante(cod_requisitante)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE Registro_Acesso (
  Cod_registro INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  Autorizacao_cod_autorizacao INTEGER UNSIGNED  NOT NULL  ,
  Usuario_matricula INTEGER UNSIGNED  NULL  ,
  Data_acesso DATE  NOT NULL  ,
  Hora_acesso TIME  NOT NULL  ,
  Laboratorio INTEGER UNSIGNED  NOT NULL    ,
PRIMARY KEY(cod_registro)  ,
INDEX Registro_Acesso_FKIndex1(Usuario_matricula)  ,
INDEX Registro_Acesso_FKIndex2(Autorizacao_cod_autorizacao),
  FOREIGN KEY(Usuario_matricula)
    REFERENCES Usuario(matricula)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(Autorizacao_cod_autorizacao)
    REFERENCES Autorizacao(Cod_autorizacao)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);

            

-- Admin account 1            
-- USERNAME: 321654987
-- PASSWORD: 465132
-- Admin account 2
-- USERNAME: 996103810
-- PASSWORD: 250503
INSERT INTO `coordenacao` (`Cod_coordenacao`, `Nome`, `Sigla`) VALUES ('1', 'Coordenadoria de informática', 'COINF');
INSERT INTO `turma` (`Cod_turma`, `Coordenacao_cod_coordenacao`, `Nome`, `Sigla`) VALUES (1, 1, '3° ano integrado em informática', '3IINF');
INSERT INTO `categoria` (`Cod_categoria`, `Descricao`) VALUES ('1', 'Coordenação');
INSERT INTO `usuario` (`matricula`, `Categoria_cod_categoria`, `Coordenacao_cod_coordenacao`, `Nome`, `Rfid`, `Senha`, `Status_usuario`) VALUES ('321654987', '1', '1', 'Pedro Silva dos Santos', '0', '465132', '1');
INSERT INTO `usuario` (`matricula`, `Categoria_cod_categoria`, `Coordenacao_cod_coordenacao`, `Nome`, `Rfid`, `Senha`, `Status_usuario`) VALUES ('996103810', '1', '1', 'Luiz Fernando Batista Morato', '0', '250503', '1');