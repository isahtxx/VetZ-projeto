<?php 
require_once '../models/Vacinacao.php';

// Obtém o ID da vacinação a ser atualizada através do parâmetro GET da URL
$id = $_GET['id'] ?? null; // Caso o ID não esteja presente, a variável $id receberá 'null'

if (!$id) { // Se o ID não for fornecido (null ou vazio)
    echo "ID da vacinação não fornecido."; // Exibe mensagem de erro
    exit; // Interrompe a execução do script
}

// Instancia o modelo de vacinação
$vacinacaoModel = new Vacinacao();
// Busca no banco de dados os dados da vacinação correspondente ao ID informado
$vacina = $vacinacaoModel->buscarPorId($id);

if (!$vacina) { // Se nenhuma vacinação for encontrada com o ID informado
    echo "Vacinação não encontrada."; // Exibe mensagem de erro
    exit; // Interrompe a execução do script
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Vacinação</title>
</head>
<body>
    <h1>Atualizar Vacinação</h1>

    <!-- Formulário para atualização dos dados da vacinação -->
    <form action="/projeto/vetz/update-vacinacao" method="POST"> <!-- Envia os dados para a rota de atualização -->
        <!-- Campo oculto que armazena o ID da vacinação para garantir que o registro correto seja atualizado -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($vacina['id']) ?>">

        <!-- Campo para seleção/alteração da data da vacinação -->
        <label for="data">Data:</label>
        <input type="date" name="data" value="<?= htmlspecialchars($vacina['data']) ?>" required><br>

        <!-- Campo para inserção/alteração da quantidade de doses aplicadas -->
        <label for="doses">Doses:</label>
        <input type="number" name="doses" min="1" value="<?= htmlspecialchars($vacina['doses']) ?>" required><br>

        <!-- Campo para inserção/alteração do ID da vacina aplicada -->
        <label for="id_vacina">ID da Vacina:</label>
        <input type="number" name="id_vacina" value="<?= htmlspecialchars($vacina['id_vacina']) ?>" required><br>

        <!-- Campo para inserção/alteração do ID do pet que recebeu a vacina -->
        <label for="id_pet">ID do Pet:</label>
        <input type="number" name="id_pet" value="<?= htmlspecialchars($vacina['id_pet']) ?>" required><br>

        <!-- Campo oculto que mantém o ID do usuário responsável pela vacinação (não editável) -->
        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($vacina['id_usuario']) ?>">

        <!-- Botão para submeter o formulário e realizar a atualização dos dados -->
        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
