<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../src/controller/admin/AdminController.php';
require_once __DIR__ . '/../../router/router.php';

class TesteRoteador extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        if (!defined('URLBASE')) {
            define('URLBASE', '/BibliotecaSenac/projeto');
        }

        if (!defined('PROJECT_ROOT')) {
            define('PROJECT_ROOT', __DIR__ . '/../../');
        }

        if (!defined('CONFIG_PATH')) {
            define('CONFIG_PATH', PROJECT_ROOT . '/config');
        }
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        unset($_POST, $_GET, $_SERVER);
    }

    public function testarLoginAdminComPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['acao'] = 'validarAdminLogin';
        $_POST['nome'] = 'admin123';
        $_POST['senha'] = 'senha_invalida';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEquals('Nome ou senha incorretos!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('erro', $_SESSION['toast']['tipo']);
    }

    public function testarLoginAdminComGetDeveRedirecionar()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['acao'] = 'validarAdminLogin';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertFalse(isset($_SESSION['toast']));
    }

    public function testarLoginUsuarioComPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['acao'] = 'validarLogin';
        $_POST['email'] = 'teste@exemplo.com';
        $_POST['senha'] = 'teste123';

        $_SESSION = [];
        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();
    }

    public function testarAcaoLogout()
    {
        $_GET['acao'] = 'logout';

        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_nome'] = 'Usuario Teste';
        $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => 'Mensagem'];

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEquals('Logout realizado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('sucesso', $_SESSION['toast']['tipo']);
        $this->assertFalse(isset($_SESSION['usuario_id']));
        $this->assertFalse(isset($_SESSION['usuario_nome']));
    }

    public function testarAcaoInvalidaDeveRedirecionar()
    {
        $_GET['acao'] = 'acao_invalida';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEmpty($_SESSION);
    }

    public function testarSemParametroAcaoDeveRedirecionar()
    {
        unset($_GET['acao']);

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEmpty($_SESSION);
    }

    public function testarValidacaoEntradaUsuario()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['acao'] = 'validarAdminLogin';
        $_POST['nome'] = '';
        $_POST['senha'] = 'teste123';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEquals('Nome e senha são obrigatórios!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('erro', $_SESSION['toast']['tipo']);
    }

    public function testarValidacaoEntradaComSenhaVazia()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['acao'] = 'validarAdminLogin';
        $_POST['nome'] = 'admin123';
        $_POST['senha'] = '';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEquals('Nome e senha são obrigatórios!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('erro', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComEntradasVazias()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['acao'] = 'validarLogin';
        $_POST['email'] = '';
        $_POST['senha'] = '';

        ob_start();
        include __DIR__ . '/../../router/router.php';
        $output = ob_get_clean();

        $this->assertEquals('Email e senha são obrigatórios!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('erro', $_SESSION['toast']['tipo']);
    }
}