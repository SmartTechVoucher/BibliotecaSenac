<?php
require_once __DIR__ . '/../../../config/db/database.php';

class LivroInfoModel {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->Connect();
    }

    public function getLivroById($id_livro) {
        $sql = "SELECT l.*, a.nome AS autor_nome, e.nome AS editora_nome
                FROM livros l
                LEFT JOIN autores a ON l.id_autor = a.id
                LEFT JOIN editoras e ON l.id_editora = e.id
                WHERE l.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_livro]);
        return $stmt->fetch();
    }

    public function getExemplaresPorUnidade($id_livro) {
        $sql = "SELECT u.nome AS unidade,
                       COUNT(*) AS total_exemplares,
                       SUM(CASE WHEN status = 'disponivel' THEN 1 ELSE 0 END) AS disponiveis,
                       SUM(CASE WHEN status = 'emprestado' THEN 1 ELSE 0 END) AS emprestados,
                       SUM(CASE WHEN status = 'reservado' THEN 1 ELSE 0 END) AS reservados
                FROM exemplares ex
                INNER JOIN unidades u ON ex.id_unidade = u.id
                WHERE ex.id_livro = ?
                GROUP BY ex.id_unidade";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_livro]);
        return $stmt->fetchAll();
    }

    public function getTotaisExemplares($id_livro) {
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'disponivel' THEN 1 ELSE 0 END) AS disponiveis,
                    SUM(CASE WHEN status = 'emprestado' THEN 1 ELSE 0 END) AS emprestados,
                    SUM(CASE WHEN status = 'reservado' THEN 1 ELSE 0 END) AS reservados
                FROM exemplares
                WHERE id_livro = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_livro]);
        return $stmt->fetch();
    }

    public function getTagsLivro($id_livro) {
        $sql = "SELECT t.nome
                FROM livro_tags lt
                INNER JOIN tags t ON lt.id_tag = t.id
                WHERE lt.id_livro = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_livro]);
        return array_column($stmt->fetchAll(), "nome");
    }

    public function verificarReservaAtiva($id_usuario, $id_livro) {
        $sql = "SELECT id FROM reservas
                WHERE id_usuario = ? AND id_livro = ? AND status = 'ativa'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_usuario, $id_livro]);
        return $stmt->fetch() ? true : false;
    }
}
