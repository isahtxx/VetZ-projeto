<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Pet</title>
</head>
<body>
    <h1>Cadastrar / Atualizar Pet</h1>

    <!-- Se estiver editando, o campo hidden vai ter o id, não vai aparecer -->
     <form action="/projeto/vetz/save-pet" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="raca">Raça:</label>
            <input type="text" id="raca" name="raca" required><br><br>

            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade"><br><br>

            <label for="porte">Porte:</label>
            <select type="text" id="porte" name="porte">
                <option value="">Selecione</option>
                <option value="pequeno">Pequeno</option>
                <option value="medio">Médio</option>
                <option value="grande">Grande</option>
            </select>

            <label for="peso">Peso:</label>
            <input type="number" id="peso" name="peso" required><br><br>

            <label for="sexo">Sexo:</label>
            <select type="text" id="sexo" name="sexo">
                <option value="">Selecione</option>
                <option value="Macho">Macho</option>
                <option value="Fêmea">Fêmea</option>
            </select>
            
            <label>Imagem do Pet:</label>
            <input type="file" name="imagem" accept="image/*" required>

            <input type="submit" value="Cadastrar Pet">
    </form>
</body>
</html>
