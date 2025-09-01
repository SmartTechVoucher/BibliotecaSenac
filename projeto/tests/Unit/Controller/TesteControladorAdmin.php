<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
require_once __DIR__ . '/../../../src/controller/admin/AdminController.php';
require_once __DIR__ . '/../../../src/model/admin/AdminModel.php';

class TesteControladorAdmin extends TestCase
{
    /**
     * @var AdminModel|MockObject
     */
    private $modeloAdminSimulado;

    /**
     * @var AdminController
     */
    private $controladorAdmin;

    protected function setUp(): void
    {
        $this->modeloAdminSimulado = $this->getMockBuilder(AdminModel::class)
                                          ->disableOriginalConstructor()
                                          ->getMock();

        $reflexao = new ReflectionClass(AdminController::class);
        $propriedade = $reflexao->getProperty('adminModel');
        $propriedade->setAccessible(true);
        $propriedade->setValue(new AdminController(), $this->modeloAdminSimulado);

        $this->iniciarSessaoSeNecessario();

        $this->controladorAdmin = new AdminController();
        $propriedade->setValue($this->controladorAdmin, $this->modeloAdminSimulado);
    }

    private function iniciarSessaoSeNecessario(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    public function testarLoginComCredenciaisValidas()
    {
        $usuarioAdminEsperado = [
            'id_administrador' => 1,
            'nome' => 'Admin Test'
        ];

        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('admin123', 'senha123')
                                  ->willReturn($usuarioAdminEsperado);

        $resultado = $this->controladorAdmin->login('admin123', 'senha123');

        $this->assertTrue($resultado, '✅ LOGIN DE ADMIN COM SUCESSO');
        $this->assertEquals($usuarioAdminEsperado['id_administrador'], $_SESSION['usuario']['id']);
        $this->assertEquals($usuarioAdminEsperado['nome'], $_SESSION['usuario']['nome']);
        $this->assertEquals('admin', $_SESSION['usuario']['tipo']);
        $this->assertEquals('Login efetuado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('success', $_SESSION['toast']['tipo']);

        echo "🎉 TESTE: Login válido passou com sucesso!\n";
    }

    public function testarLoginComCredenciaisInvalidas()
    {
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('invalid_user', 'invalid_pass')
                                  ->willReturn(false);

        $resultado = $this->controladorAdmin->login('invalid_user', 'invalid_pass');

        $this->assertFalse($resultado, '❌ LOGIN INVÁLIDO CORRETAMENTE REJEITADO');
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);

        echo "🚫 TESTE: Login inválido rejeitado corretamente!\n";
    }

    public function testarLoginComNomeUsuarioVazio()
    {
        $resultado = $this->controladorAdmin->login('', 'password');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComSenhaVazia()
    {
        $resultado = $this->controladorAdmin->login('admin123', '');

        $this->assertFalse($resultado);
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComExcecaoDeBancoDeDados()
    {
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('admin123', 'senha123')
                                  ->willThrowException(new Exception('Database connection failed'));

        $resultado = $this->controladorAdmin->login('admin123', 'senha123');

        $this->assertFalse($resultado);
        $this->assertEquals('Erro interno no servidor.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }

    public function testarLoginComExcecaoThrowable()
    {
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('admin123', 'senha123')
                                  ->willThrowException(new Error('Critical error'));

        $resultado = $this->controladorAdmin->login('admin123', 'senha123');

        $this->assertFalse($resultado);
        $this->assertEquals('Erro interno no servidor.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);
    }
}

/* non-sql? (num funciona dps eu vejo pq) */