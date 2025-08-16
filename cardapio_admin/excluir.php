<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

session_start();
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Se o ID não existe ou não é um número, redireciona pra index
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id']; // faz o casting pra int pra maior segurança

// Prepara o SELECT pra verificar se o item existe
$stmt = $conn->prepare("SELECT id FROM cardapio_semanal WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    // Agora que sabemos que ele existe, podemos excluir
    $delete = $conn->prepare("DELETE FROM cardapio_semanal WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();

}

header('Location: index.php'); // depois de excluir, redireciona pra index
exit;
?>
