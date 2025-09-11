<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - VetZ-</title>
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="views\css\style.css">
</head>
<body>

  <!-- Cabeçalho -->
  <header class="topo">
    <div class="logo-box">
      <img src="logo.png" alt="Logo ">
      <span class="titulo">Vetz</span>
    </div>
    <button class="voltar" onclick="history.back()">VOLTAR</button>
  </header>

  <!-- Conteúdo Principal -->
  <main>
    <div class="login-box">
      <h2 class="login-title">LOGIN</h2>
      <form action="/projeto/vetz/views/perfil_usuario.html" method="POST">
    <input name="email" type="email" placeholder="Email" required>
    <input name="senha" type="password" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>  

      <div class="links">
        <a href="/projeto/vetz/cadastrarForm">Criar conta</a> 
        <br>
        <a href="/projeto/vetz/recuperarForm">Esqueceu a senha?</a>
      </div>

      <!-- Gif fofo abaixo do link -->
      <img src="https://media.giphy.com/media/3o6Zt6ML6BklcajjsA/giphy.gif" alt="Cachorro e gato animados" class="bichinho">
    </div>
  </main>              

  <!-- Rodapé -->
  <footer class="rodape">
    <p>© 2025 VetZ- Todos os direitos reservados.</p>
  </footer>    

</body>
</html>