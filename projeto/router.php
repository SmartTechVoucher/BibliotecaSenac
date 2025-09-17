<?php
session_start();
require_once __DIR__ . "/src/controller/usuario/login-controller.php";
require_once __DIR__ . "/src/controller/admin/AdminController.php";

if (!isset($_GET["acao"])) {
    echo "Erro: Nenhuma ação especificada.";
    exit;
}

$acao = $_GET["acao"];

if ($acao === 'auxEntity') {
    error_log('T1: Router auxEntity reached, tipo: ' . ($_GET['tipo'] ?? 'none'));
    require_once __DIR__ . "/src/controller/admin/AuxEntityController.php";
    if (!isset($_GET['tipo'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo de entidade não especificado.']);
        exit;
    }
    $tipo = $_GET['tipo'];
    try {
        $auxController = new AuxEntityController($tipo);
        $auxController->handle();
    } catch (Exception $e) {
        error_log('T2: Router auxEntity error: ' . $e->getMessage());
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno no controlador.']);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($acao) {
        case 'validarLogin':
            $loginController = new LoginController();
            $nome = $_POST["nome"] ?? '';
            $senha = $_POST["senha"] ?? '';
            $resultado = $loginController->ValidarLogin($nome, $senha);
            
            if ($resultado) {
                header("Location: ./src/views/usuario/index.php");
                exit;
            } else {
                header("Location: ./src/views/usuario/login.php");
                exit;
            }
            break;

        case 'loginAdmin':
            $adminController = new AdminController();
            $email = $_POST["nome"] ?? '';
            $senha = $_POST["senha"] ?? '';
            $resultado = $adminController->login($email, $senha);
            
            if ($resultado) {
                header("Location: ./src/views/admin/telaInicialDoAdm.php");
                exit;
            } else {
                header("Location: ./src/views/admin/login-adm.php");
                exit;
            }
            break;

        case 'cadastrarLivro':
            require_once __DIR__ . "/src/controller/admin/CadastrarLivroController.php";
            $cadastrarLivroController = new CadastrarLivroController();
            $sucesso = $cadastrarLivroController->cadastrar();
            
            if (headers_sent()) {
                exit;
            }
            
            if ($sucesso) {
                header("Location: ./src/views/admin/telaDosLivrosCadastrados.php");
            } else {
                header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
            }
            exit;
            break;

        default:
            echo "Erro: Ação não reconhecida.";
            exit;
    }
} else {
    echo "Erro: Método não permitido para esta ação.";
    exit;
}
