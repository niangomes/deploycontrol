<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project'])) {
    require_once 'config.php';

    $project = $_POST['project'];
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT status, last_user FROM deploy_status WHERE project = :project");
    $stmt->bindParam(':project', $project);
    $stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($status['last_user'] === $user_id) {
        // Atualizar o status do projeto para "completed" no banco de dados
        $updateStmt = $db->prepare("UPDATE deploy_status SET status = 'completed', last_user = 0 WHERE project = :project");
        $updateStmt->bindParam(':project', $project);
        $updateStmt->execute();

    	// Registrar a alteração no quadro de auditoria
    	$auditStmt = $db->prepare("INSERT INTO audit_log (project, status, user_id) VALUES (:project, :status, :user_id)");
    	$auditStmt->bindParam(':project', $project);
    	$auditStmt->bindValue(':status', 'Deploy concluído');
    	$auditStmt->bindParam(':user_id', $user_id);
   	$auditStmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Você não iniciou este deploy.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
