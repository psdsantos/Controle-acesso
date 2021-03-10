<?php

    class RegistroController{
        public function index(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);

            $twig->addFunction(new \Twig\TwigFunction('callstatic', function ($class, $method, $args) {
                if (!class_exists($class)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid Class");
                }

                if (!method_exists($class, $method)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid method");
                }

                return forward_static_call([$class, $method], $args);
            }));

            $twig->addFunction(new \Twig\TwigFunction('formatdate', function ($date) {

                $newDate = date("d/m/Y", strtotime($date));

                return $newDate;
            }));

            $twig->addFunction(new \Twig\TwigFunction('formattime', function ($time) {

                $newTime = date_create($time);
                $newTime = date_format($newTime, "H:i");

                return $newTime;
            }));

            $template = $twig->load('registro.html');

            $objRegistros = Registro::selecionaTodos();

            $parametros = array();
            $parametros['registros'] = $objRegistros;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            if(!isset($_SESSION)) session_start();;
            Util::notifyToasts();
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addRegistro.html');

            $objCategoria = Usuario::selecionaTodos();
            $objCoordenacao = Autorizacao::selecionaTodos();

            $parametros = array();
            $parametros['usuarios'] = $objUsuario;
            $parametros['autorizacoes'] = $objAutorizacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Registro::insert($_GET);
                //header('Location:?pagina=registro');
            } catch(Exception $e){

            }

        }

        public function edit($RegistroID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);

            $twig->addFunction(new \Twig\TwigFunction('callstatic', function ($class, $method, $args) {
                if (!class_exists($class)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid Class");
                }

                if (!method_exists($class, $method)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid method");
                }

                return forward_static_call([$class, $method], $args);
            }));
            $template = $twig->load('edit/editRegistro.html');

            $Registro = Registro::selecionaPorId($RegistroID);
            $objCategoria = Categoria::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['cod_registro'] = $Registro->cod_registro;
            $parametros['Nome'] = $Registro->Nome;
            $parametros['Categoria_cod_categoria'] = $Registro->Categoria_cod_categoria;
            $parametros['Coordenacao_cod_coordenacao'] = $Registro->Coordenacao_cod_coordenacao;
            $parametros['categorias'] = $objCategoria;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Registro::update($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;

                header('Location:?pagina=Registro');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=Registro&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($RegistroID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);

            $twig->addFunction(new \Twig\TwigFunction('callstatic', function ($class, $method, $args) {
                if (!class_exists($class)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid Class");
                }

                if (!method_exists($class, $method)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid method");
                }

                return forward_static_call([$class, $method], $args);
            }));
            $template = $twig->load('/delete/deleteRegistro.html');

            $Registro = Registro::selecionaPorId($RegistroID);

            $parametros = array();
            $parametros['cod_registro'] = $Registro->cod_registro;
            $parametros['Nome'] = $Registro->Nome;
            $parametros['Categoria_cod_categoria'] = $Registro->Categoria_cod_categoria;
            $parametros['Coordenacao_cod_coordenacao'] = $Registro->Coordenacao_cod_coordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;

        }

        public function delete($codRegistro){
            try{
                Registro::delete($codRegistro);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;

                header('Location:?pagina=Registro');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=Registro";</script>';
            }
        }

    }

?>
