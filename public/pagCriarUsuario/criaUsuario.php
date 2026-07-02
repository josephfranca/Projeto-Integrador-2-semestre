<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="../assets/css/criaUsuario.css">
</head>

<body>

  <div class="link">

    <button class="btn-voltar"> <a id="btn-voltar" class="voltar-inicio" href="../pag_acessos/acesso.html">Voltar para Acessos</a></button>
    <div class="form-container">
      <h2>Criar Usuário</h2>
      <form action="criaUsuario.php" id="cadastroForm" method="POST">
        <input class="input-primer" type="text" name="nome" placeholder="Nome" required>
        <input class="input-primer" type="text" name="sobrenome" placeholder="Sobrenome" required>
        <input class="input-primer" type="date" name="nascimento" required>
        <input class="input-primer" type="email" name="email" placeholder="E-mail" required>
        <input class="input-primer" type="password" name="senha" placeholder="Senha" required>

        <select name="sexo" required>
          <option value="">Selecione o sexo</option>
          <option value="Masculino">Masculino</option>
          <option value="Feminino">Feminino</option>
        </select>

        <select name="funcao" required>
          <option value="">Selecione a função</option>
          <option value="Gerente">Gerente</option>
          <option value="Vendedor">Vendedor</option>
          <option value="Administrador">Administrador</option>
        </select>

        <button type="submit" formaction="cadastrarUsuario.php">Cadastrar</button>
      </form>
    </div>
    <script src="./JavaScript/criaUsuario.js"></script>
</body>

</html>