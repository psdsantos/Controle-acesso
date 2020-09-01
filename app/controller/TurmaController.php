<?php

    class TurmaController{
        public function index(){
                $loader = new \Twig\Loader\FilesystemLoader('app/view');
                $twig = new \Twig\Environment($loader);
                $template = $twig->load('turma.html');

                $objTurmas = Turma::selecionaTodos();

                $parametros = array();
                $parametros['turmas'] = $objTurmas;

                $conteudo = $template->render($parametros);
                echo $conteudo;

                session_start();
                if(isset($_SESSION['criado'])){
                    if($_SESSION['criado']){
                        echo "<script>
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Turma criada com sucesso',
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
                            title: 'Turma alterada com sucesso',
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
                            title: 'Turma apagada com sucesso',
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
            $template = $twig->load('add/addTurma.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Turma::insert($_POST);

                session_start();
                $_SESSION["criado"] = "true";
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
                session_start();
                $_SESSION["alterado"] = "true";
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

                session_start();
                $_SESSION["apagado"] = "true";
                header('Location:?pagina=turma');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            }
        }

    }

?>
