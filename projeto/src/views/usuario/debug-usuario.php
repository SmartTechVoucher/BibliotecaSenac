<?php
// Crie este arquivo: src/views/usuario/debug-usuario.php
// Acesse e veja TODOS os dados que vêm do banco

require(__DIR__ . '/../../../config/constantes.php');
require_once(__DIR__ . '/../../../config/auth-check.php');
protegerPagina();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Usuário</title></head><body>";
echo "<h1>Debug - Dados do Usuário Logado</h1>";
echo "<pre>";

echo "=== DADOS NA SESSÃO ===\n";
echo "usuario_id: " . ($_SESSION['usuario_id'] ?? 'NÃO DEFINIDO') . "\n";
echo "usuario_nome: " . ($_SESSION['usuario_nome'] ?? 'NÃO DEFINIDO') . "\n";
echo "usuario_email: " . ($_SESSION['usuario_email'] ?? 'NÃO DEFINIDO') . "\n";
echo "usuario_categoria: " . ($_SESSION['usuario_categoria'] ?? 'NÃO DEFINIDO') . "\n\n";

echo "=== DADOS DO BANCO (via obterUsuarioLogado) ===\n";
$usuario = obterUsuarioLogado();

if ($usuario) {
    echo "Campos retornados:\n";
    foreach ($usuario as $campo => $valor) {
        $valorExibir = $valor;
        if (in_array($campo, ['senha', 'password'])) {
            $valorExibir = '***OCULTO***';
        }
        echo "  $campo => " . (is_null($valorExibir) ? 'NULL' : "'$valorExibir'") . "\n";
    }
    
    echo "\n=== VERIFICAÇÃO DOS CAMPOS PROBLEMÁTICOS ===\n";
    echo "data_nascimento existe? " . (array_key_exists('data_nascimento', $usuario) ? 'SIM' : 'NÃO') . "\n";
    echo "data_nascimento valor: " . ($usuario['data_nascimento'] ?? 'NÃO EXISTE') . "\n";
    
    echo "nome_social existe? " . (array_key_exists('nome_social', $usuario) ? 'SIM' : 'NÃO') . "\n";
    echo "nome_social valor: " . ($usuario['nome_social'] ?? 'NÃO EXISTE') . "\n";
    
    echo "created_at existe? " . (array_key_exists('created_at', $usuario) ? 'SIM' : 'NÃO') . "\n";
    echo "created_at valor: " . ($usuario['created_at'] ?? 'NÃO EXISTE') . "\n";
    
    echo "criado_em existe? " . (array_key_exists('criado_em', $usuario) ? 'SIM' : 'NÃO') . "\n";
    echo "criado_em valor: " . ($usuario['criado_em'] ?? 'NÃO EXISTE') . "\n";
} else {
    echo "❌ ERRO: Nenhum dado retornado!\n";
}

echo "\n=== ESTRUTURA DA TABELA usuarios ===\n";
echo "Execute este SQL no phpMyAdmin para ver a estrutura:\n";
echo "DESCRIBE usuarios;\n";

echo "</pre>";
echo "<p><a href='minha-conta-usuario.php'>← Voltar para Minha Conta</a></p>";
echo "</body></html>";