<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Aluno — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  require_once '../includes/models/aluno.php';
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome      = trim($_POST['nome']);
      $sexo      = $_POST['sexo'] ?? '';
      $dataNasc  = trim($_POST['dataNascimento']);

      if (!$nome || !$sexo || !$dataNasc) $erro = 'Preencha todos os campos obrigatórios.';
      if (!$erro) {
        $aluno = new Aluno($nome, $dataNasc, $sexo);
        $status = $aluno->sqls();
        $em = $status['success'] ? "?msg=cadastrado" : "";
        header("Location: listar.php{$em}");
        exit;
      }
  }
  ?>
  <div class="app-shell">
<aside class="sidebar">
  <div class="sidebar-brand">
    <span class="brand-icon">📚</span>
    <h1>Biblioteca</h1>
    <p>Sistema de Gestão</p>
  </div>
  <nav class="sidebar-nav">
    <p class="nav-section-label">Geral</p>
    <a href="../index.php" class="nav-item"><span class="nav-icon">🏠</span> Painel</a>
    <p class="nav-section-label">Cadastros</p>
    <a href="../livros/listar.php" class="nav-item"><span class="nav-icon">📖</span> Livros</a>
    <a href="../alunos/listar.php" class="nav-item active"><span class="nav-icon">🎓</span> Alunos</a>
    <a href="../emprestimos/listar.php" class="nav-item"><span class="nav-icon">📋</span> Empréstimos</a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">AD</div>
    <div class="user-info">
      <div class="user-name">Admin</div>
      <div class="user-role">Bibliotecário</div>
    </div>
    <a href="../login.php" class="btn-logout" title="Sair">⏻</a>
  </div>
</aside>


  <!-- ════ MAIN ════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">➕ Cadastrar Aluno</div>
        <div class="topbar-sub">Adicionar novo aluno ao sistema</div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">
      <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?> 

      <!-- TODO PHP: action="cadastrar.php" method="POST" -->
      <form action="cadastrar.php" method="POST">
        <div class="form-card">

          <div class="form-row">
            <div class="form-group full">
              <label for="nome">Nome completo *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" type="text" id="nome" name="nome"
                     placeholder="Ex: Ana Beatriz Souza" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="sexo">Sexo *</label>
              <!-- TODO PHP: selected conforme $_POST['sexo'] ?? '' -->
              <select id="sexo" name="sexo" required>
                <option  value="">Selecione</option>
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
              </select>
            </div>
          </div>

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="dataNascimento">Data de nascimento *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['dataNascimento'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['dataNascimento'] ?? '') ?>" type="date" id="dataNascimento" name="dataNascimento" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cadastrar aluno</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>
