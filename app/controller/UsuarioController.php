<?php

    class UsuarioController{
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

            $template = $twig->load('usuario.html');

            $parametros = array();
            $parametros['status'] = 1;
            if(isset($_GET['inativos'])){
                $parametros['status'] = 0;
                $parametros['checked'] = 'checked';
            }
            $objUsuarios = Usuario::selecionaTodos($parametros['status']);
            $parametros['usuarios'] = $objUsuarios;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            Util::notifyToasts();
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addUsuario.html');

            $objCategoria = Categoria::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['categorias'] = $objCategoria;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            if(!isset($_SESSION)) session_start();;
            try{
                Usuario::insert($_POST);

                $_SESSION["criado"] = true;
                header('Location:?pagina=usuario');
            } catch(Exception $e){
                echo '<script>Swal.fire({icon: "error", text: "'.$e->getMessage().'"}).then((value) => {
                  location.href="?pagina=usuario&action=create";
                });</script>';
            }

        }

        public function edit($usuarioID){
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

            $template = $twig->load('edit/editUsuario.html');

            $usuario = Usuario::selecionaPorId($usuarioID);
            $objCategoria = Categoria::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['matricula'] = $usuario->matricula;
            $parametros['Nome'] = $usuario->Nome;
            $parametros['Rfid'] = $usuario->Rfid;
            $parametros['Senha'] = $usuario->Senha;
            $parametros['Status_usuario'] = $usuario->Status_usuario;
            $parametros['Categoria_cod_categoria'] = $usuario->Categoria_cod_categoria;
            $parametros['Coordenacao_cod_coordenacao'] = $usuario->Coordenacao_cod_coordenacao;
            $parametros['categorias'] = $objCategoria;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Usuario::update($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;

                header('Location:?pagina=usuario');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=usuario&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($usuarioID){
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
            $template = $twig->load('/delete/deleteUsuario.html');

            $usuario = Usuario::selecionaPorId($usuarioID);

            $parametros = array();
            $parametros['matricula'] = $usuario->matricula;
            $parametros['Nome'] = $usuario->Nome;
            $parametros['Categoria_cod_categoria'] = $usuario->Categoria_cod_categoria;
            $parametros['Coordenacao_cod_coordenacao'] = $usuario->Coordenacao_cod_coordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;

        }

        public function delete($codUsuario){
            try{
                Usuario::delete($codUsuario);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;

                header('Location:?pagina=usuario');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=usuario";</script>';
            }
        }

    }

?>
