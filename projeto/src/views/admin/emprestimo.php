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
            <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">
                <fieldset class="form-section">
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
                        //     renderSelectModal(name:"nome-usuario", label:"Nome do usuário", items:$usuariosMock)
                        InputAdmin(largura: 100, placeholder: "Nome completo do usuário", id: "nome-usuario", name: "nome-usuario")
                        ?>
                    </div>
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
                    <div class="form-row">
                        <label for="livro-codigo">Livro código</label>
                        <?php
                        InputAdmin(largura: 100, placeholder: "Código de um item da biblioteca", id: "livro-codigo", name: "livro-codigo");
                        ?>
                    </div>

                    <?php
                    // PHP array simulating data from a database
                    $livros = [
                        [
                            'foto' => 'https://via.placeholder.com/70x100?text=Livro+1',
                            'titulo' => 'A Arte da Guerra',
                            'codigo' => 'LIV001',
                            'dataEmprestimo' => '2025-09-01',
                            'prazoDevolucao' => '2025-09-15',
                            'dataDevolucao' => 'N/A'
                        ],
                        [
                            'foto' => 'https://via.placeholder.com/70x100?text=Livro+2',
                            'titulo' => 'O Príncipe',
                            'codigo' => 'LIV002',
                            'dataEmprestimo' => '2025-08-25',
                            'prazoDevolucao' => '2025-09-10',
                            'dataDevolucao' => '2025-09-09'
                        ],
                        [
                            'foto' => 'https://via.placeholder.com/70x100?text=Livro+3',
                            'titulo' => '1984',
                            'codigo' => 'LIV003',
                            'dataEmprestimo' => '2025-09-05',
                            'prazoDevolucao' => '2025-09-20',
                            'dataDevolucao' => 'N/A'
                        ]
                    ];
                    ?>
                    <div class="tabela-livro">
                        <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Título</th>
                                <th>Código</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="book-list">
                        </tbody>
                    </table>
                    </div>
                    

                    <script>
                        // Here, PHP generates a JavaScript variable directly in the HTML.
                        // `json_encode()` converts the PHP array into a valid JSON string.
                        const books = <?php echo json_encode($livros); ?>;

                        const bookList = document.getElementById('book-list');

                        books.forEach(book => {
                            const mainRow = document.createElement('tr');
                            mainRow.innerHTML = `
                <td><img src="${book.foto}" alt="Capa do livro ${book.titulo}" class="book-image"></td>
                <td>${book.titulo}</td>
                <td>${book.codigo}</td>
                <td><button class="toggle-btn" onclick="toggleDetails(this)">Abrir mais</button></td>
            `;
                            bookList.appendChild(mainRow);

                            const detailsRow = document.createElement('tr');
                            detailsRow.className = 'details-row';
                            detailsRow.innerHTML = `
                <td colspan="4">
                    <strong>Data de Empréstimo:</strong> ${book.dataEmprestimo}<br>
                    <strong>Prazo de Devolução:</strong> ${book.prazoDevolucao}<br>
                    <strong>Data de Devolução:</strong> ${book.dataDevolucao}
                </td>
            `;
                            bookList.appendChild(detailsRow);
                        });

                        function toggleDetails(button) {
                            const detailsRow = button.closest('tr').nextElementSibling;
                            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                                detailsRow.style.display = 'table-row';
                                button.textContent = 'Fechar';
                            } else {
                                detailsRow.style.display = 'none';
                                button.textContent = 'Abrir mais';
                            }
                        }
                    </script>

                </fieldset>

            </form>
        </div>
    </main>
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>


</body>

</html>