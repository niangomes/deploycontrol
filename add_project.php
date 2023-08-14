<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && isset($_POST['new_project_name'])) {
    require_once 'config.php';

    // Verificar se o usuário é um administrador
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['is_admin'] == 1) {
        $new_project_name = $_POST['new_project_name'];

        // Inserir o novo projeto no banco de dados
        $insertStmt = $db->prepare("INSERT INTO deploy_status (project, status, user_id, last_user) VALUES (:project, 'completed', 0, 0)");
        $insertStmt->bindParam(':project', $new_project_name);
        $insertStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Projeto adicionado com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Apenas administradores podem adicionar projetos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
