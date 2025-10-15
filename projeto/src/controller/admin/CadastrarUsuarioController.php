<?php
// CadastrarUsuarioController.php

require_once __DIR__ . '/../usuario/usuario-controller.php';

class CadastrarUsuarioController {
    private $usuarioController;

    public function __construct() {
        $this->usuarioController = new UsuarioController();
    }

    /**
     * Processa o cadastro de um novo usuário
     * @return array ['success' => bool, 'message' => string]
     */
    public function cadastrar() {
        // Validar método HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Método inválido'];
        }

        // Coletar dados do formulário
        $dados = $this->coletarDadosFormulario();

        // Validar dados obrigatórios
        $validacao = $this->validarDadosObrigatorios($dados);
        if (!$validacao['success']) {
            return $validacao;
        }

        // Validar senhas
        if ($dados['senha'] !== $dados['senha_confirm']) {
            return ['success' => false, 'message' => 'As senhas não coincidem!'];
        }

        // Processar upload da foto
        $fotoResultado = $this->processarUploadFoto();
        if (!$fotoResultado['success']) {
            return $fotoResultado;
        }

        $dados['foto_perfil'] = $fotoResultado['nome_arquivo'];

        // Ajustar valores para o banco
        $dados = $this->ajustarValoresParaBanco($dados);

        // Criar usuário no banco
        return $this->usuarioController->criarUsuario(
            $dados['nome'],
            $dados['nome_social'],
            $dados['cpf'],
            $dados['email'],
            $dados['data_nascimento'],
            $dados['telefone'],
            $dados['endereco'],
            $dados['genero'],
            $dados['foto_perfil'],
            $dados['numero_matricula'],
            $dados['categoria'],
            $dados['unidade_senac'],
            $dados['curso'],
            $dados['turma'],
            $dados['data_fim_curso'],
            $dados['notas_usuario'],
            $dados['senha']
        );
    }

    /**
     * Coleta dados do formulário POST
     * @return array
     */
    private function coletarDadosFormulario() {
        return [
            'nome' => trim($_POST['nome'] ?? ''),
            'nome_social' => trim($_POST['nome_social'] ?? ''),
            'cpf' => trim($_POST['cpf'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'data_nascimento' => $_POST['data_nascimento'] ?? '',
            'telefone' => trim($_POST['telefone'] ?? ''),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'genero' => $_POST['genero'] ?? '',
            'numero_matricula' => trim($_POST['matricula'] ?? ''),
            'categoria' => $_POST['categoria'] ?? '',
            'unidade_senac' => $_POST['unidade_senac'] ?? '',
            'curso' => trim($_POST['curso'] ?? ''),
            'turma' => trim($_POST['turma'] ?? ''),
            'data_fim_curso' => $_POST['data_fim_curso'] ?? '',
            'notas_usuario' => trim($_POST['notas_usuario'] ?? ''),
            'senha' => $_POST['senha_usuario'] ?? '',
            'senha_confirm' => $_POST['senha_usuario_confirm'] ?? '',
            'foto_perfil' => ''
        ];
    }

    /**
     * Valida campos obrigatórios
     * @param array $dados
     * @return array
     */
    private function validarDadosObrigatorios($dados) {
        $camposObrigatorios = ['nome', 'cpf', 'email', 'data_nascimento', 'categoria', 'unidade_senac', 'senha'];
        
        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                return ['success' => false, 'message' => 'Campos obrigatórios não preenchidos!'];
            }
        }

        return ['success' => true];
    }

    /**
     * Processa upload da foto de perfil
     * @return array ['success' => bool, 'nome_arquivo' => string, 'message' => string]
     */
    private function processarUploadFoto() {
        // Se não houver foto, retorna sucesso com nome vazio
        if (!isset($_FILES['foto-usuario']) || $_FILES['foto-usuario']['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'nome_arquivo' => ''];
        }

        // Se houver erro no upload
        if ($_FILES['foto-usuario']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erro ao fazer upload da foto!'];
        }

        $upload_dir = __DIR__ . '/../../../uploads/perfil/';
        
        // Criar diretório se não existir
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $arquivo = $_FILES['foto-usuario'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            return ['success' => false, 'message' => 'Formato de imagem não suportado!'];
        }

        $nome_arquivo = 'perfil_' . uniqid() . '.' . $extensao;
        $caminho_completo = $upload_dir . $nome_arquivo;

        if (!move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
            return ['success' => false, 'message' => 'Erro ao salvar a foto!'];
        }

        return ['success' => true, 'nome_arquivo' => $nome_arquivo];
    }

    /**
     * Ajusta valores do formulário para formato do banco de dados
     * @param array $dados
     * @return array
     */
    private function ajustarValoresParaBanco($dados) {
        // Ajustar gênero
        $dados['genero'] = match($dados['genero']) {
            'masculino' => 'Masculino',
            'feminino' => 'Feminino',
            'nao_binario' => 'Não binario',
            'outros' => 'Outros',
            'nao_informar' => 'Não informar',
            default => null
        };

        // Ajustar categoria
        $dados['categoria'] = match($dados['categoria']) {
            'graduacao' => 'Aluno',
            'pos' => 'Docente', 
            'extensao' => 'Bibliotecario',
            default => $dados['categoria']
        };

        // Ajustar unidade
        $dados['unidade_senac'] = match($dados['unidade_senac']) {
            'senac_hub' => 'Senac Hub Academy',
            'senac_dou' => 'Senac Dourados',
            'senac_tres' => 'Senac Três Lagoas',
            default => $dados['unidade_senac']
        };

        return $dados;
    }
}