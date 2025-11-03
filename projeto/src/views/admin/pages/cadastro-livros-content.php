<?php
require "../../../config/constantes.php";
require_once __DIR__ . '/../../../../src/model/usuario/LivroModel.php';
require_once __DIR__ . '/../../../../public/components/admin/input/input-admin.php';
require_once __DIR__ . '/../../../../public/components/admin/button/button-admin.php';
require_once __DIR__ . '/../../../../public/components/admin/select/input-select.php';

$livro_model = new LivroModel();

$autores = $livro_model->getOpcoesSelect('autores');
$editoras = $livro_model->getOpcoesSelect('unidades');
$idiomas = $livro_model->getOpcoesSelect('idiomas');
$categorias = $livro_model->getOpcoesSelect('categorias');
$areas = $livro_model->getOpcoesSelect('areas');
$documentos = $livro_model->getOpcoesSelect('documentos');
?>

<form id="cadastro-form" action="<?php echo $URLBASE ?>/router.php?acao=cadastrarLivro" method="post" enctype="multipart/form-data">

    <!-- Linha 1 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <label for="titulo-livro">Título *</label>
                <?php InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true); ?>
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <label for="numero-paginas">Número de Páginas *</label>
                <?php InputAdmin(largura: 100, name: "numero-paginas", id: "numero-paginas", tipo: "number", required: true); ?>
            </div>
        </div>
    </div>

    <!-- Linha 2 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('autor', 'Digite o autor', 'Autor *', $autores);?>
                
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('editora', 'Digite a editora', 'Editora *', $editoras); ?>
            </div>
        </div>
    </div>

    <!-- Linha 3 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <label for="isbn-livro">ISBN *</label>
                <?php InputAdmin(largura: 100, name: "isbn-livro", id: "isbn-livro", required: true); ?>
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('idioma', 'Digite o idioma', 'Idioma *', $idiomas); ?>
            </div>
        </div>
    </div>

    <!-- Linha 4 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('categoria', 'Digite a categoria', 'Categoria *', $categorias); ?>
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('area', 'Digite a área', 'Área *', $areas); ?>
            </div>
        </div>
    </div>

    <!-- Linha 5 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <label for="publicacao-livro">Ano *</label>
                <?php InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date"); ?>
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <label for="capa-livro">Capa</label>
                <?php InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file"); ?>
                <div id="preview-container" style="margin-top: 10px;">
                    <img id="preview-capa" src="" alt="Pré-visualização da capa"
                        style="max-width: 200px; max-height: 300px; display: none; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Linha 6 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <label for="resumo-livro">Resumo *</label>
                <textarea name="resumo-livro" id="resumo-livro" rows="6"></textarea>
            </div>
        </div>

        <div class="form-coluna">
            <div class="form-grupo">
                <label for="notas-livro">Notas</label>
                <textarea name="notas-livro" id="notas-livro" rows="6"></textarea>
            </div>
        </div>
    </div>

    <!-- Linha 7 -->
    <div class="form-row">
        <div class="form-coluna">
            <div class="form-grupo">
                <?php renderSelectModal('tipo-documento', 'Digite o tipo de documento', 'Tipo de documento *', $documentos); ?>
            </div>
        </div>
    </div>

    <!-- Botões -->
    <div class="botao-container">
        <?php
        botao("Cancelar", "reset", "cancel-button");
        botao("Registrar", "submit", "btn-primary");
        ?>
    </div>

</form>

<!-- Script -->
<script src="<?php echo $URLBASE ?>/public/js/admin/telaDeCadastroDeLivros.js"></script>

<!-- Modal -->
<div id="success-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Livro Registrado com Sucesso!</h2>
        <p id="success-message"></p>
        <div class="modal-buttons">
            <button class="btn-cancelar" onclick="closeSuccessModal()">Continuar Cadastrando</button>
            <button class="btn-salvar" onclick="goToBooksList()">Ver Lista de Livros</button>
        </div>
    </div>
</div>

<script>
    function showSuccessModal(message) {
        document.getElementById('success-message').textContent = message;
        document.getElementById('success-modal').style.display = 'flex';
    }

    function closeSuccessModal() {
        document.getElementById('success-modal').style.display = 'none';
    }

    function goToBooksList() {
        window.location.href = '<?php echo $URLBASE ?>/src/views/admin/livros-cadastrados.php';
    }
</script>
