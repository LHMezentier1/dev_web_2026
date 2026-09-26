<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Aluno — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once "../includes/models/aluno.php";
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';

  $matricula = $_GET['matricula'] ?? null;
  if ($matricula === null) { header('Location: listar.php'); exit; }
  $aluno = Aluno::findByMatricula($matricula);
  if ($aluno === null) { header('Location: listar.php'); exit; }


  $erro = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome     = trim($_POST['nome']);
      $sexo     = $_POST['sexo'] ?? '';
      $dataNasc = trim($_POST['dataNascimento']);

      if (!$nome || !$sexo || !$dataNasc) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } else {
          $aluno->alter($nome, $dataNasc, $sexo);
          header('Location: listar.php?msg=alterado');
          exit;
      }
  }
  $dados = [
      'nome'          => $_POST['nome']          ?? $aluno->nome,
      'sexo'          => $_POST['sexo']          ?? $aluno->sexo,
      'dataNascimento'=> $_POST['dataNascimento'] ?? $aluno->datanas,
  ];
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

  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">✏️ Editar Aluno</div>
        <!-- TODO PHP: Matrícula → <?= htmlspecialchars($matricula) ?> -->
        <div class="topbar-sub">Matrícula: <?= htmlspecialchars($matricula) ?></div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

    <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <!-- TODO PHP: action="alterar.php?matricula=<?= urlencode($matricula) ?>" method="POST" -->
      <form action="alterar.php?matricula=<?= urlencode($matricula) ?>" method="POST">
        <div class="form-card">

          <!-- Matrícula só exibe, não edita -->
          <div class="form-row cols-1" style="margin-bottom:1.25rem">
            <div class="form-group">
              <label>Matrícula (não editável)</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($matricula) ?>" -->
              <input value="<?= htmlspecialchars($matricula) ?>" type="text" disabled
                     style="opacity:.5;cursor:not-allowed">
            </div>
          </div>

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="nome">Nome completo *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['nome']) ?>" -->
              <input value="<?= htmlspecialchars($dados['nome']) ?>" type="text" id="nome" name="nome"
                     required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="sexo">Sexo *</label>
              <!-- TODO PHP: selected via $dados['sexo'] -->
              <select id="sexo" name="sexo" required>
                <option value="M" <?php echo ($dados['sexo'] === 'M') ? 'selected' : ''; ?>>Masculino</option>
                <option value="F" <?php echo ($dados['sexo'] === 'F') ? 'selected' : ''; ?>>Feminino</option>
              </select>
            </div>
            <div class="form-group">
              <label for="dataNascimento">Data de nascimento *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['dataNascimento']) ?>" -->
              <input value="<?= htmlspecialchars($dados['dataNascimento']) ?>" type="date" id="dataNascimento" name="dataNascimento"
                      required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>
