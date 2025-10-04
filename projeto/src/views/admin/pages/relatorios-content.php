

<form id="cadastro-form" action="#" method="post" enctype="multipart/form-data" name="cadastrarRelatorios">
    <fieldset class="form-section">
        <legend> <img src="<?php echo $URLBASE ?>/public/assets/icons/relatorio.png" alt="">Gerar relatórios</legend>

        <div class="form-row">

            <div class="form-grupo">
                <label for="genero"><img src="<?php echo $URLBASE ?>/public/assets/icons/relatorio.png" alt="" class="icons">Sobre:</label>
                <select id="genero" name="genero" class="select-padrao">
                    <option value="">Selecione</option>
                    <option value="">Acervo</option>
                    <option value="">Aquisição</option>
                    <option value="">Empréstimos</option>
                    <option value="">Usuários</option>
                </select>
            </div>

            <div class="form-grupo">
                <label for="formato"><img src="<?php echo $URLBASE ?>/public/assets/icons/exportar.png" alt="" class="icons">Exportar/salvar como:</label>
                <select id="formato" name="formato" class="select-padrao">
                    <option value="">Selecione</option>
                    <option value="">PDF</option>
                    <option value="">Excel</option>
                </select>
            </div>

            <div class="form-grupo">
                <label for="unidade_senac"><img src="<?php echo $URLBASE ?>/public/assets/icons/unidade.png" alt="" class="icons">Unidade:</label>
                <select id="unidade_senac" name="unidade_senac" class="select-padrao" required>
                    <option value="">Selecione</option>
                    <option value="senac_hub">Senac Hub Academy</option>
                    <option value="senac_dou">Senac Dourados</option>
                    <option value="senac_tres">Senac Três Lagoas</option>
                </select>
            </div>

            <div class="form-grupo">
                <label for="inicio"><img src="<?php echo $URLBASE ?>/public/assets/icons/calendario-antes.png" alt="" class="icons">Começando de:</label>
                <input type="date" id="inicio" name="inicio" class="input-date-padrao">
            </div>

            <div class="form-grupo">
                <label for="fim"><img src="<?php echo $URLBASE ?>/public/assets/icons/calendario-depois.png" alt="" class="icons">Até:</label>
                <input type="date" id="fim" name="fim" class="input-date-padrao">
            </div>

            <div class="form-grupo">
                <label for="ordenagem"><img src="<?php echo $URLBASE ?>/public/assets/icons/ordenagem.png" alt="" class="icons">Ordenagem</label>
                <select id="ordenagem" name="ordenagem" class="select-padrao" required>
                    <option value="">Selecione</option>
                    <option value="">Data crescente</option>
                    <option value="">Data decrescente</option>
                    <option value="">Crescente</option>
                    <option value="">Decrescente</option>
                </select>
            </div>

        </div>

        <div class="botao-container">
            <button type="button">Cancelar</button>
            <button type="submit" class="botao-cancelar">Gerar relatório</button>
        </div>
    </fieldset>
</form>

<script src="<?php echo $URLBASE ?>/public/js/admin/telaDeRelatorios.js"></script>
