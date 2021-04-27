<?php
    // define quais páginas carregar
    class Core{
        public function start($urlGet){
            if( isset($urlGet['action'])){
                $action = $urlGet['action'];
            }
            else {
                $action = 'index';
            }

            if( isset($urlGet['pagina']) ){
                $controller = ucfirst($urlGet['pagina']).'Controller';
            }
            else {
                $controller = 'HomeController';
            }

            //print_r_pre($controller);
            if( !class_exists($controller) ){
                $controller = 'ErrorController';
            }

            if( isset($urlGet['id']) && $urlGet['id'] != null){
                $id = $urlGet['id'];
            } else{
                $id = null;
            }

            session_start();
            if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"])) {
                if($controller != 'LoginController' && $controller != 'HomeController') $controller = 'ErrorController';
                if($controller != 'LoginController') $action = 'index';

                if(session_id() === "3027436a2a4e44517d2446555c") { // se for a ESP32 (hash definido no firmware)
                    $controller = 'RegistroController';
                    $action = 'insert';
                }
                $id = null;
            }

            call_user_func_array( array(new $controller, $action), array('id' => $id) );
        }
    }
