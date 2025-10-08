<?php
require_once __DIR__ . '/../config/database_site.php';

class Usuario {
    private $conn;

    public $nome;
    public $email; 
    public $senha;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function cadastrar($nome, $email, $senha) {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($sql);

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        return $stmt->execute();
    }

    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    public function salvarCodigo($email, $codigo) {
        $sql = "UPDATE usuarios 
                   SET codigo_recuperacao = :codigo, 
                       codigo_expira = NOW() + INTERVAL 10 MINUTE 
                 WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':codigo' => $codigo,
            ':email' => $email
        ]);
    }

    public function verificarCodigo($email, $codigo) {
        $sql = "SELECT * FROM usuarios 
                  WHERE email = :email 
                    AND codigo_recuperacao = :codigo 
                    AND codigo_expira > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':codigo' => $codigo
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function redefinirSenha($email, $novaSenha) {
        $sql = "UPDATE usuarios 
                   SET senha = :senha, 
                       codigo_recuperacao = NULL, 
                       codigo_expira = NULL 
                 WHERE email = :email";
        $stmt = $this->conn->prepare($sql);

        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':senha' => $senhaHash,
            ':email' => $email
        ]);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $email, $senha, $imagem = null) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        if ($imagem) {
            $sql = "UPDATE usuarios 
                       SET nome = :nome, email = :email, senha = :senha, imagem = :imagem 
                     WHERE id = :id";
        } else {
            $sql = "UPDATE usuarios 
                       SET nome = :nome, email = :email, senha = :senha 
                     WHERE id = :id";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        if ($imagem) {
            $stmt->bindParam(':imagem', $imagem);
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
