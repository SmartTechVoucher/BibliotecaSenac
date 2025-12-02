<?php
// Iniciar output buffering ANTES de qualquer output
ob_start();

// Caminho correto para o arquivo constantes.php
// Ajuste conforme sua estrutura de pastas
require_once __DIR__ . "/../../../../config/constantes.php";


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários Cadastrados - Biblioteca SENAC</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE; ?>/public/css/admin/usuarios-cadastrados.css">
    
    <script>
        const URLBASE = "<?php echo $URLBASE; ?>";
    </script>
</head>
<body>
    <div class="main-container">
        <!-- Cabeçalho -->
        <div class="header-section">
            <h1>Usuários Cadastrados</h1>
            <p class="subtitle">Gerencie todos os usuários do sistema</p>
        </div>

        <!-- Cards de Estatísticas -->
        <div id="estatisticas-container" class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>---</h3>
                    <p>Total de Usuários</p>
                </div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>---</h3>
                    <p>Usuários Regulares</p>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>---</h3>
                    <p>Usuários Bloqueados</p>
                </div>
            </div>
        </div>

        <!-- Barra de Busca -->
        <div class="search-section">
            <div class="search-box">
                <input type="text" id="campoBusca" placeholder="Buscar por nome, email ou CPF...">
            </div>
        </div>

        <!-- Abas: Regulares | Bloqueados -->
        <div class="tabs-container">
            <button class="tab-btn active" data-tab="regulares">
                Regulares
            </button>
            <button class="tab-btn" data-tab="bloqueados">
                Bloqueados
            </button>
        </div>

        <!-- Tabela de Regulares -->
        <div id="tab-regulares" class="tab-content active">
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Unidade</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaRegulares">
                        <tr>
                            <td colspan="6" class="loading-row">
                                <div class="skeleton-loader">
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line"></div>
                                </div>
                                <p style="margin-top: 15px; color: #666;">Carregando usuários...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginação Regulares -->
            <div class="pagination-controls">
                <button id="btnAnteriorRegulares" class="pagination-btn" disabled>
                    <span>←</span> Anterior
                </button>
                <span id="infoRegulares" class="pagination-info">Carregando...</span>
                <button id="btnProximoRegulares" class="pagination-btn" disabled>
                    Próximo <span>→</span>
                </button>
            </div>
        </div>

        <!-- Tabela de Bloqueados -->
        <div id="tab-bloqueados" class="tab-content">
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Unidade</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaBloqueados">
                        <tr>
                            <td colspan="6" class="loading-row">
                                <div class="skeleton-loader">
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line"></div>
                                </div>
                                <p style="margin-top: 15px; color: #666;">Carregando usuários...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginação Bloqueados -->
            <div class="pagination-controls">
                <button id="btnAnteriorBloqueados" class="pagination-btn" disabled>
                    <span>←</span> Anterior
                </button>
                <span id="infoBloqueados" class="pagination-info">Carregando...</span>
                <button id="btnProximoBloqueados" class="pagination-btn" disabled>
                    Próximo <span>→</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Edição/Detalhes -->
    <div id="modalUsuario" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Dados do Usuário</h2>
                <button class="btn-close" onclick="fecharModal()">✕</button>
            </div>

            <div class="modal-body">
                <!-- Foto e Dados Básicos -->
                <div class="user-basic-info">
                    <img id="userFoto" src="<?php echo $URLBASE; ?>/public/assets/img/NullUser.jpg" alt="Foto do usuário">
                    
                    <div class="user-main-data">
                        <div class="form-group">
                            <label>Nome Completo:</label>
                            <input type="text" id="userName" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Nome Social:</label>
                            <input type="text" id="userNomeSocial" class="form-input" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>CPF:</label>
                                <input type="text" id="userCPF" class="form-input" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label>Data de Nascimento:</label>
                                <input type="date" id="userNascimento" class="form-input" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Gênero:</label>
                            <select id="userGenero" class="form-input" disabled>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Não binario">Não binário</option>
                                <option value="Outros">Outros</option>
                                <option value="Não informar">Não informar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dados de Matrícula -->
                <div class="form-section">
                    <h3>Dados de Matrícula</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Matrícula:</label>
                            <input type="text" id="userMatricula" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Unidade:</label>
                            <input type="text" id="userUnidade" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Categoria:</label>
                            <input type="text" id="userCategoria" class="form-input" readonly>
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div class="form-section">
                    <h3>Contato</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" id="userEmail" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Telefone:</label>
                            <input type="text" id="userTelefone" class="form-input" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Endereço:</label>
                        <textarea id="userEndereco" class="form-input" rows="2" readonly></textarea>
                    </div>
                </div>

                <!-- Dados Acadêmicos -->
                <div class="form-section">
                    <h3>Dados Acadêmicos</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Curso:</label>
                            <input type="text" id="userCurso" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Turma:</label>
                            <input type="text" id="userTurma" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Fim do Curso:</label>
                            <input type="date" id="userDataFimCurso" class="form-input" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Observações:</label>
                        <textarea id="userNotas" class="form-input" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>

            <!-- Botões do Modal -->
            <div class="modal-footer">
                <button id="btnBloquear" class="btn btn-danger">
                    Bloquear Usuário
                </button>
                
                <button id="btnEditar" class="btn btn-primary">
                    Editar Dados
                </button>
                
                <button id="btnSalvar" class="btn btn-success" style="display: none;">
                    Salvar Alterações
                </button>
                
                <button id="btnCancelar" class="btn btn-secondary" style="display: none;">
                    Cancelar
                </button>
                
                <button class="btn btn-secondary" onclick="fecharModal()">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <div id="modalSucesso" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-body" style="text-align: center; padding: 135px;">
                <div style="font-size: 64px; color: #28a745; margin-bottom: 20px;">✓</div>
                <h3 style="color: #28a745; margin-bottom: 10px;">Sucesso!</h3>
                <p id="mensagemSucesso" style="color: #666;"></p>
            </div>
        </div>
    </div>

    <!-- Modal de Erro -->
    <div id="modalErro" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-body" style="text-align: center; padding: 135px;">
                <div style="font-size: 64px; color: #dc3545; margin-bottom: 20px;">✕</div>
                <h3 style="color: #dc3545; margin-bottom: 10px;">Erro!</h3>
                <p id="mensagemErro" style="color: #666;"></p>
                <button class="btn btn-secondary" onclick="fecharModalErro()" style="margin-top: 20px;">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script src="<?php echo $URLBASE; ?>/public/js/admin/usuarios-cadastrados-v2.js"></script>
</body>
</html>
<?php
// Limpar e enviar o buffer
ob_end_flush();
?>