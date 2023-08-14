<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

require_once 'config.php';

function getUsername($user_id) {
    global $db;
    $stmt = $db->prepare("SELECT username FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['username'] : 'Desconhecido';
}

$data = array();

// Recuperar informações de status de deploy do banco de dados
$stmt = $db->query("SELECT * FROM deploy_status");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[$row['project']] = array(
        'status' => $row['status'],
        'user_id' => $row['user_id'],
        'last_user' => $row['last_user']
    );
}

// Verificar se o usuário logado é um administrador
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['is_admin'] == 1) {
        $is_admin = true;
    }
}

include 'index-template.php';
?>
