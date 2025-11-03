<?php
require "../../../config/constantes.php";
?>


<div class="perfil-card">
  <div class="perfil-header">
    <h1>Luciano (786543)</h1>

    <div class="perfil-content">
      <!-- Primeira linha: Nome, Data de Nascimento, Apelido e Botão -->
      <div class="primeira-linha">
        <div class="campo-nome">
          <label>*Perfil de acesso:</label>
          <input type="text" value="Luciano" class="input-field" readonly>
        </div>
        <div class="campo-email">
          <label>E-mail</label>
          <input type="email" value="amojava@gmail.com" class="input-field" readonly>
        </div>

      </div>

      <!-- Título da seção -->
      <div class="section-title">
        <!-- Segunda linha: E-mail, Senha e Criado em -->
        <div class="segunda-linha">

          <div class="campo-senha">
            <label>Senha</label>
            <input type="password" value="************" class="input-field" readonly>
          </div>

          <div class="campo-criado">
            <label>telefone</label>
            <input type="text" value="13/05/2023" class="input-field" readonly>
          </div>
        </div>
        <div class="campo-botao">
          <label>&nbsp;</label>
          <button class="btn-editar" type="button">Editar informação</button>

        </div>
      </div>
    </div>