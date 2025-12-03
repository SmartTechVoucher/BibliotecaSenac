<?php
class RelatorioModel
{
    private $conn;

    public function __construct()
    {
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    private function montarOrdenacao($campoPadrao, $ordenar)
    {
        switch ($ordenar) {
            case "data_crescente":
                return "ORDER BY $campoPadrao ASC";
            case "data_decrescente":
                return "ORDER BY $campoPadrao DESC";
            case "crescente":
                return "ORDER BY nome ASC";
            case "decrescente":
                return "ORDER BY nome DESC";
            default:
                return "ORDER BY $campoPadrao DESC";
        }
    }

    /** ACERVO — tabela livros */
    public function getAcervo($inicio = null, $fim = null, $ordenar = null)
    {
        $inicio = $inicio ?: "1900-01-01";
        $fim    = $fim ?: "2100-01-01";

        $ordem = $this->montarOrdenacao("data_publicacao", $ordenar);

        $sql = "
            SELECT 
                titulo AS nome,
                COALESCE(data_publicacao, 'Sem data') AS data,
                'Ativo' AS status
            FROM livros
            WHERE 
                (data_publicacao BETWEEN :inicio AND :fim)
                OR data_publicacao IS NULL
            $ordem
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":inicio", $inicio);
        $stmt->bindValue(":fim", $fim);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** EMPRÉSTIMOS — tabela movimentacoes */
    public function getEmprestimos($inicio = null, $fim = null, $ordenar = null)
    {
        // Ajusta datas para cobrir todo o dia
        $inicio = $inicio ? date('Y-m-d 00:00:00', strtotime($inicio)) : "1900-01-01 00:00:00";
        $fim    = $fim ? date('Y-m-d 23:59:59', strtotime($fim)) : "2100-01-01 23:59:59";

        $ordem = $this->montarOrdenacao("m.data_movimentacao", $ordenar);

        $sql = "
            SELECT 
                l.titulo AS nome,
                m.data_movimentacao AS data,
                CASE WHEN m.status IS NULL THEN 'Sem status' ELSE m.status END AS status
            FROM movimentacoes m
            JOIN livros l ON l.id_livro = m.id_livro
            WHERE m.data_movimentacao BETWEEN :inicio AND :fim
            $ordem
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":inicio", $inicio);
        $stmt->bindValue(":fim", $fim);
        $stmt->execute();

        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mensagem caso não encontre registros
        if(empty($dados)) {
            echo "Nenhum registro encontrado para o período $inicio até $fim";
            exit;
        }

        return $dados;
    }

    /** USUÁRIOS — tabela usuarios */
    public function getUsuarios($inicio = null, $fim = null, $ordenar = null)
    {
        $inicio = $inicio ?: "1900-01-01";
        $fim    = $fim ?: date('Y-m-d');

        $ordem = $this->montarOrdenacao("data_criacao", $ordenar);

        $sql = "
            SELECT 
                nome AS nome,
                data_criacao AS data,
                CASE
                    WHEN ativo = 1 THEN 'Ativo'
                    ELSE 'Inativo'
                END AS status
            FROM usuarios
            WHERE data_criacao BETWEEN :inicio AND :fim
            $ordem
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":inicio", $inicio);
        $stmt->bindValue(":fim", $fim);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
