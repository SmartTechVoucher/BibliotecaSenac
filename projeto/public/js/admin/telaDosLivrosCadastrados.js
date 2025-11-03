// Opções dos filtros e formulários
// Use a window-injected JSON object (defined in the HTML) or fallback to empty arrays

function atualizarFiltroValor() {
    const tipoSelect = document.getElementById('filtro-tipo');
    const valorSelect = document.getElementById('filtro-valor');
    const tipo = tipoSelect.value;
    
    valorSelect.innerHTML = '<option value="">Selecione...</option>';
    
    if (!tipo || !opcoesFiltro[tipo]) {
        valorSelect.disabled = true;
        return;
    }
    
    valorSelect.disabled = false;
    
    opcoesFiltro[tipo].forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        
        if (item.id == '<?php echo htmlspecialchars($filtro_valor); ?>') {
            option.selected = true;
        }
        
        valorSelect.appendChild(option);
    });
}

// ========== MODAL EDITAR ESTOQUE ==========
function abrirModalEstoque(id, titulo, total, disponiveis, emprestados, reservas) {
    document.getElementById('estoque-id-livro').value = id;
    document.getElementById('estoque-titulo').value = titulo;
    document.getElementById('estoque-total').value = total;
    document.getElementById('estoque-disponiveis').value = disponiveis;
    document.getElementById('estoque-emprestados').value = emprestados;
    document.getElementById('estoque-reservas').value = reservas;
    document.getElementById('modal-estoque').style.display = 'flex';
}

function fecharModalEstoque() {
    document.getElementById('modal-estoque').style.display = 'none';
}

document.getElementById('modal-estoque').addEventListener('click', function(e) {
    if (e.target === this) fecharModalEstoque();
});

document.getElementById('form-estoque').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Estoque atualizado com sucesso!');
            fecharModalEstoque();
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao atualizar estoque.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
});

// ========== MODAL EDITAR LIVRO ==========
function popularSelects() {
    // Popular autor
    const autorSelect = document.getElementById('edit-autor');
    autorSelect.innerHTML = '<option value="">Selecione o autor</option>';
    opcoesFiltro.autor.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        autorSelect.appendChild(option);
    });

    // Popular categoria
    const categoriaSelect = document.getElementById('edit-categoria');
    categoriaSelect.innerHTML = '<option value="">Selecione a categoria</option>';
    opcoesFiltro.categoria.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        categoriaSelect.appendChild(option);
    });

    // Popular editora
    const editoraSelect = document.getElementById('edit-editora');
    editoraSelect.innerHTML = '<option value="">Selecione a editora</option>';
    opcoesFiltro.editora.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        editoraSelect.appendChild(option);
    });

    // Popular idioma
    const idiomaSelect = document.getElementById('edit-idioma');
    idiomaSelect.innerHTML = '<option value="">Selecione o idioma</option>';
    opcoesFiltro.idioma.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        idiomaSelect.appendChild(option);
    });

    // Popular área
    const areaSelect = document.getElementById('edit-area');
    areaSelect.innerHTML = '<option value="">Selecione a área</option>';
    opcoesFiltro.area.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        areaSelect.appendChild(option);
    });

    // Popular tipo documento
    const documentoSelect = document.getElementById('edit-documento');
    documentoSelect.innerHTML = '<option value="">Selecione o tipo</option>';
    opcoesFiltro.documento.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        documentoSelect.appendChild(option);
    });
}

async function abrirModalEditarLivro(id) {
    try {
        // Buscar dados do livro
        const response = await fetch(`../../../router.php?acao=buscarLivro&id_livro=${id}`);
        const result = await response.json();
        
        if (!result.sucesso) {
            alert('Erro ao buscar dados do livro: ' + result.mensagem);
            return;
        }
        
        const livro = result.livro;
        
        // Popular selects
        popularSelects();
        
        // Preencher formulário
        document.getElementById('edit-id-livro').value = livro.id_livro;
        document.getElementById('edit-titulo').value = livro.titulo;
        document.getElementById('edit-autor').value = livro.id_autor;
        document.getElementById('edit-isbn').value = livro.isbn;
        document.getElementById('edit-categoria').value = livro.id_categoria;
        document.getElementById('edit-paginas').value = livro.numero_paginas;
        document.getElementById('edit-editora').value = livro.id_unidade;
        document.getElementById('edit-idioma').value = livro.id_idioma;
        document.getElementById('edit-area').value = livro.id_area;
        document.getElementById('edit-documento').value = livro.id_documento;
        document.getElementById('edit-publicacao').value = livro.data_publicacao || '';
        document.getElementById('edit-resumo').value = livro.descricao || '';
        document.getElementById('edit-notas').value = livro.notas || '';
        
        // Abrir modal
        document.getElementById('modal-editar-livro').style.display = 'flex';
        
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao carregar dados do livro: ' + error.message);
    }
}

function fecharModalEditarLivro() {
    document.getElementById('modal-editar-livro').style.display = 'none';
}

document.getElementById('modal-editar-livro').addEventListener('click', function(e) {
    if (e.target === this) fecharModalEditarLivro();
});

document.getElementById('form-editar-livro').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Livro atualizado com sucesso!');
            fecharModalEditarLivro();
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao atualizar livro.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
});

// ========== DELETAR LIVRO ==========
async function confirmarDeletar(id, titulo) {
    if (!confirm(`Tem certeza que deseja DELETAR o livro "${titulo}"?\n\nEsta ação não pode ser desfeita!`)) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('id_livro', id);
        
        const response = await fetch('../../../router.php?acao=deletarLivro', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Livro deletado com sucesso!');
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao deletar livro.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
}

// Inicializa
document.addEventListener('DOMContentLoaded', atualizarFiltroValor);