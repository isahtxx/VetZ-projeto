<?php
require_once '../config/database_site.php';

class Pet {
    private $conn;
    private $table_name = "pets";

    public $id;
    public $nome;
    public $raca;
    public $idade;
    public $porte;
    public $peso;
    public $sexo;
    public $imagem;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();

            if (!$this->conn) {
                throw new Exception("Falha ao conectar com o banco de dados.");
            }
        } catch (PDOException $e) {
            die("Erro na conexão PDO: " . $e->getMessage());
        } catch (Exception $e) {
            die("Erro: " . $e->getMessage());
        }
    }

    public function save() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nome, raca, idade, porte, peso, sexo, imagem) 
                  VALUES (:nome, :raca, :idade, :porte, :peso, :sexo, :imagem)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':raca', $this->raca);
        $stmt->bindParam(':idade', $this->idade);
        $stmt->bindParam(':porte', $this->porte);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':imagem', $this->imagem);

        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome = :nome, raca = :raca, idade = :idade, 
                      porte = :porte, peso = :peso, sexo = :sexo";

        if (!empty($this->imagem)) {
            $query .= ", imagem = :imagem";
        }

        $query .= " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':raca', $this->raca);
        $stmt->bindParam(':idade', $this->idade);
        $stmt->bindParam(':porte', $this->porte);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':sexo', $this->sexo);

        if (!empty($this->imagem)) {
            $stmt->bindParam(':imagem', $this->imagem);
        }

        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT id, nome FROM " . $this->table_name);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarVacinas($id_pet) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vacinacao WHERE id_pet = ?");
        $stmt->execute([$id_pet]);
        $quantidade = $stmt->fetchColumn();
        return $quantidade == 0;
    }

    public function getPetsByUsuario($usuarioId) {
        $query = "SELECT * FROM pets WHERE id_usuario = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
