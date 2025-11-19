<?php
require_once 'config/constantes.php';

echo "<h3>Teste de Foto de Perfil</h3>";

$nomeArquivo = "perfil_68cacccbaccc5.png";
$caminhoCompleto = rtrim($URLBASE, '/') . '/uploads/perfil/' . $nomeArquivo;

echo "<p><strong>URLBASE:</strong> " . $URLBASE . "</p>";
echo "<p><strong>Nome do arquivo:</strong> " . $nomeArquivo . "</p>";
echo "<p><strong>URL completa:</strong> " . $caminhoCompleto . "</p>";

// Verifica se o arquivo existe fisicamente
$caminhoFisico = __DIR__ . '/uploads/perfil/' . $nomeArquivo;
echo "<p><strong>Caminho físico:</strong> " . $caminhoFisico . "</p>";
echo "<p><strong>Arquivo existe:</strong> " . (file_exists($caminhoFisico) ? 'SIM' : 'NÃO') . "</p>";

if (file_exists($caminhoFisico)) {
    echo "<p><strong>Tamanho do arquivo:</strong> " . filesize($caminhoFisico) . " bytes</p>";
}

echo "<hr>";
echo "<p>Teste da imagem:</p>";
echo "<img src='" . $caminhoCompleto . "' alt='Teste' style='width:100px;height:100px;border-radius:50%;border:2px solid #003162;'>";
echo "<br><br>";
echo "<p>Se a imagem aparecer acima, o problema não é de caminho.</p>";
?>