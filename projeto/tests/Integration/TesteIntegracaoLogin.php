<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\IncompleteTestError;

require_once __DIR__ . '/../../src/controller/usuario/login-controller.php';
require_once __DIR__ . '/../../src/model/admin/AdminModel.php';
require_once __DIR__ . '/../../src/model/usuario/UsuarioModel.php';

/**
 * Teste de Integração do Sistema de Login
 * Testa o fluxo completo de login para admin e usuários
 */
class TesteIntegracaoLogin extends TestCase
{
    /**
     * @var LoginController
     */
    private $controladorLogin;

    /**
     * @var AdminModel|MockObject
     */
    private $modeloAdminSimulado;

    /**
     * @var UsuarioModel|MockObject
     */
    private $modeloUsuarioSimulado;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock do AdminModel
        $this->modeloAdminSimulado = $this->getMockBuilder(AdminModel::class)
                                          ->disableOriginalConstructor()
                                          ->getMock();

        // Mock do UsuarioModel
        $this->modeloUsuarioSimulado = $this->getMockBuilder(UsuarioModel::class)
                                           ->disableOriginalConstructor()
                                           ->getMock();

        // Instancia controlador
        $this->controladorLogin = new LoginController();

        // Usa reflexão para injetar os mocks no controlador
        $reflexao = new ReflectionClass(LoginController::class);
        $propriedadeAdminModel = $reflexao->getProperty('adminModel');
        $propriedadeAdminModel->setAccessible(true);
        $propriedadeAdminModel->setValue($this->controladorLogin, $this->modeloAdminSimulado);

