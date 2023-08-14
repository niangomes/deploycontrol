<?php
require_once 'config.php';

// Recuperar informações do quadro de auditoria
$auditLog = array();
$stmt = $db->query("SELECT al.id,al.project,al.status,al.datetime,u.username FROM audit_log al join users u on al.user_id=u.id ORDER BY al.id DESC limit 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $auditLog[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quadro de Auditoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Ultimos Status</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Projeto</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Data e Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auditLog as $log) { ?>
                            <tr>
                                <td><?php echo $log['id']; ?></td>
                                <td><?php echo $log['project']; ?></td>
                                <td><?php echo $log['username']; ?></td>
                                <td><?php echo $log['status']; ?></td>
                                <td><?php echo $log['datetime']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>