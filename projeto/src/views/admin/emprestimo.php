<?php
require "../../../config/constantes.php"
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
    <?php 
        include "../../../public/components/admin/input/input-admin.php";
        include "../../../public/components/admin/button/button-admin.php";
        include "../../../public/components/admin/pesquisa/pesquisa-admin.php";
        require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; 
    ?>

</head>

<body>
    <?php
    include "../../../public/components/admin/header/header-admin.php"
    ?>

    <main>
        <div class="container-main">
            <fieldset class="form-section">
                <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">

                    <legend>
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/emprestimo-icon.png" id="fieldset-icon" alt="">
                        Cadastro de emprestimo
                    </legend>
                    <div class="form-row">
                        <label for="nome-usuario">Nome do usuário</label>
                        <?php
                        $usuariosMock = [
                            ['id' => 1, 'nome' => 'José da Silva', 'email' => 'jose.silva@email.com', 'matricula' => '2023001', 'acesso' => 'regular', 'telefone' => '67981234567'],
                            ['id' => 2, 'nome' => 'Ana Maria Santos', 'email' => 'ana.santos@email.com', 'matricula' => '2023002', 'acesso' => 'regular', 'telefone' => '67981234568'],
                            ['id' => 3, 'nome' => 'Pedro Oliveira', 'email' => 'pedro.oliveira@email.com', 'matricula' => '2023003', 'acesso' => 'bloqueado', 'telefone' => '67981234569'],
                            ['id' => 4, 'nome' => 'Fernanda Costa', 'email' => 'fernanda.costa@email.com', 'matricula' => '2023004', 'acesso' => 'regular', 'telefone' => '67981234570'],
                            ['id' => 5, 'nome' => 'Lucas Pereira', 'email' => 'lucas.pereira@email.com', 'matricula' => '2023005', 'acesso' => 'regular', 'telefone' => '67981234571'],
                            ['id' => 6, 'nome' => 'Mariana Almeida', 'email' => 'mariana.almeida@email.com', 'matricula' => '2023006', 'acesso' => 'regular', 'telefone' => '67981234572'],
                            ['id' => 7, 'nome' => 'Rafaela Martins', 'email' => 'rafaela.martins@email.com', 'matricula' => '2023007', 'acesso' => 'regular', 'telefone' => '67981234573'],
                            ['id' => 8, 'nome' => 'Guilherme Souza', 'email' => 'guilherme.souza@email.com', 'matricula' => '2023008', 'acesso' => 'regular', 'telefone' => '67981234574'],
                            ['id' => 9, 'nome' => 'Beatriz Ferreira', 'email' => 'beatriz.ferreira@email.com', 'matricula' => '2023009', 'acesso' => 'bloqueado', 'telefone' => '67981234575'],
                            ['id' => 10, 'nome' => 'Gabriel Rodrigues', 'email' => 'gabriel.rodrigues@email.com', 'matricula' => '2023010', 'acesso' => 'regular', 'telefone' => '67981234576'],
                            ['id' => 11, 'nome' => 'Juliana Gomes', 'email' => 'juliana.gomes@email.com', 'matricula' => '2023011', 'acesso' => 'regular', 'telefone' => '67981234577'],
                            ['id' => 12, 'nome' => 'Daniel Barbosa', 'email' => 'daniel.barbosa@email.com', 'matricula' => '2023012', 'acesso' => 'regular', 'telefone' => '67981234578'],
                            ['id' => 13, 'nome' => 'Carolina Lima', 'email' => 'carolina.lima@email.com', 'matricula' => '2023013', 'acesso' => 'regular', 'telefone' => '67981234579'],
                            ['id' => 14, 'nome' => 'Thiago Fernandes', 'email' => 'thiago.fernandes@email.com', 'matricula' => '2023014', 'acesso' => 'regular', 'telefone' => '67981234580'],
                            ['id' => 15, 'nome' => 'Isabela Rocha', 'email' => 'isabela.rocha@email.com', 'matricula' => '2023015', 'acesso' => 'regular', 'telefone' => '67981234581'],
                            ['id' => 16, 'nome' => 'Artur Nunes', 'email' => 'artur.nunes@email.com', 'matricula' => '2023016', 'acesso' => 'bloqueado', 'telefone' => '67981234582'],
                            ['id' => 17, 'nome' => 'Laura Dias', 'email' => 'laura.dias@email.com', 'matricula' => '2023017', 'acesso' => 'regular', 'telefone' => '67981234583'],
                            ['id' => 18, 'nome' => 'Felipe Castro', 'email' => 'felipe.castro@email.com', 'matricula' => '2023018', 'acesso' => 'regular', 'telefone' => '67981234584'],
                        ];
                        $nomesDeUsuarios = array_column($usuariosMock, 'nome');
                        //     renderSelectModal(name:"nome-usuario", label:"Nome do usuário", items:$usuariosMock)
                        // InputAdmin(largura: 100, placeholder: "Nome completo do usuário", id: "nome-usuario", name: "nome-usuario")
                        barra_de_pesquisa($nomesDeUsuarios);
                        ?>
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
                    </div>

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

                <div class="tabela-livro">
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
                </div>
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