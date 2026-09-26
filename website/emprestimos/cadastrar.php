<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Novo Empréstimo — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <?php
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  require_once '../includes/models/emprestimo.php';
  require_once '../includes/models/aluno.php';
  require_once '../includes/models/livro.php';
  $erro   = '';

  $getAllRetL = Livro::getAll();
  $getAllRetA = Aluno::getAll();
  if(!$getAllRetA['success'] || !$getAllRetL['success']){$erro = "Os livros ou os alunos não foram encontrados."; exit;}
  $livros = $getAllRetL['data'];
  $alunos = $getAllRetA['data'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idLivro        = trim($_POST['idLivro']);
      $matricula      = trim($_POST['matricula']);
      $dataEmprestimo = trim($_POST['dataEmprestimo']);
      $dataDevolucao  = trim($_POST['dataDevolucao']);

      if (!$idLivro || !$matricula || !$dataEmprestimo || !$dataDevolucao) {
        $erro = 'Preencha todos os campos obrigatórios.';
      } elseif ($dataDevolucao <= $dataEmprestimo) {
        $erro = 'A data de devolução deve ser posterior à data do empréstimo.';
      } else {
        $aluno = Aluno::findByMatricula($matricula);
        $livro = Livro::findById($idLivro);
        if($livro === null || $aluno === null) $erro = "Livro ou aluno nao foi encontrado";
        $emprestimo = new Emprestimo($livro, $aluno, $dataEmprestimo, $dataDevolucao);
        $status = $emprestimo->sqls();
        if($status['success']){header('Location: listar.php?msg=cadastrado'); exit;} else $erro = "Erro ao inserir na db! {$status['message']} ";
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
    <a href="../alunos/listar.php" class="nav-item"><span class="nav-icon">🎓</span> Alunos</a>
    <a href="../emprestimos/listar.php" class="nav-item active"><span class="nav-icon">📋</span> Empréstimos</a>
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
        <div class="topbar-title">➕ Novo Empréstimo</div>
        <div class="topbar-sub">Registrar saída de livro</div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

<?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?> 

      <form action="cadastrar.php" method="POST">
        <div class="form-card">

          <div class="form-row">
            <div class="form-group">
              <label for="idLivro">Livro *</label>
              <select id="idLivro" name="idLivro" required>
                <option value="">Selecione um livro</option>
                <?php foreach ($livros as $livro): ?>
                  <option value="<?= $livro->id ?>"
                    <?= (($_POST['idLivro'] ?? '') == $livro->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$livro->id} – {$livro->nome} (Estoque: {$livro->estoque})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="matricula">Aluno *</label>
              <select id="matricula" name="matricula" required>
                <option value="">Selecione um aluno</option>
                <?php foreach ($alunos as $aluno): ?>
                  <option value="<?= $aluno->matricula ?>"
                    <?= (($_POST['matricula'] ?? '') === $aluno->matricula) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$aluno->nome} (Mat: {$aluno->matricula})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dataEmprestimo">Data do empréstimo *</label>
              <input type="date" id="dataEmprestimo" name="dataEmprestimo"
                     value="<?= htmlspecialchars($_POST['dataEmprestimo'] ?? date('Y-m-d')) ?>" required>
            </div>
            <div class="form-group">
              <label for="dataDevolucao">Data de devolução *</label>
              <input value="<?= htmlspecialchars($_POST['dataDevolucao'] ?? '') ?>" type="date" id="dataDevolucao" name="dataDevolucao" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Registrar empréstimo</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>
