
<?php
    require "../../../config/constantes.php";
    require_once __DIR__ . '/../../../../public/components/admin/input/input-admin.php';
?>

<form id="cadastro-form" action="#" method="post" enctype="multipart/form-data" name="cadastrarRelatorios">
    <fieldset class="form-section">
        <legend> <img src="<?php echo $URLBASE ?>/public/assets/icons/relatorio.png" alt="">Livros</legend>
        <?php
            InputAdmin(
                largura: 100,
                name: "",
                icone: '<img src="' . $URLBASE . '/public/assets/icons/Buscar.png" alt="Buscar">',
                id: "titulo-livro",
                required: true,
                placeholder: "Pesquise por título ou ISBN do livro"
            )
        ?>

<table>
  <thead>
    <tr>
      <th>Título</th>
      <th>Autor</th>
      <th>ISBN</th>
      <th>Quantidade</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1984</td>
      <td>George Orwell</td>
      <td>978-0451524935</td>
      <td>5</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Dom Casmurro</td>
      <td>Machado de Assis</td>
      <td>978-8535911664</td>
      <td>3</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Dom Casmurro</td>
      <td>Machado de Assis</td>
      <td>978-8535911664</td>
      <td>3</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Dom Casmurro</td>
      <td>Machado de Assis</td>
      <td>978-8535911664</td>
      <td>3</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Dom Casmurro</td>
      <td>Machado de Assis</td>
      <td>978-8535911664</td>
      <td>3</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Dom Casmurro</td>
      <td>Machado de Assis</td>
      <td>978-8535911664</td>
      <td>3</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>O Cortiço</td>
      <td>Aluísio Azevedo</td>
      <td>978-8508040537</td>
      <td>4</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>O Senhor dos Anéis</td>
      <td>J.R.R. Tolkien</td>
      <td>978-8578277109</td>
      <td>2</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
    <tr>
      <td>Harry Potter</td>
      <td>J.K. Rowling</td>
      <td>978-8532530787</td>
      <td>6</td>
      <td class="actions">
        <span class="edit-icon">✏️</span>
        <span class="delete-icon">🗑️</span>
      </td>
    </tr>
  </tbody>
</table>

    </fieldset>
</form>

<script src="<?php echo $URLBASE ?>/public/js/admin/telaDeRelatorios.js"></script>

<style>
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }

  th, td {
    padding: 16px;
    text-align: left;
  }

  thead {
    background-color: #f0f2f5;
  }

  th {
    color: #4a5568;
    font-weight: 600;
  }

  tbody tr {
    border-bottom: 1px solid #e2e8f0;
  }

  tbody tr:nth-child(even) {
    background-color: #f9fafb;
  }

  tbody tr:last-child {
    border-bottom: none;
  }

  td.actions {
    display: flex;
    gap: 12px;
  }

  td.actions svg {
    cursor: pointer;
    transition: 0.2s;
  }

  td.actions svg:hover {
    opacity: 0.7;
  }

  /* Ícones simples para exemplo */
  .edit-icon {
    color: #1d4ed8; /* azul */
  }

  .delete-icon {
    color: #ef4444; /* vermelho */
  }

  .pagination {
  margin-top: 20px;
}

.pagination a {
  margin: 0 5px;
  padding: 8px 12px;
  background-color: #f0f2f5;
  color: #333;
  text-decoration: none;
  border-radius: 4px;
}

.pagination a:hover {
  background-color: #d1d5db;
}

</style>
