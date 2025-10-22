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