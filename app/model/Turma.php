<?php

    class Turma{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM turma ORDER BY cod_turma DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Turma')){
                $resultado[] = $row;
            }

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }
            return $resultado;
        }

        public static function selecionaPorId($turmaID){
            $con = Connection::getConn();
            print_r_pre($turmaID);
            $sql = "SELECT * FROM turma WHERE Cod_turma = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $turmaID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Turma');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeTurma']) ){
                throw new Exception("Preencha o nome da turma");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO turma (Nome) VALUES (:nom)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nom', $dadosReq['nomeTurma']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir turma");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeTurma']) ){
                throw new Exception("Preencha o nome da turma");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE turma SET Nome = :nome WHERE Cod_turma = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeTurma']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir publicação");

                return false;
            }

            return true;
        }



        public static function delete($turmaID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM Turma WHERE Cod_turma = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $turmaID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao deletar publicação");

                return false;
            }

            return true;
        }
    }