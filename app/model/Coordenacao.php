<?php

    class Coordenacao{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM coordenacao ORDER BY cod_coordenacao DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Coordenacao')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($coordenacaoID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM coordenacao WHERE Cod_coordenacao = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $coordenacaoID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Coordenacao');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeCoordenacao']) ){
                throw new Exception("Preencha o nome da coordenacao");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO coordenacao (Nome) VALUES (:nom)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nom', $dadosReq['nomeCoordenacao']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir coordenacao");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['nomeCoordenacao']) ){
                throw new Exception("Preencha o nome da coordenacao");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE coordenacao SET Nome = :nome WHERE Cod_coordenacao = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosPost['nomeCoordenacao']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir publicação");

                return false;
            }

            return true;
        }



        public static function delete($coordenacaoID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM Coordenacao WHERE Cod_coordenacao = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $coordenacaoID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao deletar publicação");

                return false;
            }

            return true;
        }
    }
