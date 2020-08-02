<?php 
    include('header.php');
    session_start();
?>

    <div class="principal">

    <?php 

        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';

        switch($pagina){

            case 'turma':
                include_once 'view/turma.php';
            break;

            case 'addTurma':
                include_once 'view/addTurma.php';
            break;

            case 'viewTurma':
                include_once 'view/viewTurma.php';
            break;
            
            
            case 'home':
            break;

            default:
            break;
        }
    ?>

    </div>
</body>

<?php 
    include('footer.php');
?>