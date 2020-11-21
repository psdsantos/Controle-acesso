<?php

    class LoginController{
        public function index(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('login.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;

            if(!isset($_SESSION)) session_start();;
            Util::notifyToasts();
        }

        public function login(){
            if(!isset($_SESSION)) session_start();

            // Check if the user is already logged in, if yes then redirect him to welcome page
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("Location:?pagina=home#ja_estava_logado");
                exit;
            }

            // Define variables and initialize with empty values
            $matricula = $senha = "";
            $matricula_err = $senha_err = "";

            // Processing form data when form is submitted
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                // Check if matricula is empty
                if(empty(trim($_POST["matricula"]))){
                    $matricula_err = "Please enter matricula.";
                } else{
                    $matricula = trim($_POST["matricula"]);
                }

                // Check if senha is empty
                if(empty(trim($_POST["senha"]))){
                    $senha_err = "Please enter your senha.";
                } else{
                    $senha = trim($_POST["senha"]);
                }

                Usuario::validarLogin($matricula, $senha, $matricula_err, $senha_err);
            }
        }

        public function logout(){
            if(!isset($_SESSION)) session_start();
            $_SESSION = array();
            session_destroy();

            if(!isset($_SESSION)) session_start();;
            $_SESSION['deslogado'] = true;

            header("Location:?pagina=home");

            exit;
        }

    }

?>
