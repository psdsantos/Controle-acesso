<?php
    // conexão com o banco de dados
    abstract class Connection{
        private static $conn;

        public static function getConn(){
            if(self::$conn == null){
                try{
                    self::$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=controle-acesso;', 'root', '250503'); // mudar de acordo com a configuração do BD
                } catch(Exception $e){
                    self::$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=controle-acesso;', 'root', '152603');
                }

            }
            return self::$conn;
        }
    }
