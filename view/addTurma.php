<?php

require_once("model/pdo.php");

if( isset($_POST['nomeTurma']) ){

    $_SESSION['nomeTurma'] = htmlentities($_POST['nomeTurma']);
    $_SESSION['ready'] = true;
    header('Location: ./?pagina=addTurma');
    return;
}
else if(isset($_SESSION["ready"])){
    $sql = "INSERT INTO turma (Nome) VALUES (:nomeTurma)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':nomeTurma' => $_SESSION['nomeTurma'],
    ));
    $_SESSION['added'] = "Turma inserida.";
    unset($_SESSION['nomeTurma']);
    unset($_SESSION['ready']);
    header('Location: ./?pagina=viewTurma');
    return;
}

?>

<html>
    <form method="POST">
        <p>Nome: <input name="nomeTurma" type="text" required></p>
        <input type="submit" value="Add">
    </form>
    
    <p><a href="?pagina=viewTurma">Ver turmas.</a></p>
</html>