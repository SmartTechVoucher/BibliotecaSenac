<?php
/**
 * Controller para edição de livros
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';

class EditarLivroController {
    private $livro_model;

    public function __construct() {
        $this->livro_model = new LivroModel();
    }

    /**
     * Busca dados de um livro específico para edição
     */
    public function buscarLivro($id_livro) {
        try {
            $livro = $this->livro_model->getLivroPorId($id_livro);
            
            if (!$livro) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Livro não encontrado.'
                ];
            }

            return [
                'sucesso' => true,
                'livro' => $livro
            ];

        } catch (Exception $e) {
            error_log("EditarLivroController - Erro ao buscar livro: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar livro: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza dados do livro
     */
    public function editar() {
        $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';
        
        if ($isAjax) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
        }
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST para edição.');
            }

            $id_livro = (int) ($_POST['id_livro'] ?? 0);
            
            if ($id_livro <= 0) {
                throw new Exception('ID do livro inválido.');
            }

            $dados = [
                'id_livro' => $id_livro,
                'titulo' => trim($_POST['titulo-livro'] ?? ''),
                'id_autor' => (int) ($_POST['autor'] ?? 0),
                'isbn' => trim($_POST['isbn-livro'] ?? ''),
                'data_publicacao' => $_POST['publicacao-livro'] ?? null,
                'id_categoria' => (int) ($_POST['categoria'] ?? 0),
                'numero_paginas' => (int) ($_POST['numero-paginas'] ?? 0),
                'descricao' => trim($_POST['resumo-livro'] ?? ''),
                'id_unidade' => (int) ($_POST['editora'] ?? 0),
                'notas' => trim($_POST['notas-livro'] ?? ''),
                'resumo_livro' => trim($_POST['resumo-livro'] ?? ''),
                'id_documento' => (int) ($_POST['tipo-documento'] ?? 1),
                'id_idioma' => (int) ($_POST['idioma'] ?? 1),
                'id_area' => (int) ($_POST['area'] ?? 1)
            ];

            // Buscar foto atual
            $livro_atual = $this->livro_model->getLivroPorId($id_livro);
            $dados['foto'] = $livro_atual['foto'] ?? 'uploads/default-capa.jpg';

            // Processar upload de nova capa (se houver)
            if (isset($_FILES['capa-livro']) && $_FILES['capa-livro']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['capa-livro'];
                
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = __DIR__ . '/../../../public/uploads/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (!in_array($extensao, $extensoes_permitidas)) {
                        throw new Exception('Formato inválido. Use: JPG, PNG, GIF ou WebP.');
                    }
                    
                    $nome_arquivo = 'capa_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extensao;
                    $caminho_completo = $upload_dir . $nome_arquivo;
                    
                    if (move_uploaded_file($file['tmp_name'], $caminho_completo)) {
                        // Deletar imagem antiga (se não for default)
                        if (!empty($livro_atual['foto']) && $livro_atual['foto'] !== 'uploads/default-capa.jpg') {
                            $arquivo_antigo = __DIR__ . '/../../../public/' . $livro_atual['foto'];
                            if (file_exists($arquivo_antigo)) {
                                unlink($arquivo_antigo);
                            }
                        }
                        
                        $dados['foto'] = 'uploads/' . $nome_arquivo;
                        error_log("Nova capa salva: $caminho_completo");
                    }
                }
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

            // Atualizar no banco
            $sucesso = $this->livro_model->atualizarLivro($dados);

            if (!$sucesso) {
                throw new Exception('Erro ao atualizar livro no banco de dados.');
            }

            $mensagem_sucesso = "Livro '{$dados['titulo']}' atualizado com sucesso!";
            
            if ($isAjax) {
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => $mensagem_sucesso
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
                header("Location: ./telaDosLivrosCadastrados.php");
                exit;
            }

        } catch (Exception $e) {
            error_log("EditarLivroController - Erro: {$e->getMessage()}");
            
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
                header("Location: ./telaDosLivrosCadastrados.php");
                exit;
            }
        }
    }
}