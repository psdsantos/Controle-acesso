<?php

require_once("model/pdo.php");

// se chegar nessa página depois de adicionar uma turma
if(isset($_SESSION['added'])){
    echo $_SESSION['added'];
    unset($_SESSION['added']);
}

?>

    <h1>Turmas:</h1>
    <p><a href="?pagina=addTurma">Adicionar</a> uma turma.</p>

<?php

    require_once("model/tabelaTurma.php");

    include("footer.php");
?>
