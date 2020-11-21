<?php

    class CategoriaController{
        public function index(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('categoria.html');

            $objCategorias = Categoria::selecionaTodos();

            $parametros = array();
            $parametros['categorias'] = $objCategorias;

            $conteudo = $template->render($parametros);
            echo $conteudo;

            Util::notifyToasts();
        }

        public function create(){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('add/addCategoria.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            try{
                Categoria::insert($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["criado"] = true;
                header('Location:?pagina=categoria');
            } catch(Exception $e){
                echo '<script>Swal.fire("'.$e->getMessage().'" {icon: "error",}).then((value) => {
                  location.href="?pagina=categoria&action=create";
                });</script>';
            }

        }

        public function edit($categoriaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('edit/editCategoria.html');

            $categoria = Categoria::selecionaPorId($categoriaID);

            $parametros = array();
            $parametros['Cod_categoria'] = $categoria->Cod_categoria;
            $parametros['Descricao'] = $categoria->Descricao;

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function update(){
            try{
                Categoria::update($_POST);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["alterado"] = true;
                header('Location:?pagina=categoria');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=categoria&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($categoriaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('delete/deleteCategoria.html');

            $categoria = Categoria::selecionaPorId($categoriaID);

            $parametros = array();
            $parametros['Cod_categoria'] = $categoria->Cod_categoria;
            $parametros['Descricao'] = $categoria->Descricao;

            $conteudo = $template->render($parametros);
            echo $conteudo;

        }

        public function delete($codCategoria){
            try{
                Categoria::delete($codCategoria);

                if(!isset($_SESSION)) session_start();;
                $_SESSION["apagado"] = true;
                header('Location:?pagina=categoria');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=categoria";</script>';
            }
        }

    }

?>
