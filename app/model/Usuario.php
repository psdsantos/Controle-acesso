<?php

    class Usuario{
        public static function selecionaTodos($status = 1){
            $con = Connection::getConn();

            $sql = "SELECT * FROM usuario WHERE Status_usuario = $status ORDER BY matricula DESC";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultado = array();

            // converter a query em um objeto
            while($row = $sql->fetchObject('Usuario')){
                $resultado[] = $row;
            }

            return $resultado;
        }

        public static function selecionaPorId($usuarioID){
            $con = Connection::getConn();
            $sql = "SELECT * FROM usuario WHERE matricula = :id";
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $usuarioID, PDO::PARAM_INT);
            $sql-> execute();

            $resultado = $sql->fetchObject('Usuario');

            if(!$resultado){
                throw new Exception("Não foi encontrado nenhum registro no banco");
            }

            return $resultado;
        }

        public static function insert($dadosReq){
            if( empty($dadosReq['nomeUsuario']) || empty($dadosReq['matriculaUsuario']) ){
                throw new Exception("Preencha o nome do usuario");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'INSERT INTO usuario (Nome, matricula, Categoria_cod_categoria, Coordenacao_cod_coordenacao, Rfid, Status_usuario, Senha)
              VALUES (:nome, :matr, :categ, :coord, :rfid, :status, :senha)';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosReq['nomeUsuario']);
            $sql->bindValue(':matr', $dadosReq['matriculaUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':categ', $dadosReq['categoriaUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':coord', $dadosReq['coordenacaoUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':status', $dadosReq['statusUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':rfid', $dadosReq['RFID']);
            $sql->bindValue(':senha', $dadosReq['senhaUsuario']);
            $res = $sql->execute();
            $sql->DebugDumpParams();
            if($res == false){
                throw new Exception("Falha ao inserir usuario");

                return false;
            }

            return true;
        }

        public static function update($dadosReq){
            if( empty($dadosReq['nomeUsuario']) ){
                throw new Exception("Preencha o nome do usuario");

                return false;
            }

            $con = Connection::getConn();

            $sql = 'UPDATE usuario SET Nome = :nome, Categoria_cod_categoria = :catid, Coordenacao_cod_coordenacao = :corid,
              Rfid = :rfid, Senha = :senha, Status_usuario = :status WHERE matricula = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':nome', $dadosReq['nomeUsuario']);
            $sql->bindValue(':catid', $dadosReq['categoriaUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':corid', $dadosReq['coordenacaoUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':status', $dadosReq['statusUsuario'], PDO::PARAM_INT);
            $sql->bindValue(':id', $dadosReq['id'], PDO::PARAM_INT);
            $sql->bindValue(':rfid', $dadosReq['RFID']);
            $sql->bindValue(':senha', $dadosReq['senhaUsuario']);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao alterar usuário");

                return false;
            }

            return true;
        }

        public static function delete($usuarioID){

            $con = Connection::getConn();

            $sql = 'DELETE FROM usuario WHERE matricula = :id';
            $sql = $con->prepare($sql);
            $sql->bindValue(':id', $usuarioID);
            $res = $sql->execute();

            if($res == false){
                throw new Exception("Falha ao apagar usuário");

                return false;
            }

            return true;
        }

        public static function validarLogin($matricula, $senha, $matricula_err, $senha_err){
            // Validate credentials
            if(empty($matricula_err) && empty($senha_err)){
                // Prepare a select statement
                $sql = "SELECT matricula, Senha FROM Usuario WHERE matricula = :matricula";
                $con = Connection::getConn();

                if($stmt = $con->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":matricula", $matricula);

                    // Attempt to execute the prepared statement
                    if($stmt->execute()){
                        // Check if matricula exists, if yes then verify senha
                        if($stmt->rowCount() == 1){
                            if($row = $stmt->fetch()){
                                $matricula = $row["matricula"];
                                $hashed_senha = $row["Senha"];
                                if(Util::verifyPassword($senha, $hashed_senha)){
                                    // senha is correct, so start a new session
                                    session_start();

                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["matricula"] = $matricula;

                                    // Redirect user to welcome page
                                    header("Location:?pagina=home");
                                } else{
                                    // Display an error message if senha is not valid
                                    $_SESSION["error"] = "Senha incorreta.";
                                    header("Location:?pagina=login");
                                }
                            }
                        } else{
                            // Display an error message if matricula doesn't exist
                            $_SESSION["error"] = "Nenhum usuário encontrado com tal matrícula.";
                            header("Location:?pagina=login");
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
            }
        }
    }
