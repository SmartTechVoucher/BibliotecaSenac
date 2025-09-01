<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../../../config/db/database.php';


class TesteModeloBaseDados extends TestCase
{
    /**
     * @var Database
     */
    private $baseDados;

    protected function setUp(): void
    {
        $this->baseDados = new Database();
    }

    public function testarInstanciacaoBaseDados()
    {
        $this->assertInstanceOf(Database::class, $this->baseDados);
    }

    public function testarConexaoBaseDadosComSimulacao()
    {
        /** @var PDO|MockObject $pdoSimulado */
        $pdoSimulado = $this->getMockBuilder(PDO::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $reflexao = new ReflectionClass(Database::class);

        $this->assertTrue($reflexao->hasProperty('server'));
        $this->assertTrue($reflexao->hasProperty('dbname'));
        $this->assertTrue($reflexao->hasProperty('user'));
        $this->assertTrue($reflexao->hasProperty('pass'));

        $this->assertTrue($reflexao->hasMethod('Connect'));
    }

    public function testarPropriedadesBaseDadosConfiguradasCorretamente()
    {
        $reflexao = new ReflectionClass(Database::class);

        $propriedadeServidor = $reflexao->getProperty('server');
        $propriedadeServidor->setAccessible(true);
        $this->assertEquals('localhost', $propriedadeServidor->getValue($this->baseDados));

        $propriedadeNomeBaseDados = $reflexao->getProperty('dbname');
        $propriedadeNomeBaseDados->setAccessible(true);
        $this->assertEquals('bibliotecasenac', $propriedadeNomeBaseDados->getValue($this->baseDados));

        $propriedadeUsuario = $reflexao->getProperty('user');
        $propriedadeUsuario->setAccessible(true);
        $this->assertEquals('root', $propriedadeUsuario->getValue($this->baseDados));

        $propriedadeSenha = $reflexao->getProperty('pass');
        $propriedadeSenha->setAccessible(true);
        $this->assertEquals('', $propriedadeSenha->getValue($this->baseDados));
    }
}