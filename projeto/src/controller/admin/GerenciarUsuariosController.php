<?php
/**
 * Controller para gerenciar usuários (listar, bloquear, desbloquear)
 */

require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/auth-check.php';
require_once __DIR__ . '/../../model/admin/UsuarioModel.php';

header('Content-Type: application/json');

try {
    if (!isAdminLoggedIn()) {
        throw new Exception('Acesso não autorizado');
    }

    $usuarioModel = new UsuarioModel();

    if (!isset($_GET['ajax']) && !isset($_POST['ajax'])) {
        throw new Exception('Acesso não autorizado');
    }

    $acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

    switch ($acao) {
        case 'listar_regulares':
            $pagina = (int)($_GET['pagina'] ?? $_POST['pagina'] ?? 1);
            $limite = (int)($_GET['limite'] ?? $_POST['limite'] ?? 10);
            $busca = $_GET['busca'] ?? $_POST['busca'] ?? '';

            $resultado = $usuarioModel->listarUsuariosRegulares($pagina, $limite, $busca);

            echo json_encode([
                'sucesso' => true,
                'usuarios' => $resultado['usuarios'],
                'total' => $resultado['total'],
                'pagina_atual' => $resultado['pagina_atual'],
                'total_paginas' => $resultado['total_paginas']
            ]);
            break;

        case 'listar_bloqueados':
            $pagina = (int)($_GET['pagina'] ?? $_POST['pagina'] ?? 1);
            $limite = (int)($_GET['limite'] ?? $_POST['limite'] ?? 10);
            $busca = $_GET['busca'] ?? $_POST['busca'] ?? '';

            $resultado = $usuarioModel->listarUsuariosBloqueados($pagina, $limite, $busca);

            echo json_encode([
                'sucesso' => true,
                'usuarios' => $resultado['usuarios'],
                'total' => $resultado['total'],
                'pagina_atual' => $resultado['pagina_atual'],
                'total_paginas' => $resultado['total_paginas']
            ]);
            break;

        case 'bloquearUsuario':
        case 'bloquear_usuario':
            $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

            if ($id_usuario <= 0) {
                throw new Exception('ID de usuário inválido');
            }

            $sucesso = $usuarioModel->bloquearUsuario($id_usuario);

            echo json_encode([
                'sucesso' => $sucesso,
                'mensagem' => $sucesso ? 'Usuário bloqueado com sucesso' : 'Erro ao bloquear usuário'
            ]);
            break;

        case 'desbloquearUsuario':
        case 'desbloquear_usuario':
            $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

            if ($id_usuario <= 0) {
                throw new Exception('ID de usuário inválido');
            }

            $sucesso = $usuarioModel->desbloquearUsuario($id_usuario);

            echo json_encode([
                'sucesso' => $sucesso,
                'mensagem' => $sucesso ? 'Usuário desbloqueado com sucesso' : 'Erro ao desbloquear usuário'
            ]);
            break;

        case 'buscar_usuario':
            $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

            if ($id_usuario <= 0) {
                throw new Exception('ID de usuário inválido');
            }

            $usuario = $usuarioModel->buscarUsuarioPorId($id_usuario);

            echo json_encode([
                'sucesso' => $usuario !== false,
                'usuario' => $usuario ?: null,
                'mensagem' => $usuario ? 'Usuário encontrado' : 'Usuário não encontrado'
            ]);
            break;

        case 'atualizar_usuario':
            $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

            error_log("=== INICIANDO ATUALIZAÇÃO DE USUÁRIO ===");
            error_log("ID do usuário: {$id_usuario}");

            if ($id_usuario <= 0) {
                error_log("ERRO: ID de usuário inválido");
                throw new Exception('ID de usuário inválido');
            }

            // Coletar dados do formulário
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'nome_social' => $_POST['nome_social'] ?? '',
                'email' => $_POST['email'] ?? '',
                'data_nascimento' => $_POST['data_nascimento'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'genero' => $_POST['genero'] ?? '',
                'numero_matricula' => $_POST['numero_matricula'] ?? '',
                'categoria' => $_POST['categoria'] ?? '',
                'unidade_senac' => $_POST['unidade_senac'] ?? '',
                'curso' => $_POST['curso'] ?? '',
                'turma' => $_POST['turma'] ?? '',
                'data_fim_curso' => $_POST['data_fim_curso'] ?? '',
                'notas_usuario' => $_POST['notas_usuario'] ?? ''
            ];

            error_log("Dados recebidos para atualização:");
            foreach ($dados as $campo => $valor) {
                error_log("  {$campo}: '{$valor}'");
            }

            // Validar campos obrigatórios
            if (empty($dados['nome'])) {
                throw new Exception('Nome é obrigatório');
            }

            if (empty($dados['email'])) {
                throw new Exception('Email é obrigatório');
            }

            if (empty($dados['data_nascimento'])) {
                throw new Exception('Data de nascimento é obrigatória');
            }

            // Validar formato da data de nascimento
            $dataNascimento = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
            if (!$dataNascimento || $dataNascimento->format('Y-m-d') !== $dados['data_nascimento']) {
                throw new Exception('Data de nascimento inválida');
            }

            if (empty($dados['categoria'])) {
                throw new Exception('Categoria é obrigatória');
            }

            if (empty($dados['unidade_senac'])) {
                throw new Exception('Unidade Senac é obrigatória');
            }

            // Validar formato do email
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }

            // Verificar se o email já existe para outro usuário
            $usuarioExistente = $usuarioModel->buscarUsuarioPorEmail($dados['email']);
            if ($usuarioExistente && $usuarioExistente['id_usuario'] != $id_usuario) {
                throw new Exception('Email já está em uso por outro usuário');
            }

            // Verificar se o CPF já existe para outro usuário (se fornecido)
            if (!empty($dados['cpf'])) {
                if (!$usuarioModel->validarCPF($dados['cpf'])) {
                    throw new Exception('CPF inválido');
                }

                $usuarioPorCPF = $usuarioModel->buscarUsuarioPorCPF($dados['cpf']);
                if ($usuarioPorCPF && $usuarioPorCPF['id_usuario'] != $id_usuario) {
                    throw new Exception('CPF já está em uso por outro usuário');
                }
            }

            $sucesso = $usuarioModel->atualizarUsuario($id_usuario, $dados);

            echo json_encode([
                'sucesso' => $sucesso,
                'mensagem' => $sucesso ? 'Usuário atualizado com sucesso' : 'Erro ao atualizar usuário'
            ]);
            break;

        case 'estatisticas':
            $estatisticas = $usuarioModel->getEstatisticasUsuarios();

            echo json_encode([
                'sucesso' => true,
                'estatisticas' => $estatisticas
            ]);
            break;

        default:
            throw new Exception('Ação não reconhecida');
    }

} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}

