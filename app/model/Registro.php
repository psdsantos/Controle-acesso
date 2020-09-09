<?php

    class Registro{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM registro_acesso ORDER BY Cod_registro DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Registro')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($RegistroID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM registro_acesso WHERE Cod_registro = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $RegistroID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Registro');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeRegistro']) || empty($dadosReq['Cod_registro']) ){
                throw new Exception("Preencha o nome do Registro");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO Registro (Nome, Cod_registro, Categoria_cod_categoria, Coordenacao_cod_coordenacao) VALUES (:nome, :matr, :categ, :coord)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosReq['nomeRegistro']);
            $sql->bindValue(':matr', $dadosReq['Cod_registro'], PDO::PARAM_INT);
            $sql->bindValue(':categ', $dadosReq['categoriaRegistro'], PDO::PARAM_INT);
            $sql->bindValue(':coord', $dadosReq['coordenacaoRegistro'], PDO::PARAM_INT);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir Registro");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeRegistro']) ){
                throw new Exception("Preencha o nome do Registro");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE Registro SET Nome = :nome, Categoria_cod_categoria = :catid, Coordenacao_cod_coordenacao = :corid WHERE Cod_registro = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeRegistro']);
            $sql->bindValue(':catid', $dadosPost['categoriaRegistro']);
            $sql->bindValue(':corid', $dadosPost['coordenacaoRegistro']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao alterar usuário");

                return false;
            }

            return true;
        }



        public static function delete($RegistroID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM registro_acesso WHERE Cod_registro = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $RegistroID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao apagar usuário");

                return false;
            }

            return true;
        }
    }
