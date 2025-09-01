<?php

class UtilitariosTeste
{
    public static function iniciarSessaoTeste()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
    }

    public static function limparSessao()
    {
        $_SESSION = [];
    }

    public static function criarPDOsimulado($casoTeste)
    {
        return $casoTeste->getMockBuilder(PDO::class)
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    public static function criarPDOStatementSimulado($casoTeste)
    {
        return $casoTeste->getMockBuilder(PDOStatement::class)->getMock();
    }

    public static function obterDadosUsuarioAdminExemplo()
    {
        return [
            'id_administrador' => 1,
            'nome' => 'Admin Teste',
            'cpf' => '12345678901',
            'email' => 'admin@teste.com',
            'data_nascimento' => '1990-01-01',
            'telefone' => '11999999999',
            'rua' => 'Rua Teste, 123',
            'bairro' => 'Centro',
            'genero' => 'M',
            'senha' => 'senha_hasheada'
        ];
    }

    public static function obterDadosUsuarioExemplo()
    {
        return [
            'id' => 1,
            'nome' => 'João da Silva',
            'email' => 'joao@teste.com',
            'cpf' => '12345678910',
            'matricula' => '20190123456'
        ];
    }

    public static function configurarRequisicao($metodo = 'GET', $dadosPost = [], $dadosGet = [])
    {
        $_SERVER['REQUEST_METHOD'] = $metodo;
        $_POST = $dadosPost;
        $_GET = $dadosGet;

        if (!defined('URLBASE')) {
            define('URLBASE', '/BibliotecaSenac/projeto');
        }
    }

    public static function limparEstadoGlobal()
    {
        unset($_POST, $_GET, $_SERVER);
        self::limparSessao();
    }

    public static function gerarStringAleatoria($tamanho = 10)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tamanhoCaracteres = strlen($caracteres);
        $stringAleatoria = '';

        for ($i = 0; $i < $tamanho; $i++) {
            $stringAleatoria .= $caracteres[rand(0, $tamanhoCaracteres - 1)];
        }

        return $stringAleatoria;
    }

    public static function afirmarMensagemToast($casoTeste, $mensagemEsperada, $tipoEsperado = 'success')
    {
        $casoTeste->assertArrayHasKey('toast', $_SESSION);
        $casoTeste->assertEquals($mensagemEsperada, $_SESSION['toast']['mensagem']);
        $casoTeste->assertEquals($tipoEsperado, $_SESSION['toast']['tipo']);
    }

    public static function afirmarDadosUsuarioSessao($casoTeste, $dadosUsuarioEsperados)
    {
        $casoTeste->assertEquals($dadosUsuarioEsperados['id'], $_SESSION['usuario']['id']);
        $casoTeste->assertEquals($dadosUsuarioEsperados['nome'], $_SESSION['usuario']['nome']);

        if (isset($dadosUsuarioEsperados['tipo'])) {
            $casoTeste->assertEquals($dadosUsuarioEsperados['tipo'], $_SESSION['usuario']['tipo']);
        }
    }

    public static function obterDadosLivrosExemplo()
    {
        return [
            [
                'id' => 1,
                'titulo' => 'Livro Teste',
                'autor' => 'Autor Teste',
                'status' => 'Disponível',
                'imagem' => 'teste_imagem.jpg',
                'descricao' => 'test'
            ],
            [
                'id' => 2,
                'titulo' => 'Outro Livro Teste',
                'autor' => 'Outro Autor',
                'status' => 'Indisponível',
                'imagem' => 'outra_imagem.jpg',
                'descricao' => 'Outra descrição de teste'
            ]
        ];
    }
}