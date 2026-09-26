<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Livro — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <?php
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  require_once '../includes/models/livro.php';

  $id     = $_GET['id'] ?? null;
  if($id === null){ header('Location: listar.php'); exit; }
  $livro  = Livro::findById($id);
  if ($livro === null) { header('Location: listar.php'); exit; }

  $erro = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome    = trim($_POST['nome']);
      $editora = trim($_POST['editora']);
      $edicao  = trim($_POST['edicao']);
      $autor   = trim($_POST['autor']);
      $estoque = intval(trim($_POST['estoque']));

      if (!$nome || !$editora || !$edicao || !$autor || !$estoque) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } else {
          $livro->alter($nome, $editora, $edicao, $autor, $estoque);
          header('Location: listar.php?msg=alterado');
          exit;
      }
  }
  $dados = [
      'nome'    => $_POST['nome']    ?? $livro->nome,
      'editora' => $_POST['editora'] ?? $livro->editora,
      'edicao'  => $_POST['edicao']  ?? $livro->edicao,
      'autor'   => $_POST['autor']   ?? $livro->autor,
      'estoque' => $_POST['estoque'] ?? $livro->estoque,
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
        <div class="topbar-title"><?= htmlspecialchars($id) ?></div>
        <div class="topbar-title">✏️ Editar Livro <span style="font-size:.9rem;color:var(--ink3)">#3</span></div>
        <div class="topbar-sub">Alterar dados do título</div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

<?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <!-- TODO PHP: action="alterar.php?id=<?= $id ?>" method="POST" -->
      <form action="alterar.php?id=<?= $id ?>" method="POST">
        <div class="form-card">

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="nome">Título do livro *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['nome']) ?>" -->
              <input type="text" id="nome" name="nome"
                     value="<?= htmlspecialchars($dados['nome']) ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="autor">Autor *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['autor']) ?>" -->
              <input type="text" id="autor" name="autor"
                     value="<?= htmlspecialchars($dados['autor']) ?>" required>
            </div>
            <div class="form-group">
              <label for="editora">Editora *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['editora']) ?>" -->
              <input type="text" id="editora" name="editora"
                     value="<?= htmlspecialchars($dados['editora']) ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="edicao">Edição *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['edicao']) ?>" -->
              <input type="number" id="edicao" name="edicao"
                     value="<?= htmlspecialchars($dados['edicao']) ?>" min="1" required>
            </div>
            <div class="form-group">
              <label for="estoque">Quantidade em estoque *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['estoque']) ?>" -->
              <input type="number" id="estoque" name="estoque"
                     value="<?= htmlspecialchars($dados['estoque']) ?>" min="0" required>
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
