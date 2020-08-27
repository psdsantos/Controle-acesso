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

            $objUsuarios = Usuario::selecionaTodos();

            $parametros = array();
            $parametros['usuarios'] = $objUsuarios;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            session_start();
            if(isset($_SESSION['criado'])){
                if($_SESSION['criado']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Usuário criado com sucesso',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#f5f5f5',
                        backdrop: `rgba(0,0,0,0)`
                    })
                    </script>";
                }
                unset($_SESSION['criado']);
            }
            if(isset($_SESSION['alterado'])){
                if($_SESSION['alterado']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Usuário alterado com sucesso',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#f5f5f5',
                        backdrop: `rgba(0,0,0,0)`
                    })
                    </script>";
                }
                unset($_SESSION['alterado']);
            }
            if(isset($_SESSION['apagado'])){
                if($_SESSION['apagado']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Usuário apagado com sucesso',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#f5f5f5',
                        backdrop: `rgba(0,0,0,0)`
                    })
                    </script>";
                }
                unset($_SESSION['apagado']);
            }
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('addUsuario.html');

            $objCategoria = Categoria::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['categorias'] = $objCategoria;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            session_start();
            try{
                Usuario::insert($_POST);

                $_SESSION["criado"] = "true";
                header('Location:?pagina=usuario');
            } catch(Exception $e){
                echo '<script>Swal.fire("'.$e->getMessage().'" {icon: "error",}).then((value) => {
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
            $template = $twig->load('editUsuario.html');

            $usuario = Usuario::selecionaPorId($usuarioID);
            $objCategoria = Categoria::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['matricula'] = $usuario->matricula;
            $parametros['Nome'] = $usuario->Nome;
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

                session_start();
                $_SESSION["alterado"] = "true";

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
            $template = $twig->load('deleteUsuario.html');

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

                session_start();
                $_SESSION["apagado"] = "true";

                header('Location:?pagina=usuario');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=usuario";</script>';
            }
        }

    }

?>
