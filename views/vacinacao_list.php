<?php
// views/vacinacao/vacina_list.php
// Arquivo responsável por exibir a lista de vacinações registradas no sistema.
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vacinações</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <h1>Vacinações Registradas</h1>

    <!-- Link que leva para o formulário de cadastro de uma nova vacinação -->
    <a href="/projeto/vetz/cadastrar-vacina">
        <button>Cadastrar nova vacinação</button>
    </a>

    <!-- Tabela que exibirá os dados das vacinações registradas -->
    <table border="1" cellpadding="10" cellspacing="0"> <!-- Espaçamento entre as células -->
        <thead>
            <tr>
                <!-- Cabeçalho da tabela com os nomes das colunas -->
                <th>Data</th>
                <th>Doses</th>
                <th>Vacina</th>
                <th>Pet</th>
                <th>Tutor</th> 
                <th>Ações</th> <!-- Coluna reservada para os links de editar e excluir -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vacinacoes)) : ?> <!-- Verifica se existe alguma vacinação registrada na variável $vacinacoes -->
                <?php foreach ($vacinacoes as $vacina) : ?> <!-- Loop que percorre cada item (vacinação) do array $vacinacoes -->
                    <tr>
                        <!-- Exibe os dados de cada vacinação de forma segura -->
                        <td><?= htmlspecialchars($vacina['data']) ?></td> <!-- Data da vacinação -->
                        <td><?= htmlspecialchars($vacina['doses']) ?></td> <!-- Quantidade de doses aplicadas -->
                        <td><?= htmlspecialchars($vacina['vacina']) ?></td> <!-- Nome da vacina aplicada -->
                        <td><?= htmlspecialchars($vacina['nome_pet']) ?></td> <!-- Nome do pet que recebeu a vacina -->
                        <td><?= htmlspecialchars($vacina['nome_tutor']) ?></td> <!-- Nome do pet que recebeu a vacina -->
                        <td>
                            <!-- Link para editar a vacinação selecionada, passando o ID da vacina pela URL -->
                            <a href="/projeto/vetz/editar-vacina/<?= $vacina['id'] ?>">Editar</a> |
                            
                            <!-- Link para excluir a vacinação selecionada, com confirmação de exclusão via JavaScript -->
                            <a href="/projeto/vetz/excluir-vacina/<?= $vacina['id'] ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir esta vacinação?');">
                               Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?> <!-- Caso não existam vacinações registradas -->
                <tr>
                    <td colspan="6">Nenhuma vacinação registrada.</td> <!-- Mensagem informando que não há dados -->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<script>
<!-- Link para excluir a vacinação selecionada, com confirmação de exclusão via JavaScript -->
<a href="/projeto/vetz/excluir-vacina/<?= $vacina['id'] ?>" 
   onclick="return confirm('Tem certeza que deseja excluir esta vacinação?');">
   Excluir
</a>
</script>
</body>
</html>

