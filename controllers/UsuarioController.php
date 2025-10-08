<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    public function loginForm() {
        include '../views/login.php';
    }

    public function cadastrarForm() {
        include '../views/cadastro.php';
    }

    public function cadastrar() {
        $dados = $_POST;
        $model = new Usuario();
        $ok = $model->cadastrar($dados['nome'], $dados['email'], $dados['senha']);

        if ($ok) {
            header('Location: ../vetz/loginForm'); 
            exit;
        } else {
            echo "Erro ao cadastrar.";
        }
    }

    public function login() {
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $senha = isset($_POST['senha']) ? $_POST['senha'] : null;

        if (!$email || !$senha) {
           echo "Por favor, preencha email e senha.";
            return;
        }

    $model = new Usuario();
    $usuario = $model->autenticar($email, $senha);

    if ($usuario) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        header('Location: /projeto/vetz/perfil-usuario?id=' . $usuario['id']);
        exit;
    } else {
        $erro = "Credenciais inválidas.";
        include '../views/login.php';
    }
    }

    public function enviarCodigo() {
        $email = $_POST['email'];
        $codigo = rand(100000, 999999);

        $usuario = new Usuario();
        $usuario->salvarCodigo($email, $codigo);

        echo $codigo; 
        exit;
    }

    public function verificarCodigo() {
        $email = $_POST['email'];
        $codigo = $_POST['codigo'];
        $novaSenha = $_POST['nova_senha'];

        $model = new Usuario();
        $valido = $model->verificarCodigo($email, $codigo);

        if ($valido) {
            $model->redefinirSenha($email, $novaSenha);
            echo "Senha alterada com sucesso!";
        } else {
            echo "Código inválido ou expirado.";
        }
    }

    public function redefinirSenha() {
        $email = $_POST['email'];
        $novaSenha = $_POST['nova_senha'];

        $model = new Usuario();
        $ok = $model->redefinirSenha($email, $novaSenha);

        echo $ok ? "Senha alterada com sucesso!" : "Erro ao alterar senha.";
    }

    public function perfil($id) {
        $usuarioModel = new Usuario();
        return $usuarioModel->buscarPorId($id);
    }

    public function atualizar($dados, $file) {
        $usuarioModel = new Usuario();
        $imagem = null;

        if (isset($file['imagem']) && $file['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagem = basename($file['imagem']['name']);
            move_uploaded_file($file['imagem']['tmp_name'], '../uploads/' . $imagem);
        }

        return $usuarioModel->atualizar(
            $dados['id'],
            $dados['nome'],
            $dados['email'],
            $dados['senha'],
            $imagem
        );
    }

    public function excluir($id) {
        $usuarioModel = new Usuario();
        return $usuarioModel->excluir($id);
    }
}
