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

            // Upload real de capa
            if (isset($_FILES['capa-livro']) && $_FILES['capa-livro']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/../../../public/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $extensao = pathinfo($_FILES['capa-livro']['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($extensao), ['jpg', 'jpeg', 'png', 'gif'])) {
                    throw new Exception('Tipo de arquivo inválido para capa. Use JPG, PNG ou GIF.');
                }
                $nome_arquivo = 'capa_' . time() . '.' . $extensao;
                $caminho_arquivo = $upload_dir . $nome_arquivo;

                if (!move_uploaded_file($_FILES['capa-livro']['tmp_name'], $caminho_arquivo)) {
                    throw new Exception('Erro ao fazer upload da capa. Verifique permissões da pasta uploads/.');
                }
                $dados['foto'] = 'uploads/' . $nome_arquivo;
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