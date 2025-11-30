<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/db/database.php';
require_once __DIR__ . '/../../model/admin/RelatoriosModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('America/Campo_Grande');

// -------------------------
// Captura filtros do formulário
// -------------------------
$genero  = $_POST['genero'] ?? '';
$formato = $_POST['formato'] ?? 'PDF';
$inicio  = $_POST['inicio'] ?? '1900-01-01';
$fim     = $_POST['fim'] ?? date('Y-m-d');
$ordenar = $_POST['ordenagem'] ?? '';

if (!$genero) exit("Selecione um gênero para gerar o relatório.");

// -------------------------
// Busca dados usando RelatorioModel
// -------------------------
$model = new RelatorioModel();
switch ($genero) {
    case "acervo":
        $dados = $model->getAcervo($inicio, $fim, $ordenar);
        $campoData = 'data';
        break;
    case "emprestimos":
        $dados = $model->getEmprestimos($inicio, $fim, $ordenar);
        $campoData = 'data';
        break;
    case "usuarios":
        $dados = $model->getUsuarios($inicio, $fim, $ordenar);
        $campoData = 'data';
        break;
    default:
        exit("Gênero inválido.");
}

// -------------------------
// Se escolher Excel
// -------------------------
if (strtoupper($formato) === 'EXCEL') {
    $filename = "relatorio_{$genero}.xls"; // extensão .xls

    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Cache-Control: max-age=0");

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr style='background-color:#2A4D8F; color:white; font-weight:bold;'>
            <th>Nome</th>
            <th>Data</th>
            <th>Status</th>
          </tr>";

    foreach ($dados as $linha) {
        $data = isset($linha[$campoData]) && $linha[$campoData] != 'Sem data' ? date('d/m/Y', strtotime($linha[$campoData])) : '-';
        $status = isset($linha['status']) && $linha['status'] !== '' ? $linha['status'] : '-';
        echo "<tr>
                <td>{$linha['nome']}</td>
                <td>{$data}</td>
                <td>{$status}</td>
              </tr>";
    }

    echo "</table>";
    exit;
}


// -------------------------
// Se escolher PDF
// -------------------------

// Monta linhas da tabela
$linhas = "";
if (empty($dados)) {
    $linhas = "<tr><td colspan='3' style='text-align:center;'>Nenhum registro encontrado.</td></tr>";
} else {
    foreach ($dados as $linha) {
        $dataFormatada = isset($linha[$campoData]) && $linha[$campoData] != 'Sem data' ? date('d/m/Y', strtotime($linha[$campoData])) : '-';
        $linhas .= "<tr><td>{$linha['nome']}</td><td>$dataFormatada</td><td>{$linha['status']}</td></tr>";
    }
}

// Logo base64
$logoPath = __DIR__ . '/../../../public/assets/icons/logo-hub-academy.png';
if (!file_exists($logoPath)) exit("Logo não encontrada.");
$logoSrc = "data:image/png;base64," . base64_encode(file_get_contents($logoPath));

// CSS e HTML
$css = "
<style>
body { font-family: DejaVu Sans, sans-serif; margin:50px 40px 70px 40px; }
header { overflow:hidden; padding-bottom:10px; margin-bottom:20px; border-bottom:2px solid #2A4D8F; }
header img { height:60px; float:left; margin-right:15px; }
header div { overflow:hidden; }
header h1 { margin:0; color:#2A4D8F; font-size:24px; }
header h2 { margin:0; font-size:14px; color:#555; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th { background:#2A4D8F; color:white; padding:8px; text-align:center; }
td { border:1px solid #ccc; padding:6px; text-align:center; }
</style>
";

$html = "
$css
<header>
    <img src='$logoSrc' alt='Logo'>
    <div>
        <h1>Senac Hub Academy - Relatório</h1>
        <h2>Cidade: Campo Grande - MS</h2>
        <h2>Tipo de Relatório: ".ucfirst($genero)."</h2>
    </div>
</header>

<p><strong>Período:</strong> ".date('d/m/Y', strtotime($inicio))." até ".date('d/m/Y', strtotime($fim))."</p>
<p><strong>Ordenação:</strong> $ordenar</p>

<table>
<tr><th>Nome</th><th>Data</th><th>Status</th></tr>
$linhas
</table>
";

// Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$pdf = new Dompdf($options);
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();

// Footer com hora e número da página
$canvas = $pdf->getCanvas();
$canvas->page_script(function($pageNumber, $pageCount, $canvas, $fontMetrics){
    $font = $fontMetrics->get_font("DejaVu Sans", "normal");
    $tamanho = 10;
    $largura = $canvas->get_width();
    $altura = $canvas->get_height();
    $texto = "Relatório emitido pelo sistema Senac Hub Academy em ".date('d/m/Y H:i:s')." | Página $pageNumber de $pageCount";
    $x = ($largura - $fontMetrics->getTextWidth($texto, $font, $tamanho))/2;
    $y = $altura - 30;
    $canvas->text($x, $y, $texto, $font, $tamanho);
});

// Envia PDF
$pdf->stream("relatorio_{$genero}.pdf", ["Attachment"=>true]);
exit;
