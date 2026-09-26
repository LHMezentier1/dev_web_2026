<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Empréstimo — Biblioteca</title>
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

  $idx         = (int)($_GET['idx'] ?? -1);
  $erro = '';
  $getAllRetL = Livro::getAll();
  $getAllRetA = Aluno::getAll();
  if(!$getAllRetL['success'] || !$getAllRetA['success'])$erro = "Não foi possivel encontrar os livros ou/e os alunos.";
  $livros = $getAllRetL['data'];
  $alunos = $getAllRetA['data'];

  if ($idx < 0) {header('Location: listar.php'); exit;}
  
  $emp  = Emprestimo::getById($idx);
  if($emp == null)$erro = "Emprestimo com id: {$idx} não encontrado";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idLivro        = trim($_POST['idLivro']);
      $matricula      = trim($_POST['matricula']);
      $dataEmprestimo = trim($_POST['dataEmprestimo']);
      $dataDevolucao  = trim($_POST['dataDevolucao']);

      if (!$idLivro || !$matricula || !$dataEmprestimo || !$dataDevolucao) {
          $erro = 'Preencha todos os campos.';
      } elseif ($dataDevolucao <= $dataEmprestimo) {
          $erro = 'A data de devolução deve ser posterior à data do empréstimo.';
      } else {
        $l = Livro::findById($idLivro);
        $a = Aluno::findByMatricula($matricula);
        if($l === null || $a === null) $erro = "Não foi possivel encontrar o livro ou/e o aluno.";
        $status = $emp->alter($l, $a, $dataEmprestimo, $dataDevolucao);
        if($status['success']){header('Location: listar.php?msg=alterado'); exit;} else $erro = "Erro ao alterar! {$status['message']}";
      }
  }

  $dados = [
      'idLivro'        => $_POST['idLivro']        ?? $emp->livro->id,
      'matricula'      => $_POST['matricula']      ?? $emp->aluno->matricula,
      'dataEmprestimo' => $_POST['dataEmprestimo'] ?? $emp->dataemp,
      'dataDevolucao'  => $_POST['dataDevolucao']  ?? $emp->datadev,
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


  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">✏️ Editar Empréstimo</div>
        <div class="topbar-sub">Registro nº <?= $idx + 1 ?></div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

<?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <form action="alterar.php?idx=<?= $idx ?>" method="POST">
        <div class="form-card">

          <div class="form-row">
            <div class="form-group">
              <label for="idLivro">Livro *</label>
              <select id="idLivro" name="idLivro" required>
              <?php foreach ($livros as $livro): ?>
                  <option value="<?= $livro->id ?>"
                    <?= (($dados['idlivro'] ?? '') === $livro->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$livro->nome}") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="matricula">Aluno *</label>
              <select id="matricula" name="matricula" required>
              <?php foreach ($alunos as $aluno): ?>
                  <option value="<?= $aluno->matricula ?>"
                    <?= (($dados['matricula'] ?? '') === $aluno->matricula) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$aluno->nome} (Mat: {$aluno->matricula})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dataEmprestimo">Data do empréstimo *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['dataEmprestimo']) ?>" -->
              <input type="date" id="dataEmprestimo" name="dataEmprestimo"
              value="<?= htmlspecialchars($dados['dataEmprestimo']) ?>" required>
            </div>
            <div class="form-group">
              <label for="dataDevolucao">Data de devolução *</label>
              <!-- TODO PHP: value="<?= htmlspecialchars($dados['dataDevolucao']) ?>" -->
              <input type="date" id="dataDevolucao" name="dataDevolucao"
              value="<?= htmlspecialchars($dados['dataDevolucao']) ?>" required>
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
