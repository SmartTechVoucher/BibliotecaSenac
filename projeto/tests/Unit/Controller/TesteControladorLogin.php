<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../src/controller/usuario/login-controller.php';

class TesteControladorLogin extends TestCase
{
    /**
     * @var LoginController
     */
    private $controladorLogin;

    protected function setUp(): void
    {
        $this->controladorLogin = new LoginController();
    }

    public function testarLoginComCredenciaisAdminValidas()
    {
        $resultado = $this->controladorLogin->ValidarLogin('admin123', '2020');

        $this->assertTrue($resultado);
        $this->assertEquals(0, $_SESSION['usuario']['id']);
        $this->assertEquals('Administrador', $_SESSION['usuario']['nome']);
        $this->assertEquals('Login efetuado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('success', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComCredenciaisUsuarioValidas()
    {
        $resultado = $this->controladorLogin->ValidarLogin('12345678910', '2020');

        $this->assertTrue($resultado);
        $this->assertEquals(1, $_SESSION['usuario']['id']);
        $this->assertEquals('João da Silva', $_SESSION['usuario']['nome']);
        $this->assertEquals('Login efetuado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('success', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComCredenciaisInvalidas()
    {
        $resultado = $this->controladorLogin->ValidarLogin('invalid_user', 'invalid_pass');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComNomeUsuarioVazio()
    {
        $resultado = $this->controladorLogin->ValidarLogin('', 'password');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComSenhaVazia()
    {
        $resultado = $this->controladorLogin->ValidarLogin('username', '');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComAmbosVazios()
    {
        $resultado = $this->controladorLogin->ValidarLogin('', '');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComCredenciaisNumericas()
    {
        $resultado = $this->controladorLogin->ValidarLogin('123456789', '2020');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginCaseSensitivityParaAdmin()
    {
        $resultado = $this->controladorLogin->ValidarLogin('ADMIN123', '2020');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
    }

    public function testarLoginCaseSensitivityParaUsuario()
    {
        $resultado = $this->controladorLogin->ValidarLogin('João da Silva', '2020');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
    }

    public function testarLoginComSenhaIncorretaParaAdmin()
    {
        $resultado = $this->controladorLogin->ValidarLogin('admin123', 'wrong_pass');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComSenhaIncorretaParaUsuario()
    {
        $resultado = $this->controladorLogin->ValidarLogin('12345678910', 'wrong_pass');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComCaracteresEspeciais()
    {
        $resultado = $this->controladorLogin->ValidarLogin('admin@123#$', '2020!@#');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }
}