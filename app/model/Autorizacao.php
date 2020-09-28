<?php

    class Autorizacao{
        public static function selecionaTodos(){
            $con = Connection::getConn();

            $sql = "SELECT * FROM Autorizacao ORDER BY Cod_autorizacao DESC";
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
            $sql = "SELECT * FROM Autorizacao WHERE Cod_autorizacao = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $autorizacaoID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Autorizacao');

            return $resultado;
        }

        public static function insert($dadosReq){
            $con = Connection::getConn();

            $sql = "INSERT INTO Autorizacao (Requisitante_cod_requisitante, Usuario_matricula, Data_validade,
                                            Hora_validade, Tempo_vida, Senha, Laboratorio, Obs)
                                VALUES (:req, :user, :data, :hora, :vida, :senha, :lab, :obs)";
            $sql = $con->prepare($sql);
            $sql->bindValue(':req', $dadosReq['requisitante'], PDO::PARAM_INT);
            $sql->bindValue(':user', $dadosReq['usuario'], PDO::PARAM_INT);
                $d = DateTime::createFromFormat('j/m/Y', $dadosReq['data']);
            $sql->bindValue(':data', $d->format('Y-m-d'));
                $hora = date_create($dadosReq['hora']);
            $sql->bindValue(':hora', date_format($hora,"H:i"));
                date_add($hora, date_interval_create_from_date_string('1800 seconds')); // + 30 min
            $sql->bindValue(':vida', date_format($hora,"H:i"));
            $sql->bindValue(':senha', $dadosReq['senha']);
            $sql->bindValue(':lab', $dadosReq['laboratorio'], PDO::PARAM_INT);
            $sql->bindValue(':obs', $dadosReq['obs']);
            $res = $sql->execute();
            //$sql->debugDumpParams();

            if($res == false){
                throw new Exception("Falha ao inserir autorizacao");
                return false;
            }

            return true;
        }


        public static function update($dadosPost){
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
