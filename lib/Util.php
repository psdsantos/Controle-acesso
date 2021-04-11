<?php

    class Util {

        public static function checkValidade($dataValidade, $hora, $entity){

            date_default_timezone_set('America/Maceio');
            $combinedDT = date('Y-m-d H:i:s', strtotime("$dataValidade $hora"));

            /*   //  DEBUGGING DATE AND TIME
            echo "hora validade: ".$hora;
            echo "<br>";
            echo "data validade: ".$dataValidade;
            echo "<br>";
            echo "combinedt: ".$combinedDT;
            echo "<br>";
            echo date("Y-m-d H:i:s");
            */

            if(date("Y-m-d H:i:s") < $combinedDT){
                if(!isset($_SESSION)) session_start();
                $_SESSION['unauthorized'] = true;
                echo "oxen";
                //header('Location:?pagina='.$entity);
            }
        }

        public static function notifyToasts() {
            if(!isset($_SESSION)) session_start();
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
                        title: 'Adicionado com sucesso',
                    })
                    </script>";
                }
                unset($_SESSION['criado']);
            }
            if(isset($_SESSION['alterado'])){
                if($_SESSION['alterado']){
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
                        title: 'Alterado com sucesso',
                    })
                    </script>";
                }
                unset($_SESSION['alterado']);
            }
            if(isset($_SESSION['apagado'])){
                if($_SESSION['apagado']){
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
                        title: 'Apagado com sucesso',
                    })
                    </script>";
                }
                unset($_SESSION['apagado']);
            }

            if(isset($_SESSION['deslogado'])){
                if($_SESSION['deslogado']){
                    echo "<script>
                    const Toast = Swal.mixin({
                        toast:true,
                        position: 'top-right',
                        showConfirmButton: true,
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
                        title: 'Deslogado com sucesso',
                    })
                    </script>";
                }
                unset($_SESSION['deslogado']);
            }

            // Autorizacao
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
                        icon: 'error',
                        title: 'Operação não permitida',
                        text: 'Esta linha não pode mais ser alterada.',
                        background: '#f5f5f5',
                    })
                </script>";
                unset($_SESSION['unauthorized']);
            }

            if(isset($_SESSION['error'])){
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Ocorreu um erro',
                        text: '".$_SESSION['error']."',
                        background: '#f5f5f5',
                    })
                </script>";
                unset($_SESSION['error']);
            }

            if(isset($_SESSION['verAcessos'])){
                $ac = '';
                foreach($_SESSION["verAcessos"] as $idx => $req) {
                    $ac .= $req['Cod_autorizacao'] . "  ";
                }

                echo "<script>
                Swal.fire({
                    title: 'Código dos acessos',
                    text: '".$ac."',
                    background: '#f5f5f5',
                })
                </script>";
                unset($_SESSION['verAcessos']);
            }
        }

        public static function verifyPassword($password, $hashed_password){
            // hashing is yet to be implemented
            return $password == $hashed_password;
        }

        public static function higienize($data) {
            $data = trim($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }
