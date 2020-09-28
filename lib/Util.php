<?php

    class Util {

        public static function checkValidade($dataValidade, $tempoVida, $entity){
            /*
                DEBUGGING DATE AND TIME
            echo $tempoVida . '<br>';
            echo date("H:i:s");
            echo "<br>";
            echo "<br>";
            echo $d = $dataValidade;
            echo "<br>";
            echo "<br>";
            echo date("Y-m-d H:i:s");
            echo "<br>";
            */

            date_default_timezone_set('America/Maceio');
            echo $combinedDT = date('Y-m-d H:i:s', strtotime("$dataValidade $tempoVida"));

            if(date("Y-m-d H:i:s") > $combinedDT){
                session_start();
                $_SESSION['unauthorized'] = true;
                header('Location:?pagina='.$entity);
            }
        }

        public static function notifyToasts() {
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
        }
    }
