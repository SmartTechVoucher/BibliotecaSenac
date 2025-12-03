
<?php
require_once __DIR__ . '/../../../config/db/database.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) throw new Exception('Falha na conexão com o banco.');
    }

    public function getStats() {
        $totalLivros = $this->conn->query("SELECT COUNT(*) as total FROM livros")->fetch(PDO::FETCH_ASSOC)['total'];
        $totalUsuarios = $this->conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch(PDO::FETCH_ASSOC)['total'];
        $ativos = $this->conn->query("SELECT COUNT(*) as total FROM movimentacoes WHERE status = 'Emprestado'")->fetch(PDO::FETCH_ASSOC)['total'];
        $totalEmprestimos = $this->conn->query("SELECT COUNT(*) as total FROM movimentacoes")->fetch(PDO::FETCH_ASSOC)['total'];
        $devolvidos = $this->conn->query("SELECT COUNT(*) as total FROM movimentacoes WHERE status = 'Devolvido'")->fetch(PDO::FETCH_ASSOC)['total'];
        $taxaDevolucao = $totalEmprestimos ? round(($devolvidos / $totalEmprestimos) * 100) . '%' : '0%';

        return [
            ["title"=>"Total de Livros","value"=>$totalLivros,"icon"=>"📚","color"=>"#004A90"],
            ["title"=>"Usuários Cadastrados","value"=>$totalUsuarios,"icon"=>"👥","color"=>"#28a745"],
            ["title"=>"Empréstimos Ativos","value"=>$ativos,"icon"=>"📄","color"=>"#fd7e14"],
            ["title"=>"Taxa de Devolução","value"=>$taxaDevolucao,"icon"=>"📈","color"=>"#6f42c1"]
        ];
    }

    public function getRecentActivities($limit = 5) {
        $atividades = [];

        // Últimos usuários cadastrados
        $stmt = $this->conn->query("SELECT nome, data_criacao FROM usuarios ORDER BY data_criacao DESC LIMIT $limit");
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $usuario) {
            $atividades[] = [
                'tipo' => 'Usuário',
                'descricao' => "Novo usuário cadastrado: {$usuario['nome']}",
                'data' => $usuario['data_criacao']
            ];
        }

        // Últimos livros adicionados (não tem data, então usamos id_livro)
        $stmt = $this->conn->query("SELECT titulo, id_livro FROM livros ORDER BY id_livro DESC LIMIT $limit");
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $livro) {
            $atividades[] = [
                'tipo' => 'Livro',
                'descricao' => "Novo livro cadastrado: {$livro['titulo']}",
                'data' => 'Recente'
            ];
        }

        // Últimas movimentações
        $stmt = $this->conn->query("
            SELECT m.status, m.data_movimentacao, l.titulo 
            FROM movimentacoes m 
            JOIN livros l ON m.id_livro = l.id_livro 
            ORDER BY m.data_movimentacao DESC 
            LIMIT $limit
        ");
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $mov) {
            $atividades[] = [
                'tipo' => 'Movimentação',
                'descricao' => "{$mov['status']} do livro: {$mov['titulo']}",
                'data' => $mov['data_movimentacao']
            ];
        }

        return $atividades;
    }



    public function getTopBooks($limit=5) {
    $stmt = $this->conn->query("
        SELECT l.titulo, a.nome AS autor, COUNT(m.id_movimentacao) AS total
        FROM livros l
        JOIN autores a ON l.id_autor = a.id_autor
        JOIN movimentacoes m ON l.id_livro = m.id_livro
        WHERE m.status IN ('Emprestado','Devolvido')
        GROUP BY l.id_livro, a.nome
        ORDER BY total DESC
        LIMIT $limit
    ");

    $topBooks = [];
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $book) {
        $topBooks[] = [
            'title' => $book['titulo'],
            'author' => $book['autor'],
            'count' => $book['total']
        ];
    }

    return $topBooks;
}

    private function tempoRelativo($datetime) {
        $diff = time() - strtotime($datetime);
        if($diff < 60) return 'há '.$diff.' segundos';
        $min = floor($diff/60); if($min<60) return 'há '.$min.' minutos';
        $h = floor($diff/3600); if($h<24) return 'há '.$h.' horas';
        $d = floor($diff/86400); return 'há '.$d.' dias';
    }
}
