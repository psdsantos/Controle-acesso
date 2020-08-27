<?php

    class Categoria{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM categoria ORDER BY Cod_categoria DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Categoria')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($categoriaID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM categoria WHERE Cod_categoria = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $categoriaID, PDO::PARAM_INT);
            $sql-> execute();
            //print_r_pre($categoriaID);
            $resultado = $sql->fetchObject('Categoria');

            if(!$resultado){
                echo("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['descCategoria']) ){
                throw new Exception("Preencha a descrição da categoria");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO categoria (Descricao) VALUES (:desc)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':desc', $dadosReq['descCategoria']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir categoria");

                return false;
            }

            return true;
        }


        public static function update($dadosPost){
            if( empty($dadosPost['descCategoria']) ){
                throw new Exception("Preencha a descrição da categoria");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE categoria SET Descricao = :desc WHERE Cod_categoria = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':desc', $dadosPost['descCategoria']);
            $sql->bindValue(':id', $dadosPost['id']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao alterar categoria");

                return false;
            }

            return true;
        }



        public static function delete($categoriaID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM Categoria WHERE Cod_categoria = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $categoriaID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao deletar categoria");

                return false;
            }

            return true;
        }
    }
