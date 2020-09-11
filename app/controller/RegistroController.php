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

            $template = $twig->load('Registro.html');

            $objRegistros = Registro::selecionaTodos();

            $parametros = array();
            $parametros['registros'] = $objRegistros;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            session_start();
            if(isset($_SESSION['criado'])){
                if($_SESSION['criado']){
                    echo "<script>
                    const Toast = Swal.mixin({
                        toast:true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        background: '#f5f5f5',
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Registro criado com sucesso',
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
            $template = $twig->load('add/addRegistro.html');

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
                Registro::insert($_POST);

                $_SESSION["criado"] = "true";
                header('Location:?pagina=Registro');
            } catch(Exception $e){
                echo '<script>Swal.fire({icon: "error", text: "'.$e->getMessage().'"}).then((value) => {
                  location.href="?pagina=Registro&action=create";
                });</script>';
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

                session_start();
                $_SESSION["alterado"] = "true";

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

                session_start();
                $_SESSION["apagado"] = "true";

                header('Location:?pagina=Registro');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=Registro";</script>';
            }
        }

    }

?>
