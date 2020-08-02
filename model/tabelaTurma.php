<?php
    $stmt = $pdo->query("SELECT * FROM turma");
        echo '<table border="1">'."\n";
        while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            echo "<tr><td>";
            echo($row['Nome']);
            echo("</td><td>");
            echo('<form action="delete.php" method="post"><input type="hidden" ');
            echo('name="Cod_turma" value="' .$row["Cod_turma"]. '">' ."\n");
            echo('<input type="submit" value="Delete" name="delete">');
            echo("\n</form>\n");
            echo("</td><td>");
            echo('<form action="edit.php" method="post"><input type="hidden" ');
            echo('name="Cod_turma" value="' .$row["Cod_turma"]. '">' ."\n");
            echo('<input type="submit" value="Edit" name="edit">');
            echo("\n</form>\n");
            echo("</td></tr>");
        }
        echo "</table>\n";
?>