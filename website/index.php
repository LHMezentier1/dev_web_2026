<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel — Biblioteca</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php
  require_once './includes/bdconnect.php';
  require_once './includes/auth.php';
  require_once './includes/models/emprestimo.php';
  require_once './includes/models/aluno.php';
  require_once './includes/models/livro.php';

  $livrosCount = Livro::getCount();
  $alunosCount = Aluno::getCount();
  $emprestimosCount = Emprestimo::getCount();
  $countLate = Emprestimo::getCountLate();
  ?>

<div class="app-shell">

  <!-- ════════════════════════════ SIDEBAR ════════════════════════════ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <span class="brand-icon">📚</span>
      <h1>Biblioteca</h1>
      <p>Sistema de Gestão</p>
    </div>

    <nav class="sidebar-nav">
      <p class="nav-section-label">Geral</p>
      <a href="index.php" class="nav-item active">
        <span class="nav-icon">🏠</span> Painel
      </a>

      <p class="nav-section-label">Cadastros</p>
      <a href="livros/listar.php" class="nav-item">
        <span class="nav-icon">📖</span> Livros
      </a>
      <a href="alunos/listar.php" class="nav-item">
        <span class="nav-icon">🎓</span> Alunos
      </a>
      <a href="emprestimos/listar.php" class="nav-item">
        <span class="nav-icon">📋</span> Empréstimos
      </a>
    </nav>

    <div class="sidebar-user">
    <? echo mb_strtoupper(mb_substr($_SESSION['usuario'], 0, 2)) ?>
      <div class="user-avatar">AD</div>
      <div class="user-info">
      <? echo htmlspecialchars($_SESSION['usuario']) ?>
        <div class="user-name">Admin</div>
        <div class="user-role">Bibliotecário</div>
      </div>
      <a href="logout.php" class="btn-logout" title="Sair">⏻</a>
    </div>
  </aside>

  <!-- ════════════════════════════ MAIN ════════════════════════════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Painel Geral</div>
        <div class="topbar-sub">Visão geral do acervo e movimentações</div>
      </div>
    </div>

    <div class="content">

      <!-- ── Estatísticas ── -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon-wrap">📖</div>
          <div>
            <div class="stat-val"><?= $livrosCount ?></div>
            <div class="stat-lbl">Livros no acervo</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap">🎓</div>
          <div>
            <div class="stat-val"><?= $alunosCount ?></div>
            <div class="stat-lbl">Alunos cadastrados</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap">📋</div>
          <div>
            <div class="stat-val"><?= $emprestimosCount ?></div>
            <div class="stat-lbl">Empréstimos ativos</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon-wrap">⚠️</div>
          <div>
            <div class="stat-val" style="color:var(--red)"><?=$countLate?></div>
            <div class="stat-lbl">Em atraso</div>
          </div>
        </div>
      </div>

      <!-- ── Menu de módulos ── -->
      <div class="menu-grid">
        <a href="livros/listar.php" class="menu-card">
          <span class="menu-card-icon">📚</span>
          <div class="menu-card-title">Livros</div>
          <div class="menu-card-desc">Gerencie o acervo: cadastre, edite e remova títulos do estoque.</div>
          <div class="menu-card-arrow">Acessar →</div>
        </a>
        <a href="alunos/listar.php" class="menu-card">
          <span class="menu-card-icon">👩‍🎓</span>
          <div class="menu-card-title">Alunos</div>
          <div class="menu-card-desc">Gerencie os alunos cadastrados: nome, matrícula, sexo e nascimento.</div>
          <div class="menu-card-arrow">Acessar →</div>
        </a>
        <a href="emprestimos/listar.php" class="menu-card">
          <span class="menu-card-icon">🔖</span>
          <div class="menu-card-title">Empréstimos</div>
          <div class="menu-card-desc">Registre empréstimos, datas de devolução e acompanhe atrasos.</div>
          <div class="menu-card-arrow">Acessar →</div>
        </a>
      </div>

    </div>
  </main>
</div>

</body>
</html>
