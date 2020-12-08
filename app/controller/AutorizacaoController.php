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

            $objAutorizacoes = Autorizacao::selecionaTodos();

            $parametros = array();
            $parametros['autorizacoes'] = $objAutorizacoes;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            $myfile = fopen("conteudo.html", "w") or die("Unable to open file!");
            fwrite($myfile, $conteudo);
            fclose($myfile);
            //PdfGenerator::toPDF($conteudo);

            Util::notifyToasts();

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

                if(!isset($_SESSION)) session_start();;
                $_SESSION["criado"] = true;
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

            $twig->addFunction(new \Twig\TwigFunction('callstatic', function ($class, $method, $args) {
                if (!class_exists($class)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid Class");
                }

                if (!method_exists($class, $method)) {
                    throw new \Exception("Cannot call static method $method on Class $class: Invalid method");
                }

                return forward_static_call([$class, $method], $args);
            }));

            $template = $twig->load('edit/editAutorizacao.html');

            $autorizacao = Autorizacao::selecionaPorId($autorizacaoID);
            $objUsuario = Usuario::selecionaTodos();
            $objRequisitante = Requisitante::selecionaTodos();

            $tempoVida = $autorizacao->Tempo_vida;
            $dataValidade = $autorizacao->Data_validade;

            Util::checkValidade($dataValidade, $tempoVida, "autorizacao");

            $parametros = array();
            $parametros['Cod_autorizacao'] = $autorizacao->Cod_autorizacao;
            $parametros['Cod_requisitante'] = $autorizacao->Requisitante_cod_requisitante;
            $parametros['Usuario_matricula'] = $autorizacao->Usuario_matricula;
            $parametros['Laboratorio'] = $autorizacao->Laboratorio;
            $parametros['Obs'] = $autorizacao->Obs;
            $parametros['Data'] = date_create($dataValidade)->format('d-m-Y');
            $parametros['Tempo_vida'] = $tempoVida;
            $parametros['Senha'] = $tempoVida;
            $parametros['usuarios'] = $objUsuario;
            $parametros['requisitantes'] = $objRequisitante;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Autorizacao::update($_POST);
                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($autorizacaoID){
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

            $template = $twig->load('delete/deleteAutorizacao.html');

            $autorizacao = Autorizacao::selecionaPorId($autorizacaoID);
            $objUsuario = Usuario::selecionaTodos();
            $objRequisitante = Requisitante::selecionaTodos();

            $tempoVida = $autorizacao->Tempo_vida;
            $dataValidade = $autorizacao->Data_validade;

            Util::checkValidade($dataValidade, $tempoVida, "autorizacao");

            $parametros = array();
            $parametros['Cod_autorizacao'] = $autorizacao->Cod_autorizacao;
            $parametros['Cod_requisitante'] = $autorizacao->Requisitante_cod_requisitante;
            $parametros['Usuario_matricula'] = $autorizacao->Usuario_matricula;
            $parametros['Laboratorio'] = $autorizacao->Laboratorio;
            $parametros['Obs'] = $autorizacao->Obs;
            $parametros['Data'] = date_create($dataValidade)->format('d-m-Y');
            $parametros['Tempo_vida'] = $tempoVida;
            $parametros['usuarios'] = $objUsuario;
            $parametros['requisitantes'] = $objRequisitante;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function delete($codAutorizacao){
            try{
                Autorizacao::delete($codAutorizacao);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao";</script>';
            }
        }

        public function observacao($codAutorizacao){
            try {
                if(!isset($_SESSION)) session_start();;
                $_SESSION["obs"] = $codAutorizacao;
                header('Location:?pagina=autorizacao');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=autorizacao";</script>';
            }
        }

    }

?>
