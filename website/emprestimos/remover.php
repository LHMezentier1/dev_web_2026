<?php
require_once __DIR__ . '/../includes/bdconnect.php';
require_once '../includes/auth.php';
require_once '../includes/models/emprestimo.php';

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : null;
if($idx === null){header('Location: listar.php'); exit;}

$emprestimo = Emprestimo::getById($idx);
if($emprestimo === null){header('Location: listar.php'); exit;}

$status = $emprestimo->sqld();
if(!$status['success']){header('Location: listar.php'); exit;}

header('Location: listar.php?msg=removido');
exit;