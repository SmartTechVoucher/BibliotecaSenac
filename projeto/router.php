<?php
session_start();
require_once __DIR__ . "../../projeto/src/controller/usuario/login-controller.php";
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
                $_SESSION['toast'] = "Login feito com sucesso!";
                header("Location: ./index.php");
                exit;
            } else {
                $_SESSION['toast'] = "Usuário ou senha inválidos!";
                header("Location: ./src/views/usuario/login.php");
                exit;
            }
            break; // <- aqui o break precisa estar dentro do switch e após o case

            
            // if ($resultado) {
            //     $_SESSION['usuario'] = $nome; // salva o nome do usuário
            //     $_SESSION['toast'] = "Login feito com sucesso!";
            //     header("Location: ./index.php");
            //     exit;
            // }
            
            // } else {
            //     else {
            //         $_SESSION['toast'] = "Usuário ou senha inválidos!";
            //         header("Location: ./src/views/usuario/login.php");
            //         exit;
            //     }
            // }
           
        
        default:
            echo "Erro: Ação não reconhecida.";
            exit;
    }

    // switch($_GET['acao']) {
    //     case ''
    // }
}