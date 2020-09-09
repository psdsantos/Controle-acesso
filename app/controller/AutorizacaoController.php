<?php

    class AutorizacaoController{
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

                $template = $twig->load('autorizacao.html');

                $objAutorizacaos = Autorizacao::selecionaTodos();

                $parametros = array();
                $parametros['autorizacoes'] = $objAutorizacaos;

                $conteudo = $template->render($parametros);
                echo $conteudo;

                session_start();
                if(isset($_SESSION['criado'])){
                    if($_SESSION['criado']){
                        echo "<script>
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Autorizacao criada com sucesso',
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
                            title: 'Autorizacao alterada com sucesso',
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
                            title: 'Autorizacao apagada com sucesso',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#f5f5f5',
                            backdrop: `rgba(0,0,0,0)`
                        })
                        </script>";
                    }
                    unset($_SESSION['apagado']);
                }
                if(isset($_SESSION['obs'])){
                    $idobs = $_SESSION['obs'];
                    echo "<script>
                        Swal.fire({
                            title: 'Observação',
                            text: ' ".Autorizacao::selecionaPorId($idobs)->Obs." ',
                            background: '#f5f5f5',
                        })
                    </script>";

                    unset($_SESSION['obs']);
                }

        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addAutorizacao.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Autorizacao::insert($_POST);

                session_start();
                $_SESSION["criado"] = "true";
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>Swal.fire("'.$e->getMessage().'" {icon: "error",}).then((value) => {
                  location.href="?pagina=autorizacao&action=create";
                });</script>';
            }

        }

        public function edit($autorizacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editAutorizacao.html');

            $autorizacao = Autorizacao::selecionaPorId($autorizacaoID);

            $parametros = array();
            $parametros['Cod_autorizacao'] = $autorizacao->Cod_autorizacao;
            $parametros['Nome'] = $autorizacao->Nome;
            $parametros['Sigla'] = $autorizacao->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Autorizacao::update($_POST);
                session_start();
                $_SESSION["alterado"] = "true";
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($autorizacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('delete/deleteAutorizacao.html');

            $autorizacao = Autorizacao::selecionaPorId($autorizacaoID);

            $parametros = array();
            $parametros['Cod_autorizacao'] = $autorizacao->Cod_autorizacao;
            $parametros['Nome'] = $autorizacao->Nome;
            $parametros['Sigla'] = $autorizacao->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function delete($codAutorizacao){
            try{
                Autorizacao::delete($codAutorizacao);

                session_start();
                $_SESSION["apagado"] = "true";
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao";</script>';
            }
        }

        public function observacao($codAutorizacao){
            try {
                session_start();
                $_SESSION["obs"] = $codAutorizacao;
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao";</script>';
            }
        }

    }

?>