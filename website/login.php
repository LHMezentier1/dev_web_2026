<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Biblioteca</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php
  require_once './includes/bdconnect.php';
  require_once './includes/models/usuario.php';
  session_start();

  // Se já estiver logado, redireciona para index.php
  if (isset($_SESSION['usuario'])) {
      header('Location: index.php');
      exit;
  }

  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $senha = trim($_POST['senha']);
      $usuario = Usuario::findByEmail($email);
      if($usuario === null) $erro = "Email e/ou senha invalido.";
      else{
        if(password_verify($senha, $usuario->password_hash)){
          $_SESSION['usuario'] = $email;
          $_SESSION['senha'] = $senha;
          header('Location: ./index.php');
          exit;
          }
        else $erro = "Email e/ou senha invalido.";
      }
  }
  ?>

<div class="login-page">
  <div class="login-bg-pattern"></div>

  <div class="login-card">
    <div class="login-header">
      <span class="login-logo">📚</span>
      <h1>Biblioteca</h1>
      <p>Acesse o sistema de gerenciamento</p>
    </div>

<?php if ($erro): ?>
    <div class="alert alert-error" style="display:block"><?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

    <div class="login-hint">
      <p>Credenciais de exemplo </p>
      <code>admin@biblioteca.com / admin123</code>
    </div>

    <form action="login.php" method="POST">
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
        Entrar no sistema →
      </button>
    </form>
    <a href="register.php" style="width:100%;justify-content:center;padding:.5rem;margin-top:3%" class="btn btn-primary">Não tem uma conta? Registre-se agora!</a>
    <div class="login-divider"></div>
    <p style="text-align:center;font-size:.75rem;color:var(--ink3)">
      Sistema de Biblioteca · PHP + SQL
    </p>
  </div>
</div>

</body>
</html>
