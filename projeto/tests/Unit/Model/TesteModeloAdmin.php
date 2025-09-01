<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../../../src/model/admin/AdminModel.php';

class TesteModeloAdmin extends TestCase
{
    /**
     * @var PDO|MockObject
     */
    private $pdoSimulado;

    /**
     * @var AdminModel
     */
    private $modeloAdmin;

    protected function setUp(): void
    {
        $this->pdoSimulado = $this->getMockBuilder(PDO::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->modeloAdmin = new AdminModel();

        $reflexao = new ReflectionClass(AdminModel::class);
        $propriedade = $reflexao->getProperty('conn');
        $propriedade->setAccessible(true);
        $propriedade->setValue($this->modeloAdmin, $this->pdoSimulado);
    }

    public function testarValidarLoginComCredenciaisValidas()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $dadosUsuario = [
            'id_administrador' => 1,
            'nome' => 'Admin Test',
            'cpf' => '12345678901',
            'email' => 'admin@test.com'
        ];

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->with('SELECT * FROM adminstrador WHERE nome = :nome AND senha = :senha')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->with([
                         ':nome' => 'admin123',
                         ':senha' => 'senha123'
                     ]);

        $stmtSimulado->expects($this->once())
                     ->method('fetch')
                     ->with(PDO::FETCH_ASSOC)
                     ->willReturn($dadosUsuario);

        $resultado = $this->modeloAdmin->validarLogin('admin123', 'senha123');

        $this->assertEquals($dadosUsuario, $resultado);
    }

    public function testarValidarLoginComCredenciaisInvalidas()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute');

        $stmtSimulado->expects($this->once())
                     ->method('fetch')
                     ->with(PDO::FETCH_ASSOC)
                     ->willReturn(false);

        $resultado = $this->modeloAdmin->validarLogin('invalid_user', 'invalid_pass');

        $this->assertFalse($resultado);
    }

    public function testarValidarLoginComNomeUsuarioVazio()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->with([
                         ':nome' => '',
                         ':senha' => 'password'
                     ]);

        $stmtSimulado->expects($this->once())
                     ->method('fetch')
                     ->willReturn(false);

        $resultado = $this->modeloAdmin->validarLogin('', 'password');

        $this->assertFalse($resultado);
    }

    public function testarValidarLoginComSenhaVazia()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->with([
                         ':nome' => 'admin123',
                         ':senha' => ''
                     ]);

        $stmtSimulado->expects($this->once())
                     ->method('fetch')
                     ->willReturn(false);

        $resultado = $this->modeloAdmin->validarLogin('admin123', '');

        $this->assertFalse($resultado);
    }

    public function testarValidarLoginComTentativaSQLInjection()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $nomeUsuarioMalicioso = "admin' OR '1'='1";
        $senhaMaliciosa = "password' OR '1'='1";

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->with([
                         ':nome' => $nomeUsuarioMalicioso,
                         ':senha' => $senhaMaliciosa
                     ]);

        $stmtSimulado->expects($this->once())
                     ->method('fetch')
                     ->willReturn(false);

        $resultado = $this->modeloAdmin->validarLogin($nomeUsuarioMalicioso, $senhaMaliciosa);

        $this->assertFalse($resultado);
    }

    public function testarValidarLoginComExcecaoBancoDados()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->willThrowException(new PDOException('Database connection failed'));

        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('Database connection failed');

        $this->modeloAdmin->validarLogin('admin123', 'senha123');
    }

    public function testarValidarLoginComErroCritico()
    {
        $stmtSimulado = $this->getMockBuilder(PDOStatement::class)->getMock();

        $this->pdoSimulado->expects($this->once())
                          ->method('prepare')
                          ->willReturn($stmtSimulado);

        $stmtSimulado->expects($this->once())
                     ->method('execute')
                     ->willThrowException(new Error('Critical system error'));

        $this->expectException(Error::class);
        $this->expectExceptionMessage('Critical system error');

        $this->modeloAdmin->validarLogin('admin123', 'senha123');
    }
}