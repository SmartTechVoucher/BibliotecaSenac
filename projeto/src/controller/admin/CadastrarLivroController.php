<?php
/**
 * Controller para cadastro de livros no painel admin.
 * Processa form de cadastro, valida e insere usando LivroModel com PDO.
 * Inclui upload real de foto para pasta public/uploads/.
 * CORRIGIDO: Agora cria registro na tabela exemplares automaticamente.
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';
require_once __DIR__ . '/../../model/admin/ExemplaresModel.php';

class CadastrarLivroController {
    private $livro_model;
    private $exemplares_model;

    public function __construct() {
        $this->livro_model = new LivroModel();
        $this->exemplares_model = new ExemplaresModel();
    }

    /**
     * Processa o cadastro de um novo livro a partir do form POST.
     * Valida dados, faz upload real de capa.
     * @return bool True se cadastrado com sucesso, false caso contrário
     */
    public function cadastrar() {
        $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';
        
        if ($isAjax) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
        }
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST para cadastro.');
            }

            $dados = [
                'titulo' => trim($_POST['titulo-livro'] ?? ''),
                'id_autor' => (int) ($_POST['autor'] ?? 0),
                'isbn' => trim($_POST['isbn-livro'] ?? ''),
                'data_publicacao' => $_POST['publicacao-livro'] ?? null,
                'id_categoria' => (int) ($_POST['categoria'] ?? 0),
                'numero_paginas' => (int) ($_POST['numero-paginas'] ?? 0),
                'descricao' => trim($_POST['resumo-livro'] ?? ''),
                'id_unidade' => (int) ($_POST['editora'] ?? 0),
                'foto' => 'uploads/default-capa.jpg',
                'notas' => trim($_POST['notas-livro'] ?? ''),
                'resumo_livro' => trim($_POST['resumo-livro'] ?? ''),
                'id_documento' => (int) ($_POST['tipo-documento'] ?? 1),
                'id_idioma' => (int) ($_POST['idioma'] ?? 1),
                'id_area' => (int) ($_POST['area'] ?? 1)
            ];

            // Processar upload de capa
            if (isset($_FILES['capa-livro']) && $_FILES['capa-livro']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['capa-livro'];
                $error_code = $file['error'];
                
                error_log("Upload iniciado: error_code=$error_code | size={$file['size']} | name={$file['name']}");
                
                if ($error_code !== UPLOAD_ERR_OK) {
                    $mensagens_erro = [
                        UPLOAD_ERR_INI_SIZE => 'Arquivo excede upload_max_filesize do php.ini',
                        UPLOAD_ERR_FORM_SIZE => 'Arquivo excede MAX_FILE_SIZE do formulário',
                        UPLOAD_ERR_PARTIAL => 'Upload parcial do arquivo',
                        UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não existe',
                        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever no disco',
                        UPLOAD_ERR_EXTENSION => 'Extensão PHP bloqueou o upload'
                    ];
                    
                    $msg = $mensagens_erro[$error_code] ?? 'Erro desconhecido no upload';
                    error_log("Erro upload: $msg (code $error_code)");
                    throw new Exception("Falha no upload: $msg");
                }
                
                if ($file['size'] == 0 || empty($file['tmp_name'])) {
                    throw new Exception('Arquivo vazio ou inválido.');
                }
                
                $upload_dir = __DIR__ . '/../../../public/uploads/';
                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0755, true)) {
                        error_log("Falha ao criar diretório: $upload_dir");
                        throw new Exception('Erro ao criar pasta uploads/. Verifique permissões.');
                    }
                    error_log("Diretório criado: $upload_dir");
                }

                if (!is_writable($upload_dir)) {
                    error_log("Diretório não gravável: $upload_dir");
                    throw new Exception('Pasta uploads/ sem permissão de escrita. Configure permissões adequadas.');
                }

                $gitkeep = $upload_dir . '.gitkeep';
                if (!file_exists($gitkeep)) {
                    file_put_contents($gitkeep, '');
                }
                
                $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (!in_array($extensao, $extensoes_permitidas)) {
                    throw new Exception('Formato inválido. Use: JPG, PNG, GIF ou WebP.');
                }
                
                $nome_arquivo = 'capa_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extensao;
                $caminho_completo = $upload_dir . $nome_arquivo;
                
                error_log("Movendo arquivo: {$file['tmp_name']} -> $caminho_completo");
                
                if (!move_uploaded_file($file['tmp_name'], $caminho_completo)) {
                    $ultimo_erro = error_get_last();
                    error_log("Falha no move_uploaded_file: " . json_encode($ultimo_erro));
                    throw new Exception('Falha ao salvar arquivo. Verifique permissões e antivírus.');
                }
                
                if (!file_exists($caminho_completo) || filesize($caminho_completo) == 0) {
                    if (file_exists($caminho_completo)) {
                        unlink($caminho_completo);
                    }
                    throw new Exception('Arquivo não foi salvo corretamente.');
                }
                
                $dados['foto'] = 'uploads/' . $nome_arquivo;
                error_log("Upload concluído: $caminho_completo (" . filesize($caminho_completo) . " bytes)");
            }

            // Validações
            if (empty($dados['titulo'])) {
                throw new Exception('O título é obrigatório.');
            }
            
            if (empty($dados['isbn'])) {
                throw new Exception('O ISBN é obrigatório.');
            }
            
            $campos_obrigatorios = [
                'id_autor' => 'Autor',
                'id_unidade' => 'Editora',
                'id_idioma' => 'Idioma',
                'id_categoria' => 'Categoria',
                'id_area' => 'Área',
                'id_documento' => 'Tipo de documento'
            ];
            
            foreach ($campos_obrigatorios as $campo => $nome) {
                if ($dados[$campo] <= 0) {
                    throw new Exception("Selecione um(a) $nome válido(a).");
                }
            }
            
            if ($dados['numero_paginas'] < 1 || $dados['numero_paginas'] > 99999) {
                throw new Exception('Número de páginas deve estar entre 1 e 99999.');
            }
            
            $isbn_limpo = preg_replace('/[^0-9]/', '', $dados['isbn']);
            if (!preg_match('/^\d{10}$|^\d{13}$/', $isbn_limpo)) {
                throw new Exception('ISBN deve ter 10 ou 13 dígitos.');
            }

            // Cadastrar no banco
            $id_livro = $this->livro_model->cadastrarLivro($dados);

            if (!$id_livro) {
                throw new Exception('Erro ao salvar no banco de dados. Verifique ISBN único.');
            }

            // ============================================
            // CORREÇÃO: Criar registro inicial na tabela exemplares
            // ============================================
            try {
                $exemplar_criado = $this->exemplares_model->criarEstoqueInicial($id_livro);
                
                if (empty($exemplar_criado)) {
                    error_log("AVISO: Não foi possível criar registro de exemplar para o livro ID $id_livro");
                    // Não lançar exceção aqui para não impedir o cadastro do livro
                } else {
                    error_log("Estoque inicial criado: Livro ID $id_livro - 1 exemplar disponível");
                }
            } catch (Exception $e) {
                error_log("ERRO ao criar exemplar inicial: " . $e->getMessage());
                // Continua mesmo com erro, pois o livro já foi cadastrado
            }

            $mensagem_sucesso = "Livro '{$dados['titulo']}' cadastrado com sucesso!";
            
            if ($isAjax) {
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => $mensagem_sucesso,
                    'id' => $id_livro
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['toast'] = [
                    'mensagem' => $mensagem_sucesso,
                    'tipo' => 'success'
                ];
                header("Location: ./src/views/admin/telaDosLivrosCadastrados.php");
                exit;
            }

        } catch (Exception $e) {
            error_log("CadastrarLivroController - Erro: {$e->getMessage()}");
            
            $mensagem_erro = $e->getMessage();
            
            if ($isAjax) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => $mensagem_erro
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['toast'] = [
                    'mensagem' => $mensagem_erro,
                    'tipo' => 'error'
                ];
                header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                exit;
            }
        }
    }
}