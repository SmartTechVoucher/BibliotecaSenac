<script src="<?php echo $URLBASE ?>/public/js/admin/emprestimo.js"></script>
    <script>
        // Busca de leitores com autocomplete
        const inputUsuario = document.getElementById('nome-usuario');
        const sugestoesDiv = document.getElementById('sugestoes-usuarios');
        const leitorIdInput = document.getElementById('leitor-id');
        const cardUsuario = document.getElementById('card-usuario');
        
        let timeoutId;

        inputUsuario.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();
            
            if (query.length < 2) {
                sugestoesDiv.style.display = 'none';
                return;
            }
            
            timeoutId = setTimeout(() => {
                fetch(`../../../src/controller/buscar-usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            sugestoesDiv.innerHTML = data.map(user => 
                                `<div class="sugestao-item" data-id="${user.id}" data-nome="${user.nome}" 
                                 data-email="${user.email}" data-matricula="${user.matricula}" 
                                 data-telefone="${user.telefone}" data-acesso="${user.acesso}">
                                    <strong>${user.nome}</strong> - ${user.matricula}
                                </div>`
                            ).join('');
                            sugestoesDiv.style.display = 'block';
                            
                            // Adicionar eventos de clique
                            document.querySelectorAll('.sugestao-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    selecionarUsuario(this);
                                });
                            });
                        } else {
                            sugestoesDiv.innerHTML = '<div class="sugestao-item">Nenhum leitor encontrado</div>';
                            sugestoesDiv.style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }, 300);
        });

        function selecionarUsuario(element) {
            const id = element.getAttribute('data-id');
            const nome = element.getAttribute('data-nome');
            const email = element.getAttribute('data-email');
            const matricula = element.getAttribute('data-matricula');
            const telefone = element.getAttribute('data-telefone');
            const acesso = element.getAttribute('data-acesso');
            
            inputUsuario.value = nome;
            leitorIdInput.value = id;
            sugestoesDiv.style.display = 'none';
            
            // Preencher card do leitor
            document.getElementById('display-nome').textContent = nome;
            document.getElementById('display-matricula').textContent = matricula;
            document.getElementById('display-email').textContent = email;
            document.getElementById('display-telefone').textContent = telefone;
            document.getElementById('display-acesso').textContent = acesso;
            cardUsuario.style.display = 'block';
            
            // Carregar empréstimos do leitor
            carregarEmprestimos(id);
        }

        function carregarEmprestimos(leitorId) {
            fetch(`../../../src/controller/buscar-emprestimos.php?leitor_id=${leitorId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tabela-emprestimos-body');
                    
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">Nenhum empréstimo encontrado para este leitor</td></tr>';
                        return;
                    }
                    
                    tbody.innerHTML = data.map(emp => {
                        const statusClass = emp.status === 'devolvido' ? 'status-devolvido' : 'status-ativo';
                        const botoesAcao = emp.status !== 'devolvido' ? 
                            `<button class="btn-renovar" onclick="renovarEmprestimo(${emp.id})">
                                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Renovar.png" alt="">Renovar
                            </button>
                            <button class="btn-devolver" onclick="devolverLivro(${emp.id})">
                                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Devolver.png" alt="">Devolver
                            </button>` : '';
                        
                        return `
                            <tr id="livro-${emp.id}">
                                <td><img src="${emp.foto}" alt="Capa do livro ${emp.titulo}" class="livro-imagem"></td>
                                <td>${emp.titulo}</td>
                                <td>${emp.codigo}</td>
                                <td><span class="${statusClass}">${emp.status.charAt(0).toUpperCase() + emp.status.slice(1)}</span></td>
                                <td class="botoes-acao">
                                    <button class="btn-abrir-mais" onclick="toggleDetalhes(${emp.id})">Abrir Mais</button>
                                    ${botoesAcao}
                                </td>
                            </tr>
                            <tr id="detalhes-livro-${emp.id}" class="info-detalhes">
                                <td colspan="5">
                                    <p><strong>Data de Empréstimo:</strong> ${formatarData(emp.data_emprestimo)}</p>
                                    <p><strong>Prazo para Devolução:</strong> ${formatarData(emp.prazo_devolucao)}</p>
                                    <p><strong>Data de Devolução:</strong> ${emp.data_devolucao ? formatarData(emp.data_devolucao) : 'N/A'}</p>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => console.error('Erro:', error));
        }

        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleDateString('pt-BR');
        }

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-row')) {
                sugestoesDiv.style.display = 'none';
            }
        });
    </script><?php
require "../../../config/constantes.php";
require_once "conexao.php";
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
                <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">

                    
                    <div class="form-row">
                        <label for="nome-usuario">Nome do Leitor</label>
                        <?php
                        InputAdmin(largura: 100, placeholder: "Digite para buscar leitor", id: "nome-usuario", name: "nome-usuario")
                        ?>
                        <input type="hidden" id="leitor-id" name="leitor-id">
                        <div id="sugestoes-usuarios" class="sugestoes-lista" style="display: none;"></div>
                    </div>

                    <div class="form-row">
                        <fieldset id="card-usuario" class="form-section" style="display: none;">
                            <legend>Dados do Leitor</legend>
                            <img src="<?php echo $URLBASE ?>/public/assets/img/NullUser.jpg" class="user-photo" alt="">
                            <div class="user-info">
                                <h2 class="user-name" id="display-nome"></h2>
                                <p class="user-detail">Matrícula: <span id="display-matricula"></span></p>
                                <p class="user-detail">Email: <span id="display-email"></span></p>
                                <p class="user-detail">Telefone: <span id="display-telefone"></span></p>
                                <p class="user-detail">Status: <span id="display-acesso"></span></p>
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
                // Buscar empréstimos do leitor selecionado (se houver)
                $emprestimos = [];
                if (isset($_GET['leitor_id']) && !empty($_GET['leitor_id'])) {
                    $leitor_id = intval($_GET['leitor_id']);
                    
                    $sql = "SELECT e.*, l.titulo, l.codigo, l.foto 
                            FROM emprestimos e 
                            INNER JOIN livros l ON e.livro_id = l.id 
                            WHERE e.leitor_id = ? 
                            ORDER BY e.data_emprestimo DESC";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $leitor_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()) {
                        $emprestimos[] = $row;
                    }
                }
                ?>

                <div class="tabela-livro">
                    <table class="tabela-emprestimos">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Título</th>
                                <th>Código</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-emprestimos-body">
                            <?php if (empty($emprestimos)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px;">
                                        Selecione um leitor para ver seus empréstimos
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($emprestimos as $emp): ?>
                                    <tr id="livro-<?= $emp['id']; ?>">
                                        <td><img src="<?= $emp['foto'] ?? $URLBASE . '/public/assets/img/livroCapa.jpg'; ?>" alt="Capa do livro <?= $emp['titulo']; ?>" class="livro-imagem"></td>
                                        <td><?= $emp['titulo']; ?></td>
                                        <td><?= $emp['codigo']; ?></td>
                                        <td>
                                            <?php 
                                            $status = $emp['status'] ?? 'ativo';
                                            $status_class = $status == 'devolvido' ? 'status-devolvido' : 'status-ativo';
                                            echo "<span class='$status_class'>" . ucfirst($status) . "</span>";
                                            ?>
                                        </td>
                                        <td class="botoes-acao">
                                            <button class="btn-abrir-mais" onclick="toggleDetalhes(<?= $emp['id']; ?>)">Abrir Mais</button>
                                            <?php if ($status != 'devolvido'): ?>
                                                <button class="btn-renovar" onclick="renovarEmprestimo(<?= $emp['id']; ?>)">
                                                    <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Renovar.png" alt="">Renovar
                                                </button>
                                                <button class="btn-devolver" onclick="devolverLivro(<?= $emp['id']; ?>)">
                                                    <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Devolver.png" alt="">Devolver
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr id="detalhes-livro-<?= $emp['id']; ?>" class="info-detalhes">
                                        <td colspan="5">
                                            <p><strong>Data de Empréstimo:</strong> <?= date('d/m/Y', strtotime($emp['data_emprestimo'])); ?></p>
                                            <p><strong>Prazo para Devolução:</strong> <?= date('d/m/Y', strtotime($emp['prazo_devolucao'])); ?></p>
                                            <p><strong>Data de Devolução:</strong> <?= $emp['data_devolucao'] ? date('d/m/Y', strtotime($emp['data_devolucao'])) : 'N/A'; ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>

    </main>
    
    <script src="<?php echo $URLBASE ?>/public/js/admin/emprestimo.js"></script>
    <script>
        // Busca de usuários com autocomplete
        const inputUsuario = document.getElementById('nome-usuario');
        const sugestoesDiv = document.getElementById('sugestoes-usuarios');
        const usuarioIdInput = document.getElementById('usuario-id');
        const cardUsuario = document.getElementById('card-usuario');
        
        let timeoutId;

        inputUsuario.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();
            
            if (query.length < 2) {
                sugestoesDiv.style.display = 'none';
                return;
            }
            
            timeoutId = setTimeout(() => {
                fetch(`../../../src/controller/buscar-usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            sugestoesDiv.innerHTML = data.map(user => 
                                `<div class="sugestao-item" data-id="${user.id}" data-nome="${user.nome}" 
                                 data-email="${user.email}" data-matricula="${user.matricula}" 
                                 data-telefone="${user.telefone}" data-acesso="${user.acesso}">
                                    <strong>${user.nome}</strong> - ${user.matricula}
                                </div>`
                            ).join('');
                            sugestoesDiv.style.display = 'block';
                            
                            // Adicionar eventos de clique
                            document.querySelectorAll('.sugestao-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    selecionarUsuario(this);
                                });
                            });
                        } else {
                            sugestoesDiv.innerHTML = '<div class="sugestao-item">Nenhum usuário encontrado</div>';
                            sugestoesDiv.style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }, 300);
        });

        function selecionarUsuario(element) {
            const id = element.getAttribute('data-id');
            const nome = element.getAttribute('data-nome');
            const email = element.getAttribute('data-email');
            const matricula = element.getAttribute('data-matricula');
            const telefone = element.getAttribute('data-telefone');
            const acesso = element.getAttribute('data-acesso');
            
            inputUsuario.value = nome;
            usuarioIdInput.value = id;
            sugestoesDiv.style.display = 'none';
            
            // Preencher card do usuário
            document.getElementById('display-nome').textContent = nome;
            document.getElementById('display-matricula').textContent = matricula;
            document.getElementById('display-email').textContent = email;
            document.getElementById('display-telefone').textContent = telefone;
            document.getElementById('display-acesso').textContent = acesso;
            cardUsuario.style.display = 'block';
            
            // Carregar empréstimos do usuário
            carregarEmprestimos(id);
        }

        function carregarEmprestimos(usuarioId) {
            fetch(`../../../src/controller/buscar-emprestimos.php?usuario_id=${usuarioId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tabela-emprestimos-body');
                    
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">Nenhum empréstimo encontrado para este usuário</td></tr>';
                        return;
                    }
                    
                    tbody.innerHTML = data.map(emp => {
                        const statusClass = emp.status === 'devolvido' ? 'status-devolvido' : 'status-ativo';
                        const botoesAcao = emp.status !== 'devolvido' ? 
                            `<button class="btn-renovar" onclick="renovarEmprestimo(${emp.id})">
                                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Renovar.png" alt="">Renovar
                            </button>
                            <button class="btn-devolver" onclick="devolverLivro(${emp.id})">
                                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icone_Devolver.png" alt="">Devolver
                            </button>` : '';
                        
                        return `
                            <tr id="livro-${emp.id}">
                                <td><img src="${emp.foto}" alt="Capa do livro ${emp.titulo}" class="livro-imagem"></td>
                                <td>${emp.titulo}</td>
                                <td>${emp.codigo}</td>
                                <td><span class="${statusClass}">${emp.status.charAt(0).toUpperCase() + emp.status.slice(1)}</span></td>
                                <td class="botoes-acao">
                                    <button class="btn-abrir-mais" onclick="toggleDetalhes(${emp.id})">Abrir Mais</button>
                                    ${botoesAcao}
                                </td>
                            </tr>
                            <tr id="detalhes-livro-${emp.id}" class="info-detalhes">
                                <td colspan="5">
                                    <p><strong>Data de Empréstimo:</strong> ${formatarData(emp.data_emprestimo)}</p>
                                    <p><strong>Prazo para Devolução:</strong> ${formatarData(emp.prazo_devolucao)}</p>
                                    <p><strong>Data de Devolução:</strong> ${emp.data_devolucao ? formatarData(emp.data_devolucao) : 'N/A'}</p>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => console.error('Erro:', error));
        }

        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleDateString('pt-BR');
        }

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-row')) {
                sugestoesDiv.style.display = 'none';
            }
        });
    </script>
    
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>

</body>

</html>