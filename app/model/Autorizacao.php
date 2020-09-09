<?php

    class Autorizacao{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM Usuario_has_Requisitante ORDER BY Cod_Autorizacao DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Autorizacao')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($autorizacaoID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM Usuario_has_Requisitante WHERE Cod_Autorizacao = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $autorizacaoID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Autorizacao');

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeAutorizacao']) ){
                throw new Exception("Preencha o nome da autorizacao");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO autorizacao (Nome, Sigla) VALUES (:nom, :sigla)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nom', $dadosReq['nomeAutorizacao']);
            $sql->bindValue(':sigla', $dadosReq['siglaAutorizacao']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir autorizacao");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeAutorizacao']) ){
                throw new Exception("Preencha o nome da autorizacao");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE autorizacao SET Nome = :nome, Sigla = :sigla WHERE Cod_autorizacao = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeAutorizacao']);
            $sql->bindValue(':sigla', $dadosPost['siglaAutorizacao']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir publicação");

                return false;
            }

            return true;
        }



        public static function delete($autorizacaoID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM Autorizacao WHERE Cod_autorizacao = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $autorizacaoID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao deletar publicação");

                return false;
            }

            return true;
        }
    }
