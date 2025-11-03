<?php
/**
 * Página de Informações do Livro
 * Exibe detalhes completos do livro com sistema de avaliações
 */

session_start();
require_once "../../config/conexao.php";

// Verificar se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Obter ID do livro
$id_livro = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_livro <= 0) {
    header("Location: index.php");
    exit;
}

// Buscar informações completas do livro
$sql = "SELECT 
            l.*,
            a.nome as nome_autor,
            a.nacionalidade as nacionalidade_autor,
            c.nome as nome_categoria,
            u.nome as nome_unidade,
            d.nome as nome_documento,
            i.nome as nome_idioma,
            ar.nome as nome_area
        FROM livros l
        LEFT JOIN autores a ON l.id_autor = a.id_autor
        LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
        LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
        LEFT JOIN documentos d ON l.id_documento = d.id_documento
        LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
        LEFT JOIN areas ar ON l.id_area = ar.id_area
        WHERE l.id_livro = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$livro) {
    header("Location: index.php");
    exit;
}

// Buscar informações de exemplares
$sqlExemplares = "SELECT * FROM exemplares WHERE id_livro = ?";
$stmtExemplares = $conn->prepare($sqlExemplares);
$stmtExemplares->bind_param("i", $id_livro);
$stmtExemplares->execute();
$exemplares = $stmtExemplares->get_result()->fetch_assoc();
$stmtExemplares->close();

// Buscar estatísticas de avaliações
$sqlStats = "SELECT * FROM vw_estatisticas_livros WHERE id_livro = ?";
$stmtStats = $conn->prepare($sqlStats);
$stmtStats->bind_param("i", $id_livro);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();
$stmtStats->close();

// Definir disponibilidade
$disponivel = $exemplares && $exemplares['disponiveis'] > 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($livro['titulo']); ?> - Biblioteca Senac</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/usuario/livro-info.css">
    <link rel="stylesheet" href="../../public/css/components/usuario/header.css">
    <link rel="stylesheet" href="../../public/css/components/usuario/footer.css">
</head>

