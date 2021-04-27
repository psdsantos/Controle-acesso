<?php
// debug
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
function print_r_pre($mixed = null) {
  echo '<pre>';
  print_r($mixed);
  echo '</pre>';
  return null;
}

// carregar todos os arquivos necessários
require_once __DIR__ . '/vendor/autoload.php';

require_once 'app/core/Core.php';

require_once 'app/controller/HomeController.php';
require_once 'app/controller/ErrorController.php';
require_once 'app/controller/TurmaController.php';
require_once 'app/controller/CategoriaController.php';
require_once 'app/controller/CoordenacaoController.php';
require_once 'app/controller/UsuarioController.php';
require_once 'app/controller/RequisitanteController.php';
require_once 'app/controller/AutorizacaoController.php';
require_once 'app/controller/RegistroController.php';
require_once 'app/controller/LoginController.php';

require_once 'app/model/Turma.php';
require_once 'app/model/Categoria.php';
require_once 'app/model/Coordenacao.php';
require_once 'app/model/Usuario.php';
require_once 'app/model/Requisitante.php';
require_once 'app/model/Autorizacao.php';
require_once 'app/model/Registro.php';

require_once 'lib/database/Connection.php';
require_once 'lib/Util.php';

// preparar template para uso com Twig
ob_start();
    $core = new Core;
    $core->start($_GET);

    $saida = ob_get_contents();
ob_end_clean();
$template = file_get_contents('app/view/template/estrutura.html');

$loader = new \Twig\Loader\FilesystemLoader('app/view/template');
$twig = new \Twig\Environment($loader);
if(!isset($_SESSION)) session_start();
$twig->addGlobal('session', $_SESSION);
$template = $twig->load("estrutura.html");

// para identificar o usuário logado
$parametros = array();
if(isset($_SESSION["matricula"])) $parametros['usuario'] = Usuario::selecionaPorId($_SESSION["matricula"]);

$conteudo = $template->render($parametros);

// substitui 'area_dinamica' no template pelo conteudo
$conteudo = str_replace('area_dinamica', $saida, $conteudo);
echo $conteudo;

?>
