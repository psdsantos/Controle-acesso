<?php

    abstract class Connection{
        private static $conn;

        public static function getConn(){
            if(self::$conn == null){
                try{
                    self::$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=tcc;', 'root', '152603');
                } catch(Exception $e){
                    self::$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=tcc;', 'root', '');
                }

            }
            return self::$conn;
        }
    }