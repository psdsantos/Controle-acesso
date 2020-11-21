<?php

    class TurmaController{
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

            $template = $twig->load('turma.html');

            $objTurmas = Turma::selecionaTodos();

            $parametros = array();
            $parametros['turmas'] = $objTurmas;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            Util::notifyToasts();

        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addTurma.html');

            $objCoord = Coordenacao::selecionaTodos();
            $parametros = array();
            $parametros['coordenacoes'] = $objCoord;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Turma::insert($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["criado"] = true;
                header('Location:?pagina=turma');
            } catch(Exception $e){
                echo '<script>Swal.fire("'.$e->getMessage().'" {icon: "error",}).then((value) => {
                  location.href="?pagina=turma&action=create";
                });</script>';
            }

        }

        public function edit($turmaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editTurma.html');

            $turma = Turma::selecionaPorId($turmaID);

            $parametros = array();
            $parametros['Cod_turma'] = $turma->Cod_turma;
            $parametros['Nome'] = $turma->Nome;
            $parametros['Sigla'] = $turma->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Turma::update($_POST);
                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;
                header('Location:?pagina=turma');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($turmaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('delete/deleteTurma.html');

            $turma = Turma::selecionaPorId($turmaID);

            $parametros = array();
            $parametros['Cod_turma'] = $turma->Cod_turma;
            $parametros['Nome'] = $turma->Nome;
            $parametros['Sigla'] = $turma->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function delete($codTurma){
            try{
                Turma::delete($codTurma);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;
                header('Location:?pagina=turma');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            }
        }

    }

?>
