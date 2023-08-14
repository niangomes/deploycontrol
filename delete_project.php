<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
    exit;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project'])) {
    require_once 'config.php';

    if ($is_admin) {
        $project = $_POST['project'];
        $stmt = $db->prepare("DELETE FROM deploy_status WHERE project = :project");
        $stmt->bindParam(':project', $project);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Erro ao excluir o projeto.']);
?>
