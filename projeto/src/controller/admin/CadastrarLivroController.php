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
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido. Use POST para cadastro.']);
            return false;
        }

        $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

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
            $nome_arquivo = 'capa_' . time() . '.' . $extensao;
            $caminho_arquivo = $upload_dir . $nome_arquivo;

            if (move_uploaded_file($_FILES['capa-livro']['tmp_name'], $caminho_arquivo)) {
                $dados['foto'] = 'uploads/' . $nome_arquivo;
            } else {
                if ($isAjax) {
                    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao fazer upload da capa. Use uma imagem válida.']);
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Erro ao fazer upload da capa. Use uma imagem válida.',
                        'tipo' => 'error'
                    ];
                    header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                    exit;
                }
                return false;
            }
        }

        // Validações básicas
        if (empty($dados['titulo']) || empty($dados['isbn']) || $dados['id_autor'] <= 0 || $dados['id_categoria'] <= 0 || $dados['numero_paginas'] <= 0 || $dados['id_unidade'] <= 0 || $dados['id_idioma'] <= 0 || $dados['id_area'] <= 0 || $dados['id_documento'] <= 0) {
            $errorMsg = 'Preencha todos os campos obrigatórios: título, ISBN, autor, editora, idioma, categoria, área, tipo de documento e número de páginas.';
            if ($isAjax) {
                echo json_encode(['sucesso' => false, 'mensagem' => $errorMsg]);
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
            $errorMsg = 'Erro ao cadastrar livro. Verifique os dados e tente novamente. Consulte logs para detalhes.';
            if ($isAjax) {
                echo json_encode(['sucesso' => false, 'mensagem' => $errorMsg]);
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