/**
 * Classe para operações relacionadas a usuários
 * Mantém a compatibilidade com o código JavaScript existente
 */
class GerenciarUsuariosController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Método principal para processar requisições
     */
    public function handle() {
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

        switch ($acao) {
            case 'listar_regulares':
                $this->listarUsuariosRegulares();
                break;
            case 'listar_bloqueados':
                $this->listarUsuariosBloqueados();
                break;
            case 'buscar_usuario':
                $this->buscarUsuario();
                break;
            case 'bloquear_usuario':
                $this->bloquearUsuario();
                break;
            case 'desbloquear_usuario':
                $this->desbloquearUsuario();
                break;
            default:
                echo json_encode(['sucesso' => false, 'erro' => 'Ação não reconhecida']);
        }
    }

    //* slk programar 2025 é só enfiar 5 case seguido

    private function listarUsuariosRegulares() {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $limite = (int)($_GET['limite'] ?? 10);
        $busca = $_GET['busca'] ?? '';

        $resultado = $this->usuarioModel->listarUsuariosRegulares($pagina, $limite, $busca);

        echo json_encode([
            'sucesso' => true,
            'usuarios' => $resultado['usuarios'],
            'total' => $resultado['total'],
            'pagina_atual' => $resultado['pagina_atual'],
            'total_paginas' => $resultado['total_paginas']
        ]);
    }

    private function listarUsuariosBloqueados() {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $limite = (int)($_GET['limite'] ?? 10);
        $busca = $_GET['busca'] ?? '';

        $resultado = $this->usuarioModel->listarUsuariosBloqueados($pagina, $limite, $busca);

        echo json_encode([
            'sucesso' => true,
            'usuarios' => $resultado['usuarios'],
            'total' => $resultado['total'],
            'pagina_atual' => $resultado['pagina_atual'],
            'total_paginas' => $resultado['total_paginas']
        ]);
    }

    private function buscarUsuario() {
        $id_usuario = (int)($_GET['id_usuario'] ?? 0);

        if ($id_usuario <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID de usuário inválido']);
            return;
        }

        $usuario = $this->usuarioModel->buscarUsuarioPorId($id_usuario);

        echo json_encode([
            'sucesso' => $usuario !== false,
            'usuario' => $usuario ?: null,
            'erro' => $usuario ? null : 'Usuário não encontrado'
        ]);
    }

    private function bloquearUsuario() {
        $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

        if ($id_usuario <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID de usuário inválido']);
            return;
        }

        $sucesso = $this->usuarioModel->bloquearUsuario($id_usuario);

        echo json_encode([
            'sucesso' => $sucesso,
            'mensagem' => $sucesso ? 'Usuário bloqueado com sucesso' : 'Erro ao bloquear usuário'
        ]);
    }

    private function desbloquearUsuario() {
        $id_usuario = (int)($_GET['id_usuario'] ?? $_POST['id_usuario'] ?? 0);

        if ($id_usuario <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID de usuário inválido']);
            return;
        }

        $sucesso = $this->usuarioModel->desbloquearUsuario($id_usuario);

        echo json_encode([
            'sucesso' => $sucesso,
            'mensagem' => $sucesso ? 'Usuário desbloqueado com sucesso' : 'Erro ao desbloquear usuário'
        ]);
    }
}
?>