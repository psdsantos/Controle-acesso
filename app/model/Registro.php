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
            $sql->execute();

            $resultado = $sql->fetchObject('Registro');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosGET){
            //var_dump($dadosGET);

            // simulação de usuário com o cartao 1
            if($dadosGET['cartao'] == 1) {
                $matricula = 2018332552; // se for usuário com o cartao 1
                $autorizacao = 0;

                $data = array("auth" => 1);
                echo json_encode($data); // Embarcado recebe auth = 1 e abre a tranca
            }

            $con = Connection::getConn();

            if(!isset($matricula)) { // não é um usuário
                // cartao 2 ou 3
                // Selecionar usuário, autorização com Rfid igual ao pino (mudar senha para RFID e pino para RFID),
                // checar validade (ainda não passou do horário)
                // checar se já foi efetivada
                $sql = " SELECT Usuario_matricula, Cod_autorizacao
                         FROM autorizacao as a, usuario as u
                         WHERE a.Rfid = :cartao AND a.Usuario_matricula = u.matricula
                            AND (NOW() < concat(Data_validade, ' ', Hora_final) AND NOW() > concat(Data_validade, ' ', Hora_inicial)) -- Se for válido
                            AND Efetivada = 0;

                         UPDATE autorizacao SET Efetivada = 1
                         WHERE autorizacao.Cod_autorizacao = (SELECT Cod_autorizacao
                                                              FROM usuario as u -- from autorização já está implícito no UPDATE
                                                              WHERE autorizacao.Rfid = :cartao AND autorizacao.Usuario_matricula = u.matricula
                                                                    AND (NOW() < concat(Data_validade, ' ', Hora_final) AND NOW() > concat(Data_validade, ' ', Hora_inicial))); -- Se for válido
                          ";
                $sql = $con->prepare($sql);
                $sql->bindValue(':cartao', $dadosGET['cartao'], PDO::PARAM_INT);
                $res = $sql->execute();
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                if(empty($resultado)) {
                    $data = array("auth" => 0); // Embarcado recebe auth = 0 e não abre a tranca
                    echo json_encode($data);

                    return true;
                }
                $matricula = $resultado['Usuario_matricula'];
                $autorizacao = $resultado["Cod_autorizacao"];

                $data = array("auth" => 1);
                echo json_encode($data); // Embarcado recebe auth = 1 e abre a tranca
            }

            $sql = " INSERT INTO registro_acesso (Data_acesso, Hora_acesso, Laboratorio, Usuario_matricula, Autorizacao_cod_autorizacao)
                        VALUES (:data_esp, :tempo_esp, :lab, :matricula, :autorizacao) ";
            $sql = $con->prepare($sql);
            $sql->bindValue(':data_esp', Util::higienize($dadosGET["date"]));
            $sql->bindValue(':tempo_esp', Util::higienize($dadosGET["time"]));
            $sql->bindValue(':lab', 1);
            $sql->bindValue(':matricula', $matricula, PDO::PARAM_INT);
            $sql->bindValue(':autorizacao', $autorizacao, PDO::PARAM_INT);

            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao inserir Registro");

                echo "Falha ao inserir";
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
