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

            $twig->addFunction(new \Twig\TwigFunction('formatdate', function ($date) {

                $newDate = date("d/m/Y", strtotime($date));

                return $newDate;
            }));

            $twig->addFunction(new \Twig\TwigFunction('formattime', function ($time) {

                $newTime = date_create($time);
                $newTime = date_format($newTime, "H:i");

                return $newTime;
            }));

            $template = $twig->load('autorizacao.html');

            $objAutorizacaos = Autorizacao::selecionaTodos();

            $parametros = array();
            $parametros['autorizacoes'] = $objAutorizacaos;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            session_start();
            Util::notifyToasts();

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
            if(isset($_SESSION['unauthorized'])){
                echo "<script>
                    Swal.fire({
                        title: 'Operação não permitida',
                        text: 'Esta autorização não pode mais ser alterada.',
                        background: '#f5f5f5',
                    })
                </script>";
                unset($_SESSION['unauthorized']);
            }
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addAutorizacao.html');

            $objUsuario = Usuario::selecionaTodos();
            $objRequisitante = Requisitante::selecionaTodos();

            $parametros = array();
            $parametros['usuarios'] = $objUsuario;
            $parametros['requisitantes'] = $objRequisitante;

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
                echo '<script>Swal.fire({
                    icon: "error",
                    title: "'.$e->getMessage().'"
                }).then((value) => {
                  location.href="?pagina=autorizacao&action=create";
                });</script>';
            }

        }

        public function edit($autorizacaoID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editAutorizacao.html');

            $autorizacao = Autorizacao::selecionaPorId($autorizacaoID);
            $tempoVida = $autorizacao->Tempo_vida;
            $dataValidade = $autorizacao->Data_validade;

            Util::checkValidade($dataValidade, $tempoVida, "autorizacao");

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

            $tempoVida = $autorizacao->Tempo_vida;
            $dataValidade = $autorizacao->Data_validade;

            Util::checkValidade($dataValidade, $tempoVida, "autorizacao");

            $parametros = array();
            $parametros['Cod_autorizacao'] = $autorizacao->Cod_autorizacao;
            $parametros['Requisitante'] = $autorizacao->Requisitante_cod_requisitante;
            $parametros['Laboratorio'] = $autorizacao->Laboratorio;
            $parametros['Data'] = $dataValidade;
            $parametros['Tempo_vida'] = $tempoVida;

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
