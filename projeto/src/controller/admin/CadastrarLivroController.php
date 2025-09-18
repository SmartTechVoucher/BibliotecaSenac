<?php
/**
 * Controller para cadastro de livros no painel admin.
 * Processa form de cadastro, valida e insere usando LivroModel com PDO.
 * Inclui upload real de foto para pasta public/uploads/.
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';

class CadastrarLivroController {
    private $livro_model;

    public function __construct() {
        $this->livro_model = new LivroModel();
    }

    /**
     * Processa o cadastro de um novo livro a partir do form POST.
     * Valida dados, faz upload real de capa,
     * @return bool True se cadastrado com sucesso, false caso contrário
     */
    public function cadastrar() {
        $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST para cadastro.');
            }

            // Coleta dados do form
            $dados = [
                'titulo' => trim($_POST['titulo-livro'] ?? ''),
                'id_autor' => (int) ($_POST['autor'] ?? 0),
                'isbn' => trim($_POST['isbn-livro'] ?? ''),
                'data_publicacao' => $_POST['publicacao-livro'] ?? null,
                'id_categoria' => (int) ($_POST['categoria'] ?? 0),
                'numero_paginas' => (int) ($_POST['numero-paginas'] ?? 0),
                'descricao' => trim($_POST['resumo-livro'] ?? ''),
                'id_unidade' => (int) ($_POST['editora'] ?? 0),
                'foto' => 'uploads/default-capa.jpg',  // Default
                'notas' => trim($_POST['notas-livro'] ?? ''),
                'resumo_livro' => trim($_POST['resumo-livro'] ?? ''),
                'id_documento' => (int) ($_POST['tipo-documento'] ?? 1),
                'id_idioma' => (int) ($_POST['idioma'] ?? 1),
                'id_area' => (int) ($_POST['area'] ?? 1)
            ];

            if (isset($_FILES['capa-livro'])) {
                $file = $_FILES['capa-livro'];
                $error_code = $file['error'];
                $tmp_name = $file['tmp_name'];
                $size = $file['size'];
                
                error_log("Upload debug: error_code=$error_code | tmp_name='$tmp_name' | size=$size | name='{$file['name']}'");
                
                if ($error_code !== UPLOAD_ERR_OK) {
                    $erros = [
                        UPLOAD_ERR_INI_SIZE => 'Arquivo muito grande (php.ini upload_max_filesize)',
                        UPLOAD_ERR_FORM_SIZE => 'Arquivo muito grande (MAX_FILE_SIZE no form)',
                        UPLOAD_ERR_PARTIAL => 'Upload incompleto',
                        UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado',
                        UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não existe',
                        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever arquivo no disco',
                        UPLOAD_ERR_EXTENSION => 'Extensão PHP parou o upload'
                    ];
                    $msg = $erros[$error_code] ?? 'Erro desconhecido no upload';
                    error_log("Erro upload (code $error_code): $msg");
                    throw new Exception("Falha no upload: $msg. Verifique configurações PHP (upload_max_filesize=2M, post_max_size=8M).");
                }
                
                if ($size == 0 || empty($tmp_name)) {
                    error_log("Arquivo vazio ou tmp_name vazio");
                    throw new Exception('Arquivo vazio ou não enviado corretamente.');
                }
                
                $upload_dir = __DIR__ . '/../../../public/uploads/';
                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0755, true)) {
                        error_log("Falha ao criar $upload_dir");
                        throw new Exception('Erro ao criar pasta uploads/. Verifique permissões.');
                    }
                }
                
                // Verifica se pasta é gravável
                if (!is_writable($upload_dir)) {
                    error_log("Pasta $upload_dir não é gravável");
                    throw new Exception('Pasta uploads/ não tem permissão de escrita. No Windows/XAMPP: clique direito > Propriedades > Segurança > Editar > dar controle total para Everyone.');
                }
                
                $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
                    throw new Exception('Tipo de arquivo inválido para capa. Use JPG, PNG ou GIF.');
                }
                
                $nome_arquivo = 'capa_' . time() . '_' . rand(1000, 9999) . '.' . $extensao; // Evita colisão
                $caminho_arquivo = $upload_dir . $nome_arquivo;
                
                error_log("Tentando move_uploaded_file: tmp='$tmp_name' -> destino='$caminho_arquivo'");
                
                $move_success = move_uploaded_file($tmp_name, $caminho_arquivo);
                error_log("move_uploaded_file retornou: " . ($move_success ? 'true' : 'false'));
                
                if (!$move_success) {
                    $last_error = error_get_last();
                    error_log("Erro após move: " . print_r($last_error, true));
                    throw new Exception('Falha no move_uploaded_file. Verifique permissões da pasta uploads/ e antivírus (pode bloquear).');
                }
                
                // Verificação final do arquivo
                if (!file_exists($caminho_arquivo)) {
                    error_log("Arquivo não existe após move: $caminho_arquivo");
                    throw new Exception('Arquivo não foi criado após upload. Verifique permissões e espaço em disco.');
                }
                
                $file_size = filesize($caminho_arquivo);
                if ($file_size == 0) {
                    unlink($caminho_arquivo); // Remove arquivo vazio
                    error_log("Arquivo criado mas vazio: $file_size bytes");
                    throw new Exception('Arquivo criado mas vazio. Verifique permissões de escrita.');
                }
                
                $dados['foto'] = 'uploads/' . $nome_arquivo;
                error_log("Upload sucesso: $caminho_arquivo ($file_size bytes)");
            } else {
                error_log("Nenhum arquivo capa-livro enviado ou erro desconhecido");
                $dados['foto'] = 'uploads/default-capa.jpg';
            }

            // Validações básicas
            if (empty($dados['titulo']) || empty($dados['isbn'])) {
                throw new Exception('Título e ISBN são obrigatórios.');
            }
            if ($dados['id_autor'] <= 0 || $dados['id_categoria'] <= 0 || $dados['numero_paginas'] <= 0 ||
                $dados['id_unidade'] <= 0 || $dados['id_idioma'] <= 0 || $dados['id_area'] <= 0 || $dados['id_documento'] <= 0) {
                throw new Exception('Selecione todos os campos obrigatórios: autor, editora, idioma, categoria, área, tipo de documento.');
            }
            if ($dados['numero_paginas'] < 1 || $dados['numero_paginas'] > 99999) {
                throw new Exception('Número de páginas deve ser entre 1 e 99999.');
            }
            // Validação flexível para ISBN-13: aceita 13 dígitos com ou sem hífens
            $isbnClean = preg_replace('/[-\s]/', '', $dados['isbn']);
            if (!preg_match('/^\d{13}$/', $isbnClean)) {
                throw new Exception('ISBN deve ter 13 dígitos (com ou sem hífens, ex: 9798330313518 ou 979-8-3303-1351-8).');
            }

            // Chama model para cadastrar
            $id_livro = $this->livro_model->cadastrarLivro($dados);

            if ($id_livro) {
                $successMsg = "Livro '{$dados['titulo']}' cadastrado com sucesso! ID: $id_livro";
                if ($isAjax) {
                    echo json_encode([
                        'sucesso' => true,
                        'mensagem' => $successMsg,
                        'id' => $id_livro
                    ]);
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => $successMsg,
                        'tipo' => 'success'
                    ];
                    header("Location: ./src/views/admin/telaDosLivrosCadastrados.php");
                    exit;
                }
                return true;
            } else {
                throw new Exception('Erro ao salvar no banco de dados. Verifique ISBN único e IDs válidos.');
            }

        } catch (Exception $e) {
            error_log('CadastrarLivroController error: ' . $e->getMessage() . ' | Dados: ' . json_encode($dados ?? []));
            $errorMsg = $e->getMessage();
            if ($isAjax) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => $errorMsg
                ]);
            } else {
                session_start();
                $_SESSION['toast'] = [
                    'mensagem' => $errorMsg,
                    'tipo' => 'error'
                ];
                header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                exit;
            }
            return false;
        }
    }
}
?>