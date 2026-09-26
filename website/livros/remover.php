<?php
require_once __DIR__ . '/../includes/bdconnect.php';
require_once '../includes/auth.php';
require_once '../includes/funcoes.php';
require_once '../includes/models/livro.php';

$idRemover = isset($_GET['id']) ? trim($_GET['id']) : null;

if ($idRemover === null) {
    header('Location: listar.php');
    exit;
}

$livro = Livro::findById($idRemover);

if(!($livro === null)){
    $status = $livro->sqld();
    if($status["success"]){
        header('Location: listar.php?msg=removido');
        exit;
    }
}

header('Location: listar.php');
exit;