<?php
require_once __DIR__ . '/../includes/bdconnect.php';
require_once '../includes/auth.php';
require_once '../includes/funcoes.php';
require_once '../includes/models/aluno.php';

$matriculaRemover = isset($_GET['matricula']) ? trim($_GET['matricula']) : null;

if ($matriculaRemover === null) {
    header('Location: listar.php');
    exit;
}

$aluno = Aluno::findByMatricula($matriculaRemover);

if (!($aluno === null)) {
    $aluno->sqld();
    header('Location: listar.php?msg=removido');
    exit;
}

header('Location: listar.php');
exit;