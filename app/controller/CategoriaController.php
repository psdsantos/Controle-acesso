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

            session_start();
            if(isset($_SESSION['success'])){
                if($_SESSION['success']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Categoria criada com sucesso',
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
            $template = $twig->load('addCategoria.html');

            $parametros = array();

            $conteudo = $template->render($parametros);
            echo $conteudo;
        }

        public function insert(){
            session_start();
            try{
                Categoria::insert($_POST);

                $_SESSION["success"] = "true";
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
            $template = $twig->load('editCategoria.html');

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

                echo '<script>alert("Categoria alterada com sucesso!");</script>';
                echo '<script>location.href="?pagina=categoria";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=categoria&action=edit&id='.$_POST["id"].'";</script>';
            }
        }

        public function predelete($categoriaID){
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('deleteCategoria.html');

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

                echo '<script>alert("Categoria deletada com sucesso!");</script>';
                echo '<script>location.href="?pagina=categoria";</script>';
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=categoria";</script>';
            }
        }

    }

?>
