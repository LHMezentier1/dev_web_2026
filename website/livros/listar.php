<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livros — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <?php
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  require_once '../includes/models/livro.php';
  $getAllRet = Livro::getAll();
  $livros = $getAllRet['success'] ? $getAllRet['data'] : [];
  $count = count($livros);
  $msg = $_GET['msg'] ?? '';
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
        <div class="topbar-title">📖 Livros</div>
        <div class="topbar-sub">Gerenciamento do acervo</div>
      </div>
      <div class="topbar-actions">
        <!-- TODO PHP: href="cadastrar.php" -->
        <a href="cadastrar.php" class="btn btn-primary">＋ Novo livro</a>
      </div>
    </div>

    <div class="content">
      <?php if ($msg === 'cadastrado'): ?>
        <div class="alert alert-success">✓ Livro cadastrado com sucesso!</div>
      <?php elseif ($msg === 'alterado'): ?>
        <div class="alert alert-success">✓ Livro atualizado com sucesso!</div>
      <?php elseif ($msg === 'removido'): ?>
        <div class="alert alert-success">✓ Livro removido com sucesso!</div>
      <?php endif; ?>
      <div class="table-header">
        <h2>Acervo</h2>
        <!-- TODO PHP: <?= $count ?> livro(s) -->
        <span class="table-count"><?= $count ?></span>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Autor</th>
              <th>Editora</th>
              <th>Edição</th>
              <th>Estoque</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>

              <?php foreach ($livros as $livro): ?>
              <tr>
                <td class="td-mono"><?= htmlspecialchars($livro->id) ?></td>
                <td class="td-main"><?= htmlspecialchars($livro->nome) ?></td>
                <td><?= htmlspecialchars($livro->autor) ?></td>
                <td><?= htmlspecialchars($livro->editora) ?></td>
                <td style="text-align:center"><?= htmlspecialchars($livro->edicao) ?></td>
                <td>
                  <span class="badge badge-stock"><?= htmlspecialchars($livro->estoque) ?> un.</span>
                </td>
                <td class="td-actions">
                  <a href="alterar.php?id=<?= $livro->id ?>" class="btn btn-sm btn-edit">Editar</a>
                  <a href="remover.php?id=<?= $livro->id ?>"
                     onclick="return confirm('Remover este livro?')"
                     class="btn btn-sm btn-danger">Remover</a>
                </td>
              </tr>
              <?php endforeach; ?>


          </tbody>
        </table>
      </div><!-- /table-wrap -->

    </div><!-- /content -->
  </main>
</div>

</body>
</html>
