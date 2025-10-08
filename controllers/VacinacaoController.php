<?php
require_once '../models/Vacinacao.php';
require_once '../models/Pet.php';

class VacinacaoController {

    private $usuarioId;

    public function __construct($usuarioId = null) {
        $this->usuarioId = $usuarioId; // id do usuário logado
    }

    // Listar todas as vacinações
    public function listVacina() {
        $model = new Vacinacao();
        $vacinas = $model->listar();
        include '../views/vacinacao_list.php';
    }

    // Listar vacinações de um usuário específico
    public function listarVacinasUsuario() {
        if (!$this->usuarioId) return [];

        $petModel = new Pet();
        $vacinacaoModel = new Vacinacao();

        // Pega todos os pets do usuário
        $pets = $petModel->getPetsByUsuario($this->usuarioId);

        $vacinasUsuario = [];
        foreach ($pets as $pet) {
            $vacinas = $vacinacaoModel->listarPorPet($pet['id']);
            foreach ($vacinas as $v) {
                $v['nome_pet'] = $pet['nome'];
                $vacinasUsuario[] = $v;
            }
        }

        return $vacinasUsuario;
    }

    // Exibir formulário de cadastro
    public function exibirFormulario() {
        $vacinacaoModel = new Vacinacao();
        $vacinas = $vacinacaoModel->listarVacinas();

        $pets = (new Pet())->getPetsByUsuario($this->usuarioId);
        include '../views/vacinacao_geral.php';
    }

    // Cadastrar vacinação
    public function cadastrarVacina() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vacinacao = new Vacinacao();
            $data = $_POST['data'];
            $doses = $_POST['doses'];
            $id_vacina = $_POST['id_vacina'];
            $id_pet = $_POST['id_pet'];

            if ($vacinacao->cadastrar($data, $doses, $id_vacina, $id_pet)) {
                header('Location: ../views/vacinacao_geral.php');
                exit;
            } else {
                echo "Erro ao cadastrar a vacina.";
            }
        }
    }

    // Editar vacinação
    public function editar($id, $data, $doses, $id_vacina, $id_pet) {
        $model = new Vacinacao();
        $model->editar($id, $data, $doses, $id_vacina, $id_pet);
        header("Location: ../views/vacinacao_pet.php");
        exit;
    }

    // Excluir vacinação
    public function excluir($id) {
        $model = new Vacinacao();
        $model->excluir($id);
        header("Location: ../views/vacinacao_pet.php");
        exit;
    }

    // Buscar vacinação por ID
    public function buscarPorId($id) {
        $model = new Vacinacao();
        return $model->buscarPorId($id);
    }
}
