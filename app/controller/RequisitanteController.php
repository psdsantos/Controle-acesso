<?php

    class RequisitanteController{
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

                $template = $twig->load('requisitante.html');

                $objRequisitantes = Requisitante::selecionaTodos();

                $parametros = array();
                $parametros['requisitantes'] = $objRequisitantes;

                $conteudo = $template->render($parametros);
                echo $conteudo;

                session_start();
                Util::notifyToasts();

        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addRequisitante.html');

            $objTurma = Turma::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['turmas'] = $objTurma;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Requisitante::insert($_POST);

                session_start();
                $_SESSION["criado"] = "true";
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante&action=create";</script>';
            }

        }

        public function edit($requisitanteID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editRequisitante.html');

            $requisitante = Requisitante::selecionaPorId($requisitanteID);

            $parametros = array();
            $parametros['Cod_requisitante'] = $requisitante->Cod_requisitante;
            $parametros['Nome'] = $requisitante->Nome;
            $parametros['Sigla'] = $requisitante->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Requisitante::update($_POST);
                session_start();
                $_SESSION["alterado"] = "true";
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($requisitanteID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('delete/deleteRequisitante.html');

            $requisitante = Requisitante::selecionaPorId($requisitanteID);

            $parametros = array();
            $parametros['Cod_requisitante'] = $requisitante->Cod_requisitante;
            $parametros['Nome'] = $requisitante->Nome;
            $parametros['Sigla'] = $requisitante->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function delete($codRequisitante){
            try{
                Requisitante::delete($codRequisitante);

                session_start();
                $_SESSION["apagado"] = "true";
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante";</script>';
            }
        }

    }

?>
