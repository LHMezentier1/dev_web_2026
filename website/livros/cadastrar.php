<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Livro — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/models/livro.php';
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome    = trim($_POST['nome']);
      $editora = trim($_POST['editora']);
      $edicao  = trim($_POST['edicao']);
      $autor   = trim($_POST['autor']);
      $estoque = trim($_POST['estoque']);

      if (!$nome || !$editora || !$edicao || !$autor || !$estoque) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } else {
        $livro = new Livro($nome, $editora, $edicao, $autor, $estoque);
        $status = $livro->sqls();
        if(!$status["success"]) $erro = "Erro ao inserir no banco de dados!  " . $status["message"];
        header('Location: listar.php?msg=cadastrado');
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
    <a href="../livros/listar.php" class="nav-item active"><span class="nav-icon">📖</span> Livros</a>
    <a href="../alunos/listar.php" class="nav-item"><span class="nav-icon">🎓</span> Alunos</a>
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
        <div class="topbar-title">➕ Cadastrar Livro</div>
        <div class="topbar-sub">Adicionar novo título ao acervo</div>
      </div>
      <div class="topbar-actions">
        <!-- TODO PHP: href="listar.php" -->
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

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="nome">Título do livro *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" type="text" id="nome" name="nome"
                     placeholder="Ex: O Senhor dos Anéis" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="autor">Autor *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['autor'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['autor'] ?? '') ?>" type="text" id="autor" name="autor"
                     placeholder="Ex: J.R.R. Tolkien" required>
            </div>
            <div class="form-group">
              <label for="editora">Editora *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['editora'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['editora'] ?? '') ?>" type="text" id="editora" name="editora"
                     placeholder="Ex: HarperCollins" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="edicao">Edição *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['edicao'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['edicao'] ?? '') ?>" type="number" id="edicao" name="edicao"
                     placeholder="Ex: 3" min="1" required>
            </div>
            <div class="form-group">
              <label for="estoque">Quantidade em estoque *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($_POST['estoque'] ?? '') ?>" -->
              <input value="<?= htmlspecialchars($_POST['estoque'] ?? '') ?>" type="number" id="estoque" name="estoque"
                     placeholder="Ex: 5" min="0" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cadastrar livro</button>
            <!-- TODO PHP: href="listar.php" -->
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div><!-- /form-card -->
      </form>

    </div><!-- /content -->
  </main>
</div>

</body>
</html>
