function toggleDetalhes(id) {
    const detalhes = document.getElementById('detalhes-livro-' + id);
    if (detalhes.style.display === 'table-row') {
        detalhes.style.display = 'none';
    } else {
        detalhes.style.display = 'table-row';
    }
}