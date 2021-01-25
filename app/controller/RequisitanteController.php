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

            if(!isset($_SESSION)) session_start();
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

                if(!isset($_SESSION)) session_start();;
                $_SESSION["criado"] = true;
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante&action=create";</script>';
            }

        }

        public function edit($requisitanteID){
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

            $template = $twig->load('edit/editRequisitante.html');

            /*// checar se já foi usado
            if($acessos = Requisitante::selecionaAcessos($requisitanteID)){
                if(!isset($_SESSION)) session_start();;
                $_SESSION['unauthorized'] = true;
                header('Location:?pagina=requisitante');
            }*/

            $requisitante = Requisitante::selecionaPorId($requisitanteID);
            $objTurma = Turma::selecionaTodos();
            $objCoordenacao = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['Cod_requisitante'] = $requisitante->Cod_requisitante;
            $parametros['Nome'] = $requisitante->Nome;
            $parametros['cod_turma'] = $requisitante->Turma_Cod_turma;
            $parametros['cod_coordenacao'] = $requisitante->Coordenacao_cod_coordenacao;
            $parametros['turmas'] = $objTurma;
            $parametros['coordenacoes'] = $objCoordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Requisitante::update($_POST);
                if(!$_SESSION) session_start();
                $_SESSION["alterado"] = true;
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($requisitanteID){
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

            $template = $twig->load('delete/deleteRequisitante.html');

            $requisitante = Requisitante::selecionaPorId($requisitanteID);

            // checar se já foi usado
            if($acessos = Requisitante::selecionaAcessos($requisitanteID)){
                if(!isset($_SESSION)) session_start();
                $_SESSION['unauthorized'] = true;
                header('Location:?pagina=requisitante');
            }

            $parametros = array();
            $parametros['Cod_requisitante'] = $requisitante->Cod_requisitante;
            $parametros['Nome'] = $requisitante->Nome;
            $parametros['cod_turma'] = $requisitante->Turma_Cod_turma;
            $parametros['cod_coordenacao'] = $requisitante->Coordenacao_cod_coordenacao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function delete($codRequisitante){
            try{
                Requisitante::delete($codRequisitante);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante";</script>';
            }
        }

        public function verAcessos($requisitanteID){
            try{
                $acessos = Requisitante::selecionaAcessos($requisitanteID);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["verAcessos"] = $acessos;
                header('Location:?pagina=requisitante');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=requisitante";</script>';
            }
        }

    }

?>
