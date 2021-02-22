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

        public static function insert($dadosReq){

            $file = 'erorr.txt';
            $current = file_get_contents($file);
            $current .= "vamo la inserir\n";
            file_put_contents($file, $current);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $con = Connection::getConn();
                    $sql = " INSERT INTO registro_acesso (Data_acesso, Hora_acesso)
                                VALUES (:data_esp, :tempo_esp) ";
                    $sql = $con->prepare($sql);
                    $sql->bindValue(':data_esp', Util::higienize($dadosReq["date"]));
                    $sql->bindValue(':tempo_esp', Util::higienize($dadosReq["time"]));
                    $res = $sql->execute();

                    if($res == false){
                        throw new Exception("Falha ao inserir Registro");
                        $file = 'erorr.txt';
                        $current = file_get_contents($file);
                        $current .= "falha oa inserir registro\n";
                        file_put_contents($file, $current);
                        echo "Falha ao inserir";
                        return false;
                    }

                    $file = 'erorr.txt';
                    $current = file_get_contents($file);
                    $current .= "nada de errado por aq\n";
                    file_put_contents($file, $current);
                    echo "nada errado";
                    return true;
            }
            else {

                $file = 'erorr.txt';
                file_put_contents($file, "nao foi post");

                echo "No data posted with HTTP POST.";
            }
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
