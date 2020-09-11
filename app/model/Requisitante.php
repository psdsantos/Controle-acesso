<?php

    class Requisitante{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM requisitante ORDER BY Cod_requisitante DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Requisitante')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($requisitanteID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM requisitante WHERE Cod_requisitante = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $requisitanteID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Requisitante');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeRequisitante']) ){
                throw new Exception("Preencha o nome da requisitante");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO requisitante (Nome, Coordenacao_cod_coordenacao, Turma_Cod_turma) VALUES (:nom, :coord, :turma)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nom', $dadosReq['nomeRequisitante']);
            $sql->bindValue(':turma', $dadosReq['turmaRequisitante']);
            $sql->bindValue(':coord', $dadosReq['coordenacaoRequisitante']);
            $res = $sql->execute();
            $sql->debugDumpParams();
            if($res == false){
                throw new Exception("Falha ao inserir requisitante");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeRequisitante']) ){
                throw new Exception("Preencha o nome da requisitante");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE requisitante SET Nome = :nome, Sigla = :sigla WHERE Cod_requisitante = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeRequisitante']);
            $sql->bindValue(':sigla', $dadosPost['siglaRequisitante']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir publicação");

                return false;
            }

            return true;
        }



        public static function delete($requisitanteID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM Requisitante WHERE Cod_requisitante = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $requisitanteID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao deletar publicação");

                return false;
            }

            return true;
        }
    }
