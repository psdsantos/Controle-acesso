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
            if(isset($_SESSION['criado'])){
                if($_SESSION['criado']){
                    echo "<script>
                    const Toast = Swal.mixin({
                        toast:true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        background: '#f5f5f5',
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Categoria criada com sucesso',
                    })
                    </script>";
                }
                unset($_SESSION['criado']);
            }
            if(isset($_SESSION['alterado'])){
                if($_SESSION['alterado']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Categoria alterada com sucesso',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#f5f5f5',
                        backdrop: `rgba(0,0,0,0)`
                    })
                    </script>";
                }
                unset($_SESSION['alterado']);
            }
            if(isset($_SESSION['apagado'])){
                if($_SESSION['apagado']){
                    echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Categoria apagada com sucesso',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#f5f5f5',
                        backdrop: `rgba(0,0,0,0)`
                    })
                    </script>";
                }
                unset($_SESSION['apagado']);
            }
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

                session_start();
                $_SESSION["criado"] = "true";
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

                session_start();
                $_SESSION["alterado"] = "true";
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

                session_start();
                $_SESSION["apagado"] = "true";
                header('Location:?pagina=categoria');
            } catch(Exception $e){
                echo '<script>alert("'.$e->getMessage().'");</script>';
                echo '<script>location.href="?pagina=categoria";</script>';
            }
        }

    }

?>
