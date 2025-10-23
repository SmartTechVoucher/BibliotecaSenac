<?php
/**
 * Controller para deletar livros
 * Verifica se há empréstimos/reservas ativos antes de deletar
 * Remove a imagem do livro do servidor
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';
require_once __DIR__ . '/../../model/admin/ExemplaresModel.php';
require_once __DIR__ . '/../../../config/db/database.php';

class DeletarLivroController {
    private $livro_model;
    private $exemplares_model;
    private $conn;

    public function __construct() {
        $this->livro_model = new LivroModel();
        $this->exemplares_model = new ExemplaresModel();
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    /**
     * Deleta um livro e suas dependências
     */
    public function deletar() {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST.');
            }

            $id_livro = (int) ($_POST['id_livro'] ?? 0);
            
            if ($id_livro <= 0) {
                throw new Exception('ID do livro inválido.');
            }

            // Buscar livro
            $livro = $this->livro_model->getLivroPorId($id_livro);
            
            if (!$livro) {
                throw new Exception('Livro não encontrado.');
            }

            // Verificar se há empréstimos ativos
            $emprestimos_ativos = $this->verificarEmprestimosAtivos($id_livro);
            
            if ($emprestimos_ativos > 0) {
                throw new Exception("Não é possível deletar este livro pois há $emprestimos_ativos empréstimos ativos. Aguarde as devoluções.");
            }

            // Verificar reservas ativas
            $reservas_ativas = $this->verificarReservasAtivas($id_livro);
            
            if ($reservas_ativas > 0) {
                throw new Exception("Não é possível deletar este livro pois há $reservas_ativas reservas ativas. Cancele as reservas primeiro.");
            }

            // Deletar imagem (se não for default)
            if (!empty($livro['foto']) && $livro['foto'] !== 'uploads/default-capa.jpg') {
                $arquivo_imagem = __DIR__ . '/../../../public/' . $livro['foto'];
                if (file_exists($arquivo_imagem)) {
                    if (unlink($arquivo_imagem)) {
                        error_log("Imagem deletada com sucesso: $arquivo_imagem");
                    } else {
                        error_log("AVISO: Não foi possível deletar a imagem: $arquivo_imagem");
                    }
                }
            }

            // Deletar livro (CASCADE deleta exemplares, favoritos e movimentações antigas)
            $sucesso = $this->livro_model->deletarLivro($id_livro);

            if (!$sucesso) {
                throw new Exception('Erro ao deletar livro no banco de dados.');
            }

            error_log("Livro deletado com sucesso: ID $id_livro - {$livro['titulo']}");

            echo json_encode([
                'sucesso' => true,
                'mensagem' => "Livro '{$livro['titulo']}' deletado com sucesso!"
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("DeletarLivroController - Erro: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    /**
     * Verifica quantos empréstimos ativos existem para o livro
     */
    private function verificarEmprestimosAtivos($id_livro) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM movimentacoes m
                    WHERE m.id_livro = :id_livro
                    AND m.status = 'emprestado'
                    AND m.data_real_devolucao IS NULL";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao verificar empréstimos ativos do livro $id_livro: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Verifica quantas reservas ativas existem para o livro
     */
    private function verificarReservasAtivas($id_livro) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM movimentacoes m
                    WHERE m.id_livro = :id_livro
                    AND m.status = 'reservado'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao verificar reservas ativas do livro $id_livro: " . $e->getMessage());
            return 0;
        }
    }
}