<?php

    class CoordenacaoController{
        public function index(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('coordenacao.html');

            $objCoordenacoes = Coordenacao::selecionaTodos();

            $parametros = array();
            $parametros['coordenacoes'] = $objCoordenacoes;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            if(!isset($_SESSION)) session_start();;
            Util::notifyToasts();
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addCoordenacao.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            if(!isset($_SESSION)) session_start();;
            try{
                Coordenacao::insert($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["criado"] = true;
                header('Location:?pagina=coordenacao');
            } catch(Exception $e){
                echo '<script>Swal.fire("'.$e->getMessage().'" {icon: "error",}).then((value) => {
                  location.href="?pagina=coordenacao&action=create";
                });</script>';
            }

        }

        public function edit($coordenacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editCoordenacao.html');

            $coordenacao = Coordenacao::selecionaPorId($coordenacaoID);

            $parametros = array();
            $parametros['Cod_coordenacao'] = $coordenacao->Cod_coordenacao;
            $parametros['Nome'] = $coordenacao->Nome;
            $parametros['Sigla'] = $coordenacao->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Coordenacao::update($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;
                header('Location:?pagina=coordenacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=coordenacao&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($coordenacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('delete/deleteCoordenacao.html');

            $coordenacao = Coordenacao::selecionaPorId($coordenacaoID);

            $parametros = array();
            $parametros['Cod_coordenacao'] = $coordenacao->Cod_coordenacao;
            $parametros['Nome'] = $coordenacao->Nome;
            $parametros['Sigla'] = $coordenacao->Sigla;

            $conteudo = $template->render($parametros);
            echo $conteudo;

        }

        public function delete($codCoordenacao){
            try{
                Coordenacao::delete($codCoordenacao);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;
                header('Location:?pagina=coordenacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=coordenacao";</script>';
            }
        }

    }

?>
