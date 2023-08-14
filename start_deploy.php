<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project'])) {
    require_once 'config.php';

    $project = $_POST['project'];
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT status, user_id, last_user FROM deploy_status WHERE project = :project");
    $stmt->bindParam(':project', $project);
    $stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($status['status'] === 'completed') {
        if ($status['last_user'] === $user_id) {
            echo json_encode(['success' => false, 'message' => 'O deploy foi iniciado por outro usuário.']);
            exit;
        }

    	// Registrar a alteração no quadro de auditoria
    	$auditStmt = $db->prepare("INSERT INTO audit_log (project, status, user_id) VALUES (:project, :status, :user_id)");
    	$auditStmt->bindParam(':project', $project);
    	$auditStmt->bindValue(':status', 'Deploy iniciado');
    	$auditStmt->bindParam(':user_id', $user_id);
   	$auditStmt->execute();

        // Atualizar o status do projeto para "deploying" no banco de dados
        $updateStmt = $db->prepare("UPDATE deploy_status SET status = 'deploying', user_id = :user_id, last_user = :last_user WHERE project = :project");
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->bindParam(':last_user', $user_id);
        $updateStmt->bindParam(':project', $project);
        $updateStmt->execute();

        echo json_encode(['success' => true]);
    } else {
        // Adicione esta linha para depurar o valor de status['status']
        echo json_encode(['success' => false, 'message' => 'Status inválido: ' . $status['status']]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}

?>
