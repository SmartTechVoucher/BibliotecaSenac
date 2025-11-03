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
            <div class="form-coluna">
                <div class="form-row">
                    <div class="form-grupo">
                        <label for="titulo-livro">Titulo *</label>
                        <?php
                        InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                        ?>
                    </div>
                

                <!-- Campo para número de páginas (obrigatório no schema de livros) -->
                    <div class="form-grupo">
                        <label for="numero-paginas">Número de Páginas *</label>
                        <?php InputAdmin(largura: 100, name: "numero-paginas", id: "numero-paginas", tipo: "number", required: true) ?>
                    </div>
                </div>
                

                <div class="form-grupo">
                    <?php
                    renderSelectModal('autor', 'Autor', $autores);
                    ?>

                </div>
                <div class="form-grupo">
                    <?php
                    renderSelectModal('editora', 'Editora', $editoras);
                    ?>
                </div>




            </div>
            <div class="form-coluna">
                <div class="form-grupo">
                    <label for="isbn-livro">ISBN *</label>
                    <?php
                    InputAdmin(largura: 100, name: "isbn-livro", id: "isbn-livro", required: true)
                    ?>
                </div>
                <div class="form-grupo">
                    <?php
                    renderSelectModal('idioma', 'Idioma', $idiomas);
                    ?>
                </div>
                <div class="form-grupo">
                    <?php
                    renderSelectModal('categoria', 'Categoria', $categorias);
                    ?>
                </div>
            </div>
            <div class="form-coluna">
                <div class="form-grupo">
                    <?php
                    renderSelectModal('area', 'Área', $areas);
                    ?>
                </div>
                <div class="form-grupo">
                    <label for="publicacao-livro">Ano</label>
                    <?php
                    InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date")
                    ?>
                </div>
                <div class="form-grupo">
                    <label for="capa-livro">Capa</label>
                    <?php
                    InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file")
                    ?>
                    <div id="preview-container" style="margin-top: 10px;">
                        <img id="preview-capa" src="" alt="Pré-visualização da capa" style="max-width: 200px; max-height: 300px; display: none; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
            </div>
            <div class="form-coluna">
                <div class="form-grupo">
                    <label for="resumo-livro">Resumo</label>
                    <textarea name="resumo-livro" id="resumo-livro" cols="30" rows="10"></textarea>
                </div>
                <div class="form-grupo">
                    <label for="notas-livro">Notas</label>
                    <textarea name="notas-livro" id="notas-livro" cols="30" rows="10"></textarea>
                </div>
                <div class="form-grupo">
                    <?php renderSelectModal('tipo-documento', 'Tipo de documento', $documentos); ?>
                </div>
            </div>


        <div class="botao-container">
            <?php
            botao("Cancelar", "reset", "cancel-button"); // botão vermelho
            botao("Registrar", "submit", "btn-primary"); // botão azul
            ?>
        </div>  




    </form>
</div>
<script src="<?php echo $URLBASE ?>/public/js/admin/telaDeCadastroDeLivros.js"></script>
<!-- Modal de confirmação de cadastro de livro -->
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