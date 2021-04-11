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
            //$sql->debugDumpParams();

            $resultado = $sql->fetchObject('Autorizacao');

            return $resultado;
        }

        public static function insert($dadosReq){
            $con = Connection::getConn();

            $sql = "INSERT INTO Autorizacao (Requisitante_cod_requisitante, Usuario_matricula, Data_validade,
                                            Hora_inicial, Hora_final, Rfid, Laboratorio, Obs)
                                VALUES (:req, :user, :data, :hora, :final, :rfid, :lab, :obs)";
            $sql = $con->prepare($sql);
            $sql->bindValue(':req', $dadosReq['requisitante'], PDO::PARAM_INT);
            $sql->bindValue(':user', $dadosReq['usuario'], PDO::PARAM_INT);
                $d = DateTime::createFromFormat('j/m/Y', $dadosReq['data']);
            $sql->bindValue(':data', $d->format('Y-m-d'));
                $hora = date_create($dadosReq['hora']);
            $sql->bindValue(':hora', date_format($hora,"H:i"));
                date_add($hora, date_interval_create_from_date_string('1800 seconds')); // + 30 min
            $sql->bindValue(':final', date_format($hora,"H:i"));
            $sql->bindValue(':rfid', $dadosReq['rfid']);
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


        public static function update($dadosReq){
            $con = Connection::getConn();

            $sql = "UPDATE Autorizacao SET Requisitante_cod_requisitante = :req, Usuario_matricula = :user, Data_validade = :data,
                                            Hora_inicial = :hora_inicial, Hora_final = :hora_final, Rfid = :rfid, Laboratorio = :lab, Obs = :obs
                                            WHERE Cod_autorizacao = :cod_aut";
            $sql = $con->prepare($sql);
            $sql->bindValue(':cod_aut', $dadosReq['Cod_autorizacao'], PDO::PARAM_INT);
            $sql->bindValue(':req', $dadosReq['requisitante'], PDO::PARAM_INT);
            $sql->bindValue(':user', $dadosReq['usuario'], PDO::PARAM_INT);
                $d = DateTime::createFromFormat('j/m/Y', $dadosReq['data']);
            $sql->bindValue(':data', $d->format('Y-m-d'));
                $hora = date_create($dadosReq['hora']);
            $sql->bindValue(':hora_inicial', date_format($hora,"H:i"));
                date_add($hora, date_interval_create_from_date_string('1800 seconds')); // + 30 min
            $sql->bindValue(':hora_final', date_format($hora,"H:i"));
            $sql->bindValue(':rfid', $dadosReq['rfid']);
            $sql->bindValue(':lab', $dadosReq['laboratorio'], PDO::PARAM_INT);
            $sql->bindValue(':obs', $dadosReq['obs']);
            $res = $sql->execute();
            $sql->debugDumpParams();

            if($res == false){
                throw new Exception("Falha ao alterar autorizacao");
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
                throw new Exception("Falha ao deletar");

                return false;
            }

            return true;
        }

        public static function checarEfetivadas(){

            $con = Connection::getConn();

            // para cada autorização:
            // se não foi efetivada e horário seja inválido:
                // marcar como efetivada
            $sql = " UPDATE autorizacao SET Efetivada =
                                                autorizacao.Efetivada OR NOW() > concat(Data_validade, ' ', Hora_final); -- no passado
                      ";
            $sql = $con->prepare($sql);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao checar autorizacoes efetivadas");
                return false;
            }

            return true;
        }

        public static function rfidNotUsed(){

            $con = Connection::getConn();

            /*$sql = " SELECT DISTINCT Rfid FROM autorizacao WHERE
                                                NOT ( (NOW() < concat(Data_validade, ' ', Hora_final)
                                                AND NOW() > concat(Data_validade, ' ', Hora_inicial)) ) -- inválido
                                                AND Rfid <> (SELECT DISTINCT Rfid FROM autorizacao WHERE
                                                                NOW() < concat(Data_validade, ' ', Hora_final)
                                                                AND NOW() > concat(Data_validade, ' ', Hora_inicial) -- válido
                                                            );
                      ";*/
            $sql = "SELECT DISTINCT Rfid FROM autorizacao WHERE Efetivada = 1
                                                        AND Rfid <> (SELECT DISTINCT Rfid FROM autorizacao WHERE
                                                                NOW() < concat(Data_validade, ' ', Hora_final)
                                                                AND NOW() > concat(Data_validade, ' ', Hora_inicial))-- válido";
            $sql = $con->prepare($sql);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao selecionar rfid não usados");
                return false;
            }

            $resultado = [];

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultado[] = $row;
            }


            return $resultado;
            //else return false;

        }
    }
