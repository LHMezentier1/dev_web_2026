<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empréstimos — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once __DIR__ . '/../includes/bdconnect.php';
  require_once '../includes/auth.php';
  require_once '../includes/funcoes.php';
  require_once '../includes/models/emprestimo.php';

  $empRet = Emprestimo::getAll();
  $emprestimos = $empRet['success'] ? $empRet['data'] : [];
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
        <div class="topbar-title">📋 Empréstimos</div>
        <div class="topbar-sub">Controle de empréstimos e devoluções</div>
      </div>
      <div class="topbar-actions">
        <a href="cadastrar.php" class="btn btn-primary">＋ Novo empréstimo</a>
      </div>
    </div>

    <div class="content">

   
      <?php if ($msg === 'cadastrado'): ?>
        <div class="alert alert-success">✓ Empréstimo registrado com sucesso!</div>
      <?php elseif ($msg === 'alterado'): ?>
        <div class="alert alert-success">✓ Empréstimo atualizado com sucesso!</div>
      <?php elseif ($msg === 'removido'): ?>
        <div class="alert alert-success">✓ Empréstimo removido com sucesso!</div>
      <?php endif; ?>
   

      <div class="table-header">
        <h2>Empréstimos</h2>
        <span class="table-count"><?= count($emprestimos) ?> registro(s)</span>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Livro</th>
              <th>Aluno</th>
              <th>Matrícula</th>
              <th>Empréstimo</th>
              <th>Devolução</th>
              <th>Situação</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>

              <?php foreach ($emprestimos as $emp): ?>
              <?php
                $nomeLivro = $emp->livro->nome ?? "Livro #{$emp->livro->id}";
                $nomeAluno = $emp->aluno->nome ?? "Matr. {$emp->aluno->matricula}";
                $atrasado  = $emp->datadev < date('Y-m-d');
              ?>
              <tr>
                <td class="td-mono"><?= $emp->id ?></td>
                <td class="td-main"><?= htmlspecialchars($nomeLivro) ?></td>
                <td><?= htmlspecialchars($nomeAluno) ?></td>
                <td class="td-mono"><?= htmlspecialchars($emp->aluno->matricula) ?></td>
                <td><?= date('d/m/Y', strtotime($emp->dataemp)) ?></td>
                <td><?= date('d/m/Y', strtotime($emp->datadev)) ?></td>
                <td>
                  <span class="badge <?= $atrasado ? 'badge-late' : 'badge-ok' ?>">
                    <?= $atrasado ? '⚠ Atrasado' : '✓ No prazo' ?>
                  </span>
                </td>
                <td class="td-actions">
                  <a href="alterar.php?idx=<?= $emp->id ?>" class="btn btn-sm btn-edit">Editar</a>
                  <a href="remover.php?idx=<?= $emp->id ?>"
                     onclick="return confirm('Remover este empréstimo?')"
                     class="btn btn-sm btn-danger">Remover</a>
                </td>
              </tr>
              <?php endforeach; ?>

            
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

</body>
</html>
