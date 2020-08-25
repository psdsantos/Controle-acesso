<?php

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
                //print_r_pre($controller);
            }
            else {
                $controller = 'TurmaController';
            }

            if( !class_exists($controller) ){
                $controller = 'ErrorController';
            }

            if( isset($urlGet['id']) && $urlGet['id'] != null){
                $id = $urlGet['id'];
            } else{
                $id = null;
            }

            call_user_func_array( array(new $controller, $action), array('id' => $id) );
        }
        }
