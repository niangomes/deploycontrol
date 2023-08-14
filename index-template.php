<!DOCTYPE html>
<html>
<head>
    <title>Painel de Controle de Deploys</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="scripts.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Painel de Controle de Deploys</h1>

    <div class="container mt-5">
        <div class="logout-button text-right">
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>

        <p>Bem-vindo, <?php echo getUsername($_SESSION['user_id']); ?>!</p>

        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
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
                            echo "<button class='btn btn-success btn-sm ml-2 complete-deploy-button' data-project='$project'>Concluir Deploy</button>";
                        } elseif ($deployStatus['status'] === 'completed' && $deployStatus['last_user'] === 0) {
                            echo "<button class='btn btn-primary btn-sm ml-2 start-deploy-button' data-project='$project'>Iniciar Deploy</button>";
                        }

   			            if ($is_admin) {
        		            echo "<button class='btn btn-danger btn-sm ml-2 delete-project-button' data-project='$project'>Excluir Projeto</button>";
    			        }


                        echo "</li>";
                    }
                    ?>
                </ul>

<?php if ($is_admin): ?>
    <button class="btn btn-primary mt-3" id="add-project-button">Adicionar Projeto</button>
    <div id="add-project-form" style="display: none;">
        <form id="project-form">
            <div class="form-group">
                <label for="new-project-name">Nome do Projeto:</label>
                <input type="text" class="form-control" id="new-project-name" name="new_project_name" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
<?php endif; ?>
<div class="text-center">
<?php if ($is_admin) require_once 'audit_log.php'; { ?>
    <?php } ?>

            </div>
        </div>
    </div>
</body>
</html>