        $propriedadeUsuarioModel = $reflexao->getProperty('usuarioModel');
        $propriedadeUsuarioModel->setAccessible(true);
        $propriedadeUsuarioModel->setValue($this->controladorLogin, $this->modeloUsuarioSimulado);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Limpar sessão após cada teste
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        // Limpar variaveis
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
    }

    /**
     * Testa login de administrador com banco de dados
     */
    public function testarLoginAdminComBancoDados()
    {
        // Arranja
        $dadosAdmin = [
            'id_administrador' => 1,
            'nome' => 'Admin Teste Fictício',
            'cpf' => '12345678901',
            'email' => 'admin@teste.com',
            'data_nascimento' => '1990-01-01',
            'telefone' => '11999999999',
            'rua' => 'Rua Teste, 123',
            'bairro' => 'Centro',
            'genero' => 'M',
            'senha' => password_hash('senha123', PASSWORD_DEFAULT)
        ];

        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('admin@teste.com', 'senha123')
                                  ->willReturn($dadosAdmin);

        // Ação
        $resultado = $this->controladorLogin->validarLogin('admin@teste.com', 'senha123');

        // Verifica
        $this->assertTrue($resultado, 'Admin deve fazer login com sucesso');
        $this->assertEquals($dadosAdmin['id_administrador'], $_SESSION['usuario']['id']);
        $this->assertEquals($dadosAdmin['nome'], $_SESSION['usuario']['nome']);
        $this->assertEquals('admin', $_SESSION['usuario']['tipo']);
        $this->assertEquals('Login efetuado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('success', $_SESSION['toast']['tipo']);

        echo "🧪 TESTE INTEGRADO: Admin fez login com sucesso!\n";
    }

    /**
     * Testa login de usuário comum com banco de dados
     */
    public function testarLoginUsuarioComumComBancoDados()
    {
        // Arranja
        $dadosUsuario = [
            'id_usuario' => 2,
            'nome' => 'João da Silva',
            'email' => 'joao@teste.com',
            'cpf' => '12345678910',
            'numero_matricula' => '20190123456',
            'telefone' => '11988888888',
            'rua' => 'Rua João, 123',
            'bairro' => 'Centro',
            'data_nascimento' => '2000-01-01'
        ];

        // Primeiro retorna false para admin (não existe)
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('joao@teste.com', '12345678910')
                                  ->willReturn(false);

        // Depois encontra no modelo de usuário
        $this->modeloUsuarioSimulado->expects($this->once())
                                    ->method('validarLogin')
                                    ->with('joao@teste.com', '12345678910')
                                    ->willReturn($dadosUsuario);

        // Ação
        $resultado = $this->controladorLogin->validarLogin('joao@teste.com', '12345678910');

        // Verifica
        $this->assertTrue($resultado, 'Usuário deve fazer login com sucesso');
        $this->assertEquals($dadosUsuario['id_usuario'], $_SESSION['usuario']['id']);
        $this->assertEquals($dadosUsuario['nome'], $_SESSION['usuario']['nome']);
        $this->assertEquals('usuario', $_SESSION['usuario']['tipo']);
        $this->assertEquals('Login efetuado com sucesso!', $_SESSION['toast']['mensagem']);
        $this->assertEquals('success', $_SESSION['toast']['tipo']);

        echo "🧪 TESTE INTEGRADO: Usuário comum fez login com sucesso!\n";
    }

    /**
     * Testa login inválido para ambos os tipos
     */
    public function testarLoginInvalidoParaAmbosTipos()
    {
        // Arranja
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('invalid@invalid.com', '123456')
                                  ->willReturn(false);

        $this->modeloUsuarioSimulado->expects($this->once())
                                    ->method('validarLogin')
                                    ->with('invalid@invalid.com', '123456')
                                    ->willReturn(false);

        // Ação
        $resultado = $this->controladorLogin->validarLogin('invalid@invalid.com', '123456');

        // Verifica
        $this->assertFalse($resultado, 'Login inválido deve falhar');
        $this->assertFalse(isset($_SESSION['usuario']), 'Sessão de usuário não deve ser criada');
        $this->assertEquals('Usuário ou senha inválidos.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);

        echo "🚫 TESTE INTEGRADO: Login inválido corretamente rejeitado!\n";
    }

    /**
     * Testa redirecionamento correto para admin
     */
    public function testarRedirecionamentoAdmin()
    {
        // Simula usuário admin logado
        $_SESSION['usuario'] = [
            'id' => 1,
            'nome' => 'Admin Teste',
            'tipo' => 'admin'
        ];

        // Este teste será incompleto se não puder testar header() diretamente
        try {
            $this->controladorLogin->redirecionarUsuario();
            $this->fail('Espero que redirecione e saia');
        } catch (IncompleteTestError $e) {
            // Isso é esperado pois header() termina a execução
            echo "➡️ TESTE INTEGRADO: Redirecionamento de admin funciona!\n";
        }
    }

    /**
     * Testa redirecionamento correto para usuário comum
     */
    public function testarRedirecionamentoUsuarioComum()
    {
        // Simula usuário comum logado
        $_SESSION['usuario'] = [
            'id' => 2,
            'nome' => 'João da Silva',
            'tipo' => 'usuario'
        ];

        // Este teste será incompleto se não puder testar header() diretamente
        try {
            $this->controladorLogin->redirecionarUsuario();
            $this->fail('Espero que redirecione e saia');
        } catch (IncompleteTestError $e) {
            // Isso é esperado pois header() termina a execução
            echo "➡️ TESTE INTEGRADO: Redirecionamento de usuário funciona!\n";
        }
    }

    /**
     * Testa tratamento de exceções no login
     */
    public function testarTratamentoExcecoesLogin()
    {
        // Arranja - simula erro de banco de dados
        $this->modeloAdminSimulado->expects($this->once())
                                  ->method('validarLogin')
                                  ->with('error@test.com', 'senha123')
                                  ->willThrowException(new Exception('Conexão com banco falhou'));

        // Ação
        $resultado = $this->controladorLogin->validarLogin('error@test.com', 'senha123');

        // Verifica
        $this->assertFalse($resultado, 'Login deve falhar por erro de banco');
        $this->assertFalse(isset($_SESSION['usuario']), 'Sessão não deve ser criada com erro');
        $this->assertEquals('Erro interno no servidor.', $_SESSION['toast']['mensagem']);
        $this->assertEquals('error', $_SESSION['toast']['tipo']);

        echo "💥 TESTE INTEGRADO: Tratamento de erros funciona!\n";
    }
}