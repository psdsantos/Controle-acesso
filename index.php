<?php

// debug
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
function print_r_pre($mixed = null) {
  echo '<pre>';
  print_r($mixed);
  echo '</pre>';
  return null;
}

require_once 'app/core/Core.php';

require_once 'app/controller/TurmaController.php';
require_once 'app/controller/ErrorController.php';

require_once 'app/model/Turma.php';

require_once 'lib/database/Connection.php';

require 'vendor/autoload.php';

$template = file_get_contents('app/template/estrutura.html');

ob_start();
    $core = new Core;
    $core->start($_GET);

    $saida = ob_get_contents();
ob_end_clean();

//carrega o controller apropriado na {{area_dinamica}} do template
$tplPronto = str_replace('{{area_dinamica}}', $saida, $template);
echo $tplPronto;

?>
