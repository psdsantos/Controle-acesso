<?php

    class Util {
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
                        title: 'Criado com sucesso',
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
                        title: 'Alterado com sucesso',
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
                        title: 'Apagado com sucesso',
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
    }
