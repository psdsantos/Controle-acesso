<?php

    class TurmaController{
        public function index(){
            try{

                $loader = new \Twig\Loader\FilesystemLoader('app/view');
                $twig = new \Twig\Environment($loader);
                $template = $twig->load('turma.html');

                $objTurmas = Turma::selecionaTodos();

                $parametros = array();
                $parametros['turmas'] = $objTurmas;

                $conteudo = $template->render($parametros);
                echo $conteudo;

            } catch(Exception $e){
                echo $e->getMessage();
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
            try{
                Turma::insert($_POST);

                echo '<script>alert("Turma inserida com sucesso!");</script>';
                echo '<script>location.href="?pagina=turma";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=turma&action=create";</script>';
            }

        }

    }

?>
