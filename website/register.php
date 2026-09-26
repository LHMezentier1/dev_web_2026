<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar — Biblioteca</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php
  require_once './includes/bdconnect.php';
  require_once './includes/models/usuario.php';
  session_start();
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $senha = trim($_POST['senha']);
      $nome = trim($_POST['nome']);
      if(str_contains(strtolower($nome), "drop table")) $erro = "Nome invalido.";
      else if(!(Usuario::findByEmail($email) === null)) $erro = "Email já cadastrado!";
      else{
        $password_hash = password_hash($senha, PASSWORD_DEFAULT);
        $usuario = new Usuario($nome, $password_hash, $email);
        $status = $usuario->sqls();
        if(!$status['success']) $erro = "Erro ao salvar na db! {$status['message']}";
        if($status['success']){header('Location: ./login.php');exit;}
      }

  }
  ?>

<div class="login-page">
  <div class="login-bg-pattern"></div>

  <div class="login-card">
    <div class="login-header">
      <span class="login-logo">📚</span>
      <h1>Biblioteca</h1>
      <p>Registre-se no sistema de gerenciamento</p>
    </div>

<?php if ($erro): ?>
    <div class="alert alert-error" style="display:block"><?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>
    <form action="register.php" method="POST">
    <div class="form-group" style="margin-bottom:1.5rem">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome"
               placeholder="Seu Nome" required>
      </div>
      <div class="form-group" style="margin-bottom:1.25rem">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email"
               placeholder="seu@email.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group" style="margin-bottom:1.5rem">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha"
               placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.7rem">
        Registrar
      </button>
    </form>

    <div class="login-divider"></div>
    <p style="text-align:center;font-size:.75rem;color:var(--ink3)">
      Sistema de Biblioteca · PHP + SQL
    </p>
  </div>
</div>

</body>
</html>
