# Biblioteca SENAC HUB ACADEMY - Testes

## Pré-requisitos
- PHP 8.2+
- Composer
- Apache + MySQL (XAMPP recomendado)

## Instalação
```bash
cd BibliotecaSenac/projeto
composer install
```

## Estrutura dos Testes
```
tests/
├── Unit/
│   ├── Controller/
│   │   ├── TesteControladorAdmin.php    # Testes admin/login
│   │   └── TesteControladorLogin.php    # Testes usuario/login
│   └── Model/
│       ├── TesteModeloLivro.php         # Model livros (15/15 OK)
│       ├── TesteModeloBaseDados.php    # Model database
│       └── TesteModeloAdmin.php
└── Integration/
    ├── TesteIntegracaoLogin.php        # Sistema completo login
    └── TesteRoteador.php
```

## Comandos de Teste

### Principais
```bash
# Modelos principais (Funcionando - 15/15 testes OK)
composer test-unit

# Controladores (Admin + Usuario)
composer test-controladores

# Sistema completo de login
composer test-integration-login

# Banco de dados
composer test-base-dados

# Todos os modelos juntos
composer test-modelos

# Execução completa
composer test
```

## O que Cada Teste Faz

### `composer test-unit`
- **Arquivo**: `TesteModeloLivro.php`
- **Testa**: Model de livros com dados mockados
- **Cobertura**: 15 testes funcionais
- **Status**: Funcionando perfeitamente
- **Resultado esperado**: "OK (15 tests, 269 assertions)"

### `composer test-controladores`
- **Arquivos**: `TesteControladorAdmin.php`, `TesteControladorLogin.php`
- **Testa**: Login admin e usuario + sessões + redirecionamentos
- **Cobertura**: Controllers + Models + Database
- **Dependências**: Tabelas `adminstrador` e `usuarios`

### `composer test-integration-login`
- **Arquivo**: `TesteIntegracaoLogin.php`
- **Testa**: Sistema completo de autenticação
- **Cobertura**: Detecção automática admin vs usuario
- **Redirecionamento**: telaInicialDoAdm.php ou index.php

### `composer test-base-dados`
- **Arquivo**: `TesteModeloBaseDados.php`
- **Testa**: Conexões database + estrutura PDO
- **Cobertura**: Propriedades privadas + queries
- **Schema**: Validação de tabelas MySQL

### `composer test-modelos`
- **Arquivos**: `TesteModeloBaseDados.php` + `TesteModeloLivro.php`
- **Testa**: Camada de dados completa
- **Cobertura**: Database + Business logic

## Como Executar Individualmente
```bash
# Teste específico
./vendor/bin/phpunit tests/Unit/Model/TesteModeloLivro.php

# Comandos específicos
composer test-base-dados         # Database apenas
composer test-integration-login  # Sistema login
composer test-unit              # Modelos principais
```

## Como Adicionar Novos Testes

### 1. Teste Unitário
```php
<?php
require_once __DIR__ . '/../../../src/model/exemplo/ExemploModel.php';

class TesteModeloExemplo extends TestCase
{
    private $modelo;

    protected function setUp(): void
    {
        $this->modelo = new ExemploModel();
    }

    public function testarFuncaoExemplo()
    {
        $resultado = $this->modelo->funcaoExemplo();
        $this->assertEquals('esperado', $resultado);
    }
}
```

### 2. Teste de Controller
```php
<?php
require_once __DIR__ . '/../../../src/controller/exemplo/ExemploController.php';
require_once __DIR__ . '/../../../src/model/exemplo/ExemploModel.php';

class TesteControladorExemplo extends TestCase
{
    private $modeloSimulado;

    public function testarAcaoController()
    {
        $this->modeloSimulado = $this->getMockBuilder(ExemploModel::class)
                                   ->getMock();

        $resultado = $this->controlador->acao();
        $this->assertTrue($resultado);
    }
}
```

### 3. Adicionar Comando no Composer
```json
// composer.json
{
    "scripts": {
        "test-exemplo": "./vendor/bin/phpunit tests/Unit/Model/TesteModeloExemplo.php",
        "test-controlador-exemplo": "./vendor/bin/phpunit tests/Unit/Controller/TesteControladorExemplo.php"
    }
}
```

### 4. Estrutura Recomendada
- ✅ Herança: `extends TestCase`
- ✅ Métodos: `public function testarNomeFuncao()`
- ✅ Linguagem: Português brasileiro
- ✅ Localização: `tests/Unit/Model/` ou `Controller/`
- ✅ Documentação: Comments explicativos

## Solução de Problemas

### Erros de Sessão
```bash
# Problema: "session_start() cannot be started"
# Solução: Limpar sessão entre testes
session_destroy();
$_SESSION = [];
```

### Headers Já Enviados
```bash
# Problema: "Cannot modify header information"
# Solução: Evitar redirecionamentos em testes
```

### PHPUnit com Problemas
```bash
# Verificar versão
./vendor/bin/phpunit --version

# Configuração
./vendor/bin/phpunit --configuration phpunit.xml
```

## Estrutura de Desenvolvimento
```text
1. Criar classe de teste
2. Escrever assertions claras
3. Adicionar comando no composer.json
4. Executar e validar
5. Atualizar este README
6. Commit no GitHub
```

Isso não é um test/unit case profissional do jeito que eu gostaria que fosse, mas, infelizmente o PC do senac não me deixa testar tudo corretamente, então isso foi um "freestyle" feito em um dia.