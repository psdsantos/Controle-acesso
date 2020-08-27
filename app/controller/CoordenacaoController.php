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

                session_start();
                if(isset($_SESSION['success'])){
                    if($_SESSION['success']){
                        echo "<script>
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Coordenação criada com sucesso',
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
            $template = $twig->load('addCoordenacao.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            session_start();
            try{
                Coordenacao::insert($_POST);

                $_SESSION["success"] = "true";
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
            $template = $twig->load('editCoordenacao.html');

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

                echo '<script>alert("Coordenacao alterada com sucesso!");</script>';
                echo '<script>location.href="?pagina=coordenacao";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=coordenacao&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($coordenacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('deleteCoordenacao.html');

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

                echo '<script>alert("Coordenacao deletada com sucesso!");</script>';
                echo '<script>location.href="?pagina=coordenacao";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=coordenacao";</script>';
            }
        }

    }

?>
