<?php

    class Usuario{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM usuario ORDER BY matricula DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Usuario')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($usuarioID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM usuario WHERE matricula = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $usuarioID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Usuario');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeUsuario']) || empty($dadosReq['matriculaUsuario']) ){
                throw new Exception("Preencha o nome do usuario");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO usuario (Nome, matricula, Categoria_cod_categoria, Coordenacao_cod_coordenacao) VALUES (:nome, :matr, :categ, :coord)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosReq['nomeUsuario']);
            $sql->bindValue(':matr', $dadosReq['matriculaUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':categ', $dadosReq['categoriaUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':coord', $dadosReq['coordenacaoUsuario'], PDO::PARAM_INT);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir usuario");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeUsuario']) ){
                throw new Exception("Preencha o nome da usuario");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE usuario SET Nome = :nome, Categoria_cod_categoria = :catid, Coordenacao_cod_coordenacao = :corid WHERE matricula = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeUsuario']);
            $sql->bindValue(':catid', $dadosPost['categoriaUsuario']);
            $sql->bindValue(':corid', $dadosPost['coordenacaoUsuario']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao alterar usuário");

                return false;
            }

            return true;
        }



        public static function delete($usuarioID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM usuario WHERE matricula = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $usuarioID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao apagar usuário");

                return false;
            }

            return true;
        }
    }
