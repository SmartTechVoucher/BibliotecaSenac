<?php
session_start();
require_once __DIR__ . "/src/controller/usuario/login-controller.php";
require_once __DIR__ . "/src/controller/admin/AdminController.php";

// Função helper para verificar auth admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']) && isset($_SESSION['admin']['id']);
}

if (!isset($_GET["acao"])) {
    echo "Erro: Nenhuma ação especificada.";
    exit;
}

$acao = $_GET["acao"];

if ($acao === 'auxEntity') {
    $isAjax = true; // auxEntity is always AJAX
    if (!isAdminLoggedIn()) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Acesso negado. Faça login como administrador.'
        ]);
        exit;
    }
    
    header('Content-Type: application/json; charset=utf-8');
    error_log('T1: Router auxEntity reached, tipo: ' . ($_GET['tipo'] ?? 'none'));
    
    try {
        require_once __DIR__ . "/src/controller/admin/AuxEntityController.php";
        if (!isset($_GET['tipo'])) {
            throw new Exception('Tipo de entidade não especificado.');
        }
        $tipo = $_GET['tipo'];
        $auxController = new AuxEntityController($tipo);
        $auxController->handle();
    } catch (Exception $e) {
        error_log('T2: Router auxEntity error: ' . $e->getMessage());
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno no controlador: ' . $e->getMessage()]);
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
            if (!isAdminLoggedIn()) {
                $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';
                header('Content-Type: application/json; charset=utf-8');
                if ($isAjax) {
                    echo json_encode([
                        'sucesso' => false,
                        'mensagem' => 'Acesso negado. Faça login como administrador.'
                    ]);
                    exit;
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Acesso negado. Faça login como administrador.',
                        'tipo' => 'error'
                    ];
                    header("Location: ./src/views/admin/login-adm.php");
                    exit;
                }
            }
            
            header('Content-Type: application/json; charset=utf-8');
            $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';
            
            try {
                require_once __DIR__ . "/src/controller/admin/CadastrarLivroController.php";
                $cadastrarLivroController = new CadastrarLivroController();
                $sucesso = $cadastrarLivroController->cadastrar();
                
                if ($isAjax) {
                    // Controller already outputs JSON for AJAX
                    exit;
                }
                
                if ($sucesso) {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Livro cadastrado com sucesso!',
                        'tipo' => 'success'
                    ];
                    header("Location: ./src/views/admin/telaDosLivrosCadastrados.php");
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Erro ao cadastrar livro.',
                        'tipo' => 'error'
                    ];
                    header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                }
                exit;
                
            } catch (Exception $e) {
                error_log('Router cadastrarLivro error: ' . $e->getMessage());
                if ($isAjax) {
                    echo json_encode([
                        'sucesso' => false,
                        'mensagem' => 'Erro interno no servidor: ' . $e->getMessage()
                    ]);
                    exit;
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Erro interno no servidor.',
                        'tipo' => 'error'
                    ];
                    header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                    exit;
                }
            }
            break;

        default:
            echo "Erro: Ação não reconhecida.";
            exit;
    }
} else {
    echo "Erro: Método não permitido para esta ação.";
    exit;
}
