<?php
/**
 * Controller para gerenciar usuários
 * Processa todas as requisições AJAX relacionadas a usuários
 * 
 * @package Controller
 * @author Sistema Biblioteca SENAC
 * @version 2.0
 */

require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/auth-check.php';
require_once __DIR__ . '/../../model/admin/UsuarioModel.php';

// Define cabeçalho JSON para todas as respostas
header('Content-Type: application/json; charset=utf-8');

/**
 * Classe principal do controller
 */
class GerenciarUsuariosController {
    private $usuarioModel;
    private $response;

    /**
     * Construtor - Inicializa o model e estrutura de resposta
     */
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->response = [
            'sucesso' => false,
            'mensagem' => '',
            'erro' => '',
            'data' => null
        ];
    }

    /**
     * Método principal - Processa requisições
     */
    public function processar() {
        try {
            // Verifica autenticação
            if (!isAdminLoggedIn()) {
                $this->enviarErro('Acesso não autorizado', 401);
                return;
            }

            // Verifica se é requisição AJAX
            if (!$this->isAjaxRequest()) {
                $this->enviarErro('Acesso não autorizado', 403);
                return;
            }

            // Obtém ação solicitada
            $acao = $this->getAcao();

            // Processa ação
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

                case 'atualizar_usuario':
                    $this->atualizarUsuario();
                    break;

                case 'bloquearUsuario':
                case 'bloquear_usuario':
                    $this->bloquearUsuario();
                    break;

                case 'desbloquearUsuario':
                case 'desbloquear_usuario':
                    $this->desbloquearUsuario();
                    break;

                case 'estatisticas':
                    $this->obterEstatisticas();
                    break;

                default:
                    $this->enviarErro('Ação não reconhecida');
            }

        } catch (Exception $e) {
            error_log("Erro no controller: " . $e->getMessage());
            $this->enviarErro('Erro interno no servidor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lista usuários regulares (ativos)
     */
    private function listarUsuariosRegulares() {
        try {
            $pagina = $this->getInt('pagina', 1);
            $limite = $this->getInt('limite', 10);
            $busca = $this->getString('busca', '');

            $resultado = $this->usuarioModel->listarUsuariosRegulares($pagina, $limite, $busca);

            $this->enviarSucesso([
                'usuarios' => $resultado['usuarios'],
                'total' => $resultado['total'],
                'pagina_atual' => $resultado['pagina_atual'],
                'total_paginas' => $resultado['total_paginas']
            ]);

        } catch (Exception $e) {
            $this->enviarErro('Erro ao listar usuários regulares: ' . $e->getMessage());
        }
    }

    /**
     * Lista usuários bloqueados (inativos)
     */
    private function listarUsuariosBloqueados() {
        try {
            $pagina = $this->getInt('pagina', 1);
            $limite = $this->getInt('limite', 10);
            $busca = $this->getString('busca', '');

            $resultado = $this->usuarioModel->listarUsuariosBloqueados($pagina, $limite, $busca);

            $this->enviarSucesso([
                'usuarios' => $resultado['usuarios'],
                'total' => $resultado['total'],
                'pagina_atual' => $resultado['pagina_atual'],
                'total_paginas' => $resultado['total_paginas']
            ]);

        } catch (Exception $e) {
            $this->enviarErro('Erro ao listar usuários bloqueados: ' . $e->getMessage());
        }
    }

    /**
     * Busca usuário por ID
     */
    private function buscarUsuario() {
        try {
            $id_usuario = $this->getInt('id_usuario');

            if ($id_usuario <= 0) {
                $this->enviarErro('ID de usuário inválido');
                return;
            }

            $usuario = $this->usuarioModel->buscarUsuarioPorId($id_usuario);

            if ($usuario === false) {
                $this->enviarErro('Usuário não encontrado');
                return;
            }

            $this->enviarSucesso([
                'usuario' => $usuario
            ], 'Usuário encontrado com sucesso');

        } catch (Exception $e) {
            $this->enviarErro('Erro ao buscar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza dados do usuário
     */
    private function atualizarUsuario() {
        try {
            $id_usuario = $this->getInt('id_usuario');

            if ($id_usuario <= 0) {
                $this->enviarErro('ID de usuário inválido');
                return;
            }

            // Coletar dados do formulário
            $dados = [
                'nome' => $this->getString('nome'),
                'nome_social' => $this->getString('nome_social'),
                'email' => $this->getString('email'),
                'data_nascimento' => $this->getString('data_nascimento'),
                'telefone' => $this->getString('telefone'),
                'endereco' => $this->getString('endereco'),
                'genero' => $this->getString('genero'),
                'numero_matricula' => $this->getString('numero_matricula'),
                'categoria' => $this->getString('categoria'),
                'unidade_senac' => $this->getString('unidade_senac'),
                'curso' => $this->getString('curso'),
                'turma' => $this->getString('turma'),
                'data_fim_curso' => $this->getString('data_fim_curso'),
                'notas_usuario' => $this->getString('notas_usuario')
            ];

            // Validações básicas
            if (empty($dados['nome'])) {
                $this->enviarErro('Nome é obrigatório');
                return;
            }

            if (empty($dados['email'])) {
                $this->enviarErro('Email é obrigatório');
                return;
            }

            // Valida formato do email
            if (!$this->usuarioModel->validarEmail($dados['email'])) {
                $this->enviarErro('Email inválido');
                return;
            }

            // Verifica se email já existe para outro usuário
            $usuarioExistente = $this->usuarioModel->buscarUsuarioPorEmail($dados['email']);
            if ($usuarioExistente && $usuarioExistente['id_usuario'] != $id_usuario) {
                $this->enviarErro('Email já está em uso por outro usuário');
                return;
            }

            // Valida data de nascimento se fornecida
            if (!empty($dados['data_nascimento'])) {
                $dataNascimento = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
                if (!$dataNascimento || $dataNascimento->format('Y-m-d') !== $dados['data_nascimento']) {
                    $this->enviarErro('Data de nascimento inválida');
                    return;
                }
            }

            // Atualiza usuário
            $sucesso = $this->usuarioModel->atualizarUsuario($id_usuario, $dados);

            if ($sucesso) {
                $this->enviarSucesso(null, 'Usuário atualizado com sucesso');
            } else {
                $this->enviarErro('Erro ao atualizar usuário');
            }

        } catch (Exception $e) {
            $this->enviarErro('Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Bloqueia um usuário
     */
    private function bloquearUsuario() {
        try {
            $id_usuario = $this->getInt('id_usuario');

            if ($id_usuario <= 0) {
                $this->enviarErro('ID de usuário inválido');
                return;
            }

            $sucesso = $this->usuarioModel->bloquearUsuario($id_usuario);

            if ($sucesso) {
                $this->enviarSucesso(null, 'Usuário bloqueado com sucesso');
            } else {
                $this->enviarErro('Erro ao bloquear usuário. Verifique se o usuário está ativo.');
            }

        } catch (Exception $e) {
            $this->enviarErro('Erro ao bloquear usuário: ' . $e->getMessage());
        }
    }

    /**
     * Desbloqueia um usuário
     */
    private function desbloquearUsuario() {
        try {
            $id_usuario = $this->getInt('id_usuario');

            if ($id_usuario <= 0) {
                $this->enviarErro('ID de usuário inválido');
                return;
            }

            $sucesso = $this->usuarioModel->desbloquearUsuario($id_usuario);

            if ($sucesso) {
                $this->enviarSucesso(null, 'Usuário desbloqueado com sucesso');
            } else {
                $this->enviarErro('Erro ao desbloquear usuário. Verifique se o usuário está bloqueado.');
            }

        } catch (Exception $e) {
            $this->enviarErro('Erro ao desbloquear usuário: ' . $e->getMessage());
        }
    }

    /**
     * Obtém estatísticas dos usuários
     */
    private function obterEstatisticas() {
        try {
            $estatisticas = $this->usuarioModel->getEstatisticasUsuarios();

            $this->enviarSucesso([
                'estatisticas' => $estatisticas
            ]);

        } catch (Exception $e) {
            $this->enviarErro('Erro ao obter estatísticas: ' . $e->getMessage());
        }
    }

    /**
     * Helpers - Métodos auxiliares
     */

    /**
     * Verifica se é requisição AJAX
     */
    private function isAjaxRequest() {
        return isset($_GET['ajax']) || isset($_POST['ajax']);
    }

    /**
     * Obtém ação da requisição
     */
    private function getAcao() {
        return $_GET['acao'] ?? $_POST['acao'] ?? '';
    }

    /**
     * Obtém valor inteiro da requisição
     */
    private function getInt($key, $default = 0) {
        $value = $_GET[$key] ?? $_POST[$key] ?? $default;
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Obtém valor string da requisição
     */
    private function getString($key, $default = '') {
        $value = $_GET[$key] ?? $_POST[$key] ?? $default;
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    /**
     * Envia resposta de sucesso
     */
    private function enviarSucesso($data = null, $mensagem = '') {
        $this->response['sucesso'] = true;
        $this->response['mensagem'] = $mensagem;
        
        if ($data !== null) {
            foreach ($data as $key => $value) {
                $this->response[$key] = $value;
            }
        }

        echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Envia resposta de erro
     */
    private function enviarErro($erro, $httpCode = 400) {
        http_response_code($httpCode);
        
        $this->response['sucesso'] = false;
        $this->response['erro'] = $erro;

        echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Execução
try {
    $controller = new GerenciarUsuariosController();
    $controller->processar();
} catch (Exception $e) {
    error_log("Erro fatal no controller: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Erro interno no servidor'
    ], JSON_UNESCAPED_UNICODE);
}
?>