<body>
    <!-- Header -->
    <?php include "../../public/components/usuario/header.php"; ?>

    <!-- Conteúdo Principal -->
    <div class="containerConteudo">
        <!-- Botão Voltar -->
        <div class="voltar">
            <a href="index.php" class="btn-voltar">← Voltar ao Catálogo</a>
        </div>

        <!-- Informações do Livro -->
        <div class="containerInfo">
            <!-- Imagem do Livro -->
            <img id="livroFoto" 
                 src="<?php echo !empty($livro['foto']) ? $livro['foto'] : '../../public/assets/img/livro-padrao.png'; ?>" 
                 alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
            
            <div class="info_1">
                <div class="livroInfo">
                    <!-- Título e ISBN -->
                    <div class="livroTitulo">
                        <h1><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                        <p id="livroIsbn">
                            (<?php echo htmlspecialchars($livro['nome_documento']); ?> - ISBN: <?php echo htmlspecialchars($livro['isbn']); ?>)
                        </p>
                    </div>

                    <!-- Review/Avaliações -->
                    <div class="review">
                        <?php if ($stats && $stats['total_avaliacoes'] > 0): ?>
                            <img src="../../public/assets/icons/estrelas<?php echo $stats['media_arredondada']; ?>.png" 
                                 alt="<?php echo $stats['media_arredondada']; ?> estrelas">
                            <p>
                                <strong><?php echo $stats['total_avaliacoes']; ?></strong> 
                                <?php echo $stats['total_avaliacoes'] == 1 ? 'avaliação' : 'avaliações'; ?>
                                (Média: <strong><?php echo number_format($stats['media_notas'], 1, ',', '.'); ?></strong>)
                            </p>
                        <?php else: ?>
                            <p>Nenhuma avaliação ainda</p>
                        <?php endif; ?>
                    </div>

                    <!-- Tags/Categorias -->
                    <div class="tags">
                        <h3>Tags:</h3>
                        <div class="tags2">
                            <div class="tag_icone">
                                <p><?php echo htmlspecialchars($livro['nome_categoria']); ?></p>
                            </div>
                            <div class="tag_icone">
                                <p><?php echo htmlspecialchars($livro['nome_area']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <p id="livroDescricao">
                        <?php echo nl2br(htmlspecialchars($livro['descricao'] ?: 'Descrição não disponível.')); ?>
                    </p>

                    <!-- Botão de Reservar -->
                    <div class="livroReservar">
                        <p class="status-<?php echo $disponivel ? 'disponivel' : 'indisponivel'; ?>">
                            <?php echo $disponivel ? 'Disponível' : 'Indisponível'; ?>
                        </p>
                        <?php if ($disponivel): ?>
                            <button id="botaoReserva" onclick="reservarLivro(<?php echo $id_livro; ?>)">
                                Reservar
                            </button>
                        <?php else: ?>
                            <button id="botaoReserva" disabled style="background: #ccc; cursor: not-allowed;">
                                Sem Exemplares
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="info_2">
                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="../../public/assets/icons/User.png" alt="Autor">
                            <p><?php echo htmlspecialchars($livro['nome_autor']); ?></p>
                        </div>
                        
                        <div class="publicacao">
                            <h3>Publicação:</h3>
                            <img src="../../public/assets/icons/Geography.png" alt="Publicação">
                            <p>
                                <?php 
                                echo htmlspecialchars($livro['nome_unidade']); 
                                if ($livro['data_publicacao']) {
                                    echo ', ' . date('Y', strtotime($livro['data_publicacao']));
                                }
                                ?>
                            </p>
                        </div>
                        
                        <div class="paginas">
                            <h3>Páginas:</h3>
                            <img src="../../public/assets/icons/Read.png" alt="Páginas">
                            <p><?php echo $livro['numero_paginas']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplares -->
        <?php if ($exemplares): ?>
        <div class="containerExemplar">
            <p>Exemplares</p>
            <img src="../../public/assets/icons/Plus Math.png" 
                 alt="Expandir" 
                 id="abrirExemplares" 
                 onclick="alternarExemplar()">
        </div>

        <div id="containerExemplarOpen" style="display: none;">
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Total</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo htmlspecialchars($livro['nome_unidade']); ?></div>
                <div class="gridB"><?php echo $exemplares['total_exemplares']; ?></div>
                <div class="gridB"><?php echo $exemplares['disponiveis']; ?></div>
                <div class="gridB"><?php echo $exemplares['emprestados']; ?></div>
                <div class="gridB"><?php echo $exemplares['reservas']; ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Sistema de Comentários e Avaliações -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Avaliações e Comentários</p>
                <img src="../../public/assets/icons/Read.png" alt="">
            </div>
            
            <!-- Formulário de Avaliação -->
            <form id="commentForm" onsubmit="event.preventDefault();">
                <div class="inputRating" style="margin: 20px 0; display: flex; gap: 10px; align-items: center;">
                    <span style="margin-right: 10px; font-weight: bold;">Sua nota:</span>
                    <img class="estrela-input" src="../../public/assets/icons/livro-info-estrela.png" alt="1 estrela" data-value="1">
                    <img class="estrela-input" src="../../public/assets/icons/livro-info-estrela.png" alt="2 estrelas" data-value="2">
                    <img class="estrela-input" src="../../public/assets/icons/livro-info-estrela.png" alt="3 estrelas" data-value="3">
                    <img class="estrela-input" src="../../public/assets/icons/livro-info-estrela.png" alt="4 estrelas" data-value="4">
                    <img class="estrela-input" src="../../public/assets/icons/livro-info-estrela.png" alt="5 estrelas" data-value="5">
                </div>
                
                <textarea 
                    id="comentario-input" 
                    placeholder="Compartilhe sua opinião sobre este livro... (mínimo 10 caracteres)"
                    rows="4"
                    style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-family: inherit; resize: vertical;"
                ></textarea>
                
                <input type="hidden" name="rating" id="rating-value" value="0">
                
                <button 
                    type="button" 
                    id="comentario-botao"
                    style="margin-top: 10px; padding: 12px 30px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;"
                >
                    Enviar Avaliação
                </button>
            </form>

            <!-- Container de Avaliações -->
            <div id="reviewsContainer" style="margin-top: 30px;">
                <p style="text-align: center; color: #999;">Carregando avaliações...</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../../public/components/usuario/footer.php"; ?>

    <!-- Scripts -->
    <script>
        // Passar configurações para o JavaScript
        window.CONFIG = {
            ID_LIVRO: <?php echo $id_livro; ?>,
            API_URL: '../api/avaliacoes-api.php',
            BASE_URL: '../../'
        };

        // Função para alternar exemplares
        function alternarExemplar() {
            const container = document.getElementById('containerExemplarOpen');
            const icone = document.getElementById('abrirExemplares');
            
            if (container.style.display === 'none') {
                container.style.display = 'block';
                icone.style.transform = 'rotate(45deg)';
            } else {
                container.style.display = 'none';
                icone.style.transform = 'rotate(0deg)';
            }
        }

        // Função para reservar livro
        async function reservarLivro(idLivro) {
            if (!confirm('Deseja reservar este livro?')) return;
            
            try {
                const response = await fetch('../api/reservas-api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_livro: idLivro })
                });
                
                const data = await response.json();
                
                if (data.sucesso) {
                    alert('Livro reservado com sucesso!');
                    location.reload();
                } else {
                    alert('Erro: ' + data.erro);
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao reservar livro');
            }
        }
    </script>
    <script src="../../public/js/usuario/livro-info.js"></script>
</body>
</html>