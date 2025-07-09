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

                // Redireciona conforme o login
                if ($nome === 'admin123') {
                    header("Location: ./src/views/admin/telaInicialDoAdm.php");
                } else {
                    header("Location: ./index.php");
                }
                exit;
            } else {
                header("Location: ./src/views/usuario/login.php");
                exit;
            }
            break; // ✅ o break fica aqui, dentro do case
        default:
            echo "Erro: Ação não reconhecida.";
            exit;
    }
}
