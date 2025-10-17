<?php
require "../../../config/constantes.php";
// require_once "../../../controller/admin/EmprestimosController.php";
require_once __DIR__ . '/../../controller/admin/EmprestimosController.php';

$controller = new EmprestimosController();
$mensagem = "";
$usuarioEncontrado = null;
$livrosDisponiveis = [];

// Buscar usuário
if (isset($_POST['buscar_usuario'])) {
    $busca = trim($_POST['busca_usuario']);
    $usuarioEncontrado = $controller->buscarUsuario($busca);
    if (!$usuarioEncontrado) {
        $mensagem = "Usuário não encontrado.";
    } else {
        $livrosDisponiveis = $controller->listarLivrosDisponiveis();
    }
}

// Registrar empréstimo
if (isset($_POST['registrar_emprestimo'])) {
    $resultado = $controller->registrarEmprestimo($_POST['id_usuario'], $_POST['id_livro']);
    $mensagem = $resultado['mensagem'];
}

if (isset($_POST['renovar'])) {
    $resultado = $controller->renovarEmprestimo($_POST['id_movimentacao']);
    $mensagem = $resultado['mensagem'];
}

// 📦 Devolver livro
if (isset($_POST['devolver'])) {
    $resultado = $controller->devolverLivro($_POST['id_movimentacao']);
    $mensagem = $resultado['mensagem'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empréstimo</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/emprestimo.css">
    <link rel="stylesheet" href="../../../public/css/global.css">
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>
    <?php require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; ?>

</head>

<body>
    <?php
    include "../../../public/components/admin/header/header-admin.php"
    ?>

    <main>
        <div class="container-main">
            <fieldset class="form-section">
                <legend>
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/emprestimo-icon.png" id="fieldset-icon" alt="">
                    Cadastro de emprestimo
                </legend>
                <?php if ($mensagem): ?>
                    <p style="color: darkblue; font-weight: bold;"><?= $mensagem ?></p>
                <?php endif; ?>

                <form method="POST" style="margin-bottom: 20px;">
                    <label>Buscar usuário (nome ou CPF):</label>
                    <input type="text" name="busca_usuario" placeholder="Digite o nome ou CPF" required>
                    <button type="submit" name="buscar_usuario">Buscar</button>
                </form>

                <?php if ($usuarioEncontrado): ?>
                    <fieldset class="form-section">
                        <legend>Usuário encontrado</legend>
                        <p><strong>Nome:</strong> <?= htmlspecialchars($usuarioEncontrado['nome']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($usuarioEncontrado['email']) ?></p>
                        <p><strong>CPF:</strong> <?= htmlspecialchars($usuarioEncontrado['cpf']) ?></p>
                        <p><strong>Categoria:</strong> <?= htmlspecialchars($usuarioEncontrado['categoria']) ?></p>

                        <form method="POST">
                            <input type="hidden" name="id_usuario" value="<?= $usuarioEncontrado['id_usuario'] ?>">
                            <label>Selecione um livro disponível:</label>
                            <select name="id_livro" required>
                                <option value="">-- Escolha um livro --</option>
                                <?php foreach ($livrosDisponiveis as $livro): ?>
                                    <option value="<?= $livro['id_livro'] ?>">
                                        <?= $livro['titulo'] ?> (<?= $livro['autor'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <br><br>
                            <button type="submit" name="registrar_emprestimo">Registrar Empréstimo</button>
                        </form>
                    </fieldset>
                <?php endif; ?>

                <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">


                    <!-- <div class="form-row">
                        <label for="nome-usuario">Nome do usuário</label>
                        
                    </div>
                    <div class="form-row">
                        <fieldset id="card-usuario" class="form-section">
                            <legend>Dados do Usuário</legend>
                            <img src="<?php echo $URLBASE ?>/public/assets/img/NullUser.jpg" class="user-photo" alt="">
                            <div class="user-info">
                                <h2 class="user-name"><?php echo $usuariosMock[0]["nome"] ?></h2>
                                <p class="user-detail">Matrícula: <?php echo $usuariosMock[0]["matricula"] ?></p>
                                <p class="user-detail">Email: <?php echo $usuariosMock[0]["email"] ?></p>
                                <p class="user-detail">Telefone: <?php echo $usuariosMock[0]["telefone"] ?></p>
                                <p class="user-detail">Telefone: <?php echo $usuariosMock[0]["acesso"] ?></p>
                            </div>
                        </fieldset>
                    </div> -->

                    <div class="form-row">
                        <label for="livro-codigo">Livro código</label>
                        <?php
                        InputAdmin(largura: 100, placeholder: "Código de um item da biblioteca", id: "livro-codigo", name: "livro-codigo");
                        ?>
                    </div>




                </form>
                <?php
                // lista mock só pra apresentar na daily
                $livros = [
                    [
                        'id' => 1,
                        'titulo' => 'Dom Casmurro',
                        'codigo' => 'LIV-001',
                        'foto' => '/BibliotecaSenac/projeto/public/assets/img/livroCapa.jpg',
                        'data_emprestimo' => '10/09/2025',
                        'prazo_devolucao' => '24/09/2025',
                        'data_devolucao' => 'N/A'
                    ],
                    [
                        'id' => 2,
                        'titulo' => 'O Pequeno Príncipe',
                        'codigo' => 'LIV-002',
                        'foto' => '/BibliotecaSenac/projeto/public/assets/img/livroCapa.jpg',
                        'data_emprestimo' => '05/09/2025',
                        'prazo_devolucao' => '19/09/2025',
                        'data_devolucao' => 'N/A'
                    ],
                    [
                        'id' => 3,
                        'titulo' => '1984',
                        'codigo' => 'LIV-003',
                        'foto' => '/BibliotecaSenac/projeto/public/assets/img/livroCapa.jpg',
                        'data_emprestimo' => '01/09/2025',
                        'prazo_devolucao' => '15/09/2025',
                        'data_devolucao' => '15/09/2025'
                    ]
                ];
                ?>
                <?php if ($usuarioEncontrado): ?>
                    <?php
                    $emprestimos = $controller->listarEmprestimosUsuario($usuarioEncontrado['id_usuario']);
                    ?>
                    <?php if ($emprestimos && count($emprestimos) > 0): ?>
                        <h3>📚 Empréstimos Ativos do Usuário</h3>
                        <table border="1" cellpadding="6" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Data Empréstimo</th>
                                    <th>Devolução Prevista</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprestimos as $emp): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emp['titulo']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($emp['data_movimentacao'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($emp['data_prevista_devolucao'])) ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_movimentacao" value="<?= $emp['id_movimentacao'] ?>">
                                                <button type="submit" name="renovar">Renovar +3 dias</button>
                                            </form>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_movimentacao" value="<?= $emp['id_movimentacao'] ?>">
                                                <button type="submit" name="devolver">Devolver</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum empréstimo ativo para este usuário.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- <div class="tabela-livro">
                    <table class="tabela-emprestimos">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Título</th>
                                <th>Código</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($livros as $livro): ?>
                                <tr id="livro-<?= $livro['id']; ?>">
                                    <td><img src="<?= $livro['foto']; ?>" alt="Capa do livro <?= $livro['titulo']; ?>" class="livro-imagem"></td>
                                    <td><?= $livro['titulo']; ?></td>
                                    <td><?= $livro['codigo']; ?></td>
                                    
                                    <td class="botoes-acao">
                                        <button class="btn-abrir-mais" onclick="toggleDetalhes(<?= $livro['id']; ?>)">Abrir Mais</button>
                                        <button class="btn-renovar"><img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Renovar.png" alt="">Renovar</button>
                                        <button class="btn-devolver"><img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Devolver.png" alt="">Devolver</button>
                                    </td>

                                </tr>

                                <tr id="detalhes-livro-<?= $livro['id']; ?>" class="info-detalhes">
                                    <td colspan="4">
                                        <p><strong>Data de Empréstimo:</strong> <?= $livro['data_emprestimo']; ?></p>
                                        <p><strong>Prazo para Devolução:</strong> <?= $livro['prazo_devolucao']; ?></p>
                                        <p><strong>Data de Devolução:</strong> <?= $livro['data_devolucao']; ?></p>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div> -->
            </fieldset>
        </div>

    </main>

    <script src="<?php echo $URLBASE ?>/public/js/admin/emprestimo.js">

    </script>
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>


</body>

</html>