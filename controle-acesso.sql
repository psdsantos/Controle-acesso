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
  Hora_validade TIME  NOT NULL  ,
  Tempo_vida TIME  NOT NULL  ,
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
