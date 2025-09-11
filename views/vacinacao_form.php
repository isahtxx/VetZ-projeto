<?php
require_once '../controllers/VacinacaoController.php';
require_once '../controllers/PetController.php';
require_once '../controllers/UsuarioController.php';

// Controlador de vacinação
$vacinacaoModel = new Vacinacao();
$vacinas = $vacinacaoModel->listarVacinas(); // Lista de vacinas disponíveis no sistema

// Controlador de pets
$petController = new PetController();
$pets = $petController->listarPets(); // Lista de pets cadastrados no sistema

// Verifica se há um ID recebido via GET para edição de vacinação
$id = $_GET['id'] ?? null; // Se existir um ID na URL, armazena na variável $id; caso contrário, $id será null
$vacinacao = null; // Inicializa a variável $vacinacao como nula

if ($id) { // Se um ID foi passado (ou seja, é uma edição)
    $vacinacao = $vacinacaoController->buscarPorId($id); // Busca os dados da vacinação correspondente ao ID
}
?>

<!DOCTYPE html>
<html lang="pt-br"> <!-- Define o idioma da página como português do Brasil -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <!-- O título da página será "Editar Vacinação" se estiver editando, ou "Cadastrar Vacinação" se for um novo cadastro -->
    <title><?= $id ? "Editar Vacinação" : "Cadastrar Vacinação" ?></title>
</head>
<body>
    <!-- Exibe o título da página de acordo com a ação (edição ou cadastro) -->
    <h1>Cadastrar Vacinação</h1>
<form action="/projeto/vetz/salvar-vacina" method="POST">
    <label>Data:</label>
    <input type="date" name="data" required><br>

    <label>Doses:</label>
    <input type="number" name="doses" required><br>

    <label>Vacina:</label>
<select name="id_vacina" required>
    <option value="">Selecione uma vacina</option> <!-- opção vazia -->
    <?php foreach ($vacinas as $v): ?>
        <option value="<?= $v['id_vacina'] ?>"><?= htmlspecialchars($v['vacina']) ?></option>
    <?php endforeach; ?>
</select><br>

<label>Pet:</label>
<select name="id_pet" required>
    <option value="">Selecione um pet</option> <!-- opção vazia -->
    <?php foreach ($pets as $pet): ?>
        <option value="<?= $pet['id'] ?>"><?= htmlspecialchars($pet['nome']) ?></option>
    <?php endforeach; ?>
</select><br>


    <button type="submit">Cadastrar</button>
</form>

    </form>
</body>
</html>