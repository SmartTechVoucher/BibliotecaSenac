<?php
require_once __DIR__ . '/../../../config/db/database.php';
class EmprestimosController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->Connect();
    }

    // 🔹 Buscar usuário
    public function buscarUsuario($busca)
    {
        $sql = "SELECT * FROM usuarios WHERE nome LIKE :busca OR cpf LIKE :busca LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':busca' => "%$busca%"]);
        return $stmt->fetch();
    }

    // 🔹 Livros disponíveis
    public function listarLivrosDisponiveis()
    {
        $sql = "SELECT l.id_livro, l.titulo, a.nome AS autor
                FROM livros l
                JOIN autores a ON l.id_autor = a.id_autor
                JOIN exemplares e ON l.id_livro = e.id_livro
                WHERE e.disponiveis > 0";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // 🔹 Registrar empréstimo
    public function registrarEmprestimo($idUsuario, $idLivro)
    {
        $dataMov = date('Y-m-d H:i:s');
        $dataPrevista = date('Y-m-d', strtotime('+6 days'));

        try {
            $this->conn->beginTransaction();

            $sqlInsert = "INSERT INTO movimentacoes (id_usuario, id_livro, data_movimentacao, data_prevista_devolucao, status)
                          VALUES (:usuario, :livro, :mov, :prev, 'Emprestado')";
            $stmt = $this->conn->prepare($sqlInsert);
            $stmt->execute([
                ':usuario' => $idUsuario,
                ':livro' => $idLivro,
                ':mov' => $dataMov,
                ':prev' => $dataPrevista
            ]);

            $sqlUpdate = "UPDATE exemplares 
                          SET disponiveis = disponiveis - 1, emprestados = emprestados + 1
                          WHERE id_livro = :id";
            $stmt = $this->conn->prepare($sqlUpdate);
            $stmt->execute([':id' => $idLivro]);

            $this->conn->commit();
            return [
                "status" => "success",
                "mensagem" => "✅ Empréstimo registrado! Devolução prevista: " .
                              date('d/m/Y', strtotime($dataPrevista))
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                "status" => "error",
                "mensagem" => "Erro: " . $e->getMessage()
            ];
        }
    }

    // 🔹 Listar empréstimos ativos de um usuário
    public function listarEmprestimosUsuario($idUsuario)
    {
        $sql = "SELECT m.id_movimentacao, l.titulo, m.data_movimentacao, m.data_prevista_devolucao,
                       m.data_real_devolucao, m.status
                FROM movimentacoes m
                JOIN livros l ON m.id_livro = l.id_livro
                WHERE m.id_usuario = :id AND m.status = 'Emprestado'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $idUsuario]);
        return $stmt->fetchAll();
    }

    // 🔹 Renovar empréstimo (+3 dias)
    public function renovarEmprestimo($idMovimentacao)
    {
        try {
            $sql = "UPDATE movimentacoes 
                    SET data_prevista_devolucao = DATE_ADD(data_prevista_devolucao, INTERVAL 3 DAY)
                    WHERE id_movimentacao = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $idMovimentacao]);

            return [
                "status" => "success",
                "mensagem" => "🔁 Empréstimo renovado por mais 3 dias!"
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "mensagem" => "Erro ao renovar: " . $e->getMessage()
            ];
        }
    }

    // 🔹 Devolver livro
    public function devolverLivro($idMovimentacao)
    {
        try {
            $this->conn->beginTransaction();

            // Buscar o livro associado
            $stmt = $this->conn->prepare("SELECT id_livro FROM movimentacoes WHERE id_movimentacao = :id");
            $stmt->execute([':id' => $idMovimentacao]);
            $livro = $stmt->fetchColumn();

            // Atualizar movimentação
            $sqlMov = "UPDATE movimentacoes 
                       SET data_real_devolucao = NOW(), status = 'Devolvido'
                       WHERE id_movimentacao = :id";
            $stmt = $this->conn->prepare($sqlMov);
            $stmt->execute([':id' => $idMovimentacao]);

            // Atualizar exemplares
            $sqlEx = "UPDATE exemplares 
                      SET disponiveis = disponiveis + 1, emprestados = emprestados - 1
                      WHERE id_livro = :livro";
            $stmt = $this->conn->prepare($sqlEx);
            $stmt->execute([':livro' => $livro]);

            $this->conn->commit();
            return [
                "status" => "success",
                "mensagem" => "📦 Livro devolvido com sucesso!"
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                "status" => "error",
                "mensagem" => "Erro na devolução: " . $e->getMessage()
            ];
        }
    }
}
?>