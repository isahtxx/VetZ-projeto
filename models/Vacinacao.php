<?php
require_once '../config/database_site.php';

class Vacinacao {
    private $conn;
    private $table_name = "vacinacao";

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

    public function cadastrar($data, $doses, $id_vacina, $id_pet) {
        $query = "INSERT INTO vacinacao (data, doses, id_vacina, id_pet)
                  VALUES (:data, :doses, :id_vacina, :id_pet)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':doses', $doses);
        $stmt->bindParam(':id_vacina', $id_vacina);
        $stmt->bindParam(':id_pet', $id_pet);
        return $stmt->execute();
    }

    public function editar($id, $data, $doses, $id_vacina, $id_pet) {
        $query = "UPDATE vacinacao SET data = :data, doses = :doses, 
                  id_vacina = :id_vacina, id_pet = :id_pet
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':doses', $doses);
        $stmt->bindParam(':id_vacina', $id_vacina);
        $stmt->bindParam(':id_pet', $id_pet);
        $stmt->bindParam(':id_usuario', $id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM vacinacao WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluirPorPet($id_pet) {
        $query = "DELETE FROM vacinacao WHERE id_pet = :id_pet";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pet', $id_pet);
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM vacinacao WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarVacinas() {
        $sql = "SELECT id_vacina, vacina AS nome_vacina FROM registro_vacina";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listar() {
        $sql = "SELECT 
                v.id, 
                v.data, 
                v.doses, 
                rv.vacina AS nome_vacina, 
                p.nome AS nome_pet,
                u.nome AS nome_tutor
            FROM vacinacao v
            INNER JOIN registro_vacina rv ON v.id_vacina = rv.id_vacina
            INNER JOIN pets p ON v.id_pet = p.id
            INNER JOIN usuarios u ON p.id_usuario = u.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function listarPorPet($id_pet) {
        $sql = "SELECT v.id, v.data, v.doses, rv.vacina AS nome_vacina
                FROM vacinacao v
                INNER JOIN registro_vacina rv ON v.id_vacina = rv.id_vacina
                WHERE v.id_pet = :id_pet";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_pet', $id_pet);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function petTemVacina($id_pet) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vacinacao WHERE id_pet = :id_pet");
        $stmt->bindParam(':id_pet', $id_pet);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
