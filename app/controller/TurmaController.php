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
                if(isset($_SESSION['success'])){
                    if($_SESSION['success']){
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
                    unset($_SESSION['success']);
                }
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('addTurma.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            session_start();
            try{
                Turma::insert($_POST);

                $_SESSION["success"] = "true";
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
            $template = $twig->load('editTurma.html');

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

                echo '<script>alert("Turma alterada com sucesso!");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($turmaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('deleteTurma.html');

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

                echo '<script>alert("Turma deletada com sucesso!");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            }
        }

    }

?>
