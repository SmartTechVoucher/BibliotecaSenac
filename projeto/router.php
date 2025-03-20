<?php
require_once __DIR__ . "../../projeto/src/controller/usuario/loginController.php";
$loginController = new LoginController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_GET["acao"])) {
        echo "Erro: Nenhuma ação especificada.";
        exit;
    }

    switch ($_GET["acao"]) {
        case 'validarLogin':
            $nome = $_POST["nome"] ?? '';
            $senha = $_POST["senha"] ?? '';
            $resultado = $loginController->ValidarLogin($nome, $senha);
            if ($resultado) {
                echo "funcionou";
                header("Location: ./src/views/usuario/pagina_de_sucesso.php");
                exit;
            } else {
                echo "deu ruim";
                header("Location: ./src/views/usuario/login.php");
                exit;
            }
            break;
        
        default:
            echo "Erro: Ação não reconhecida.";
            exit;
    }

    // switch($_GET['acao']) {
    //     case ''
    // }
}