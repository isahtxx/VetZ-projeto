<?php
require_once '../models/Pet.php';
require_once '../models/Vacinacao.php'; // se você já tiver criado

class PetController {
    public function showForm() {
        include '../views/pet_form.php';
    }

    public function savePet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pet = new Pet();
            $pet->nome  = $_POST['nome'];
            $pet->raca  = $_POST['raca'];
            $pet->idade = $_POST['idade'];
            $pet->porte = $_POST['porte'];
            $pet->peso  = $_POST['peso'];
            $pet->sexo  = $_POST['sexo'];

            // Upload de imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $nomeImagem = uniqid() . '.' . $extensao;
                $caminhoDestino = __DIR__ . '/../uploads/' . $nomeImagem;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoDestino)) {
                    $pet->imagem = $nomeImagem;
                } else {
                    echo "Erro ao mover a imagem.";
                    return;
                }
            }

            if ($pet->save()) {
                header('Location: /projeto/vetz/list-pet');
                exit;
            } else {
                echo "Erro ao cadastrar o pet.";
            }
        }
    }

    public function listPet() {
        $pet = new Pet();
        $pets = $pet->getAll();
        include '../views/pet_list.php';
    }

    public function showUpdateForm($id) {
        $petModel = new Pet();
        $pet = $petModel->getById($id);

        if ($pet) {
            include '../views/update_pet.php';
        } else {
            echo "Pet não encontrado.";
        }
    }

    public function updatePet() {
        $pet = new Pet();
        $pet->id    = $_POST['id'];
        $pet->nome  = $_POST['nome'];
        $pet->raca  = $_POST['raca'];
        $pet->idade = $_POST['idade'];
        $pet->porte = $_POST['porte'];
        $pet->peso  = $_POST['peso'];
        $pet->sexo  = $_POST['sexo'];

        // Só atualiza a imagem se houver upload
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nomeImagem = uniqid() . '.' . $extensao;
            $caminhoDestino = __DIR__ . '/../uploads/' . $nomeImagem;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoDestino)) {
                $pet->imagem = $nomeImagem;
            }
        }

        if ($pet->update()) {
            header('Location: /projeto/vetz/list-pet');
            exit;
        } else {
            echo "Erro ao atualizar o pet.";
        }
    }

    public function deletePetById($id) {
        $petModel = new Pet();
        $naoTemVacinas = $petModel->verificarVacinas($id);

        if ($naoTemVacinas) {
            $petModel->id = $id;
            $petModel->delete();
            header('Location: /projeto/vetz/list-pet');
            exit;
        } else {
            echo "Erro: este pet possui vacinas registradas e não pode ser excluído.";
        }
    }

    public function listarPets() {
        $model = new Pet();
        return $model->listar();
    }

    public function listarPetsComVacinas() {
        $petModel = new Pet();
        $pets = $petModel->getAll();

        $vacinacaoModel = new Vacinacao();

        foreach ($pets as &$pet) {
            $pet['tem_vacina'] = $vacinacaoModel->petTemVacina($pet['id']);
        }
        unset($pet);

        return $pets;
    }
}
