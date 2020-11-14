<?php

    class LoginController{
        public function index(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('Login.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function login(){
            // Initialize the session
            session_start();

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
            session_start();

            // Unset all of the session variables
            $_SESSION = array();

            // Destroy the session.
            session_destroy();

            // Redirect to login page
            header("location:?login");
            exit;
        }

    }

?>
