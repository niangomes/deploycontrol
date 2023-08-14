<?php
foreach ($data as $project => $deployStatus) {
    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
    echo "<strong>$project:</strong> <span class='badge badge-primary badge-pill ml-2'>{$deployStatus['status']}</span>";
    
    if ($deployStatus['status'] === 'deploying') {
        echo "<span class='user ml-auto'>Deploy iniciado por: " . getUsername($deployStatus['user_id']) . "</span>";
    } else {
        echo "<span class='user ml-auto'>Disponível</span>";
    }

    if ($deployStatus['status'] === 'deploying' && $deployStatus['user_id'] === $_SESSION['user_id']) {
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='project' value='$project'>";
        echo "<button type='submit' name='action' value='complete_deploy' class='btn btn-success btn-sm ml-2'>Concluir Deploy</button>";
        echo "</form>";
    } elseif ($deployStatus['status'] === 'completed' && $deployStatus['last_user'] === 0) {
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='project' value='$project'>";
        echo "<button type='submit' name='action' value='start_deploy' class='btn btn-primary btn-sm ml-2'>Iniciar Deploy</button>";
        echo "</form>";
    }
    
    echo "</li>";
}
?>
