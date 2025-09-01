<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../src/model/usuario/livro-model.php';

class TesteModeloLivro extends TestCase
{
    private $modeloLivro;

    protected function setUp(): void
    {
        $this->modeloLivro = new LivroModel();
    }

    public function testarRetornaArray()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $this->assertIsArray($livros, '✅ ARRAY DE LIVROS RETORNADO');
        $this->assertNotEmpty($livros);

        echo "📚 TESTE: Modelo retornando array corretamente!\n";
    }

    public function testarQuantidadeCorretaLivros()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $this->assertCount(10, $livros, '✅ 10 LIVROS RETORNADOS');

        echo "📖 TESTE: Quantidade de livros correta (10)!\n";
    }

    public function testarCamposObrigatorios()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $this->assertArrayHasKey('id', $livro);
            $this->assertArrayHasKey('titulo', $livro);
            $this->assertArrayHasKey('autor', $livro);
            $this->assertArrayHasKey('status', $livro);
            $this->assertArrayHasKey('imagem', $livro);
            $this->assertArrayHasKey('descricao', $livro);

            $this->assertIsInt($livro['id']);
            $this->assertIsString($livro['titulo']);
            $this->assertIsString($livro['autor']);
            $this->assertIsString($livro['status']);
            $this->assertIsString($livro['imagem']);
            $this->assertIsString($livro['descricao']);
        }
    }

    public function testarPrimeiroLivro()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $primeiroLivro = $livros[0];

        $this->assertEquals(1, $primeiroLivro['id']);
        $this->assertEquals('O Senhor dos Anéis', $primeiroLivro['titulo']);
        $this->assertEquals('J.R.R. Tolkien', $primeiroLivro['autor']);
        $this->assertEquals('Disponível', $primeiroLivro['status']);
        $this->assertStringContainsString('covers.odilo.io', $primeiroLivro['imagem']);
    }

    public function testarUltimoLivro()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $ultimoLivro = end($livros);

        $this->assertEquals(10, $ultimoLivro['id']);
        $this->assertEquals('O Código Da Vinci', $ultimoLivro['titulo']);
        $this->assertEquals('Dan Brown', $ultimoLivro['autor']);
        $this->assertEquals('Indisponível', $ultimoLivro['status']);
    }

    public function testarIdsUnicos()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $ids = array_column($livros, 'id');
        $this->assertCount(count($ids), array_unique($ids));
    }

    public function testarIdsSequenciais()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        for ($i = 0; $i < count($livros); $i++) {
            $this->assertEquals($i + 1, $livros[$i]['id']);
        }
    }

    public function testarUrlsImagensValidas()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $this->assertNotEmpty($livro['imagem']);
            $this->assertStringContainsString('covers.odilo.io', $livro['imagem']);
        }
    }

    public function testarStatusValidos()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $statusValidos = ['Disponível', 'Indisponível'];

        foreach ($livros as $livro) {
            $this->assertContains($livro['status'], $statusValidos);
        }
    }

    public function testarTitulosNaoVazios()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $this->assertNotEmpty($livro['titulo']);
            $this->assertGreaterThan(0, strlen($livro['titulo']));
        }
    }

    public function testarAutoresNaoVazios()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $this->assertNotEmpty($livro['autor']);
            $this->assertGreaterThan(0, strlen($livro['autor']));
        }
    }

    public function testarDescricoesNaoVazias()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $this->assertNotEmpty($livro['descricao']);
            $this->assertGreaterThan(0, strlen($livro['descricao']));
        }
    }

    public function testarDadosConsistentes()
    {
        $livros1 = $this->modeloLivro->getLivrosMock();
        $livros2 = $this->modeloLivro->getLivrosMock();
        $this->assertEquals($livros1, $livros2);
    }

    public function testarLivrosEspecificos()
    {
        $livros = $this->modeloLivro->getLivrosMock();
        $mapaLivros = [];

        foreach ($livros as $livro) {
            $mapaLivros[$livro['titulo']] = $livro;
        }

        $this->assertArrayHasKey('O Senhor dos Anéis', $mapaLivros);
        $this->assertArrayHasKey('1984', $mapaLivros);
        $this->assertArrayHasKey('Dom Casmurro', $mapaLivros);
        $this->assertArrayHasKey('Harry Potter e a Pedra Filosofal', $mapaLivros);
        $this->assertArrayHasKey('O Hobbit', $mapaLivros);
    }

    public function testarTamanhoDescricoesRazoaveis()
    {
        $livros = $this->modeloLivro->getLivrosMock();

        foreach ($livros as $livro) {
            $tamanhoDescricao = strlen($livro['descricao']);
            $this->assertGreaterThan(10, $tamanhoDescricao);
            $this->assertLessThanOrEqual(200, $tamanhoDescricao);
        }
    }
}