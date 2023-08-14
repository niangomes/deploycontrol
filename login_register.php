<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($_POST['action'] === 'login') {
        $stmt = $db->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $message = 'Credenciais inválidas.';
        }
    } elseif ($_POST['action'] === 'register') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        if ($stmt->execute()) {
            $message = 'Registro concluído com sucesso. Faça login.';
        } else {
            $message = 'Erro ao registrar usuário.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login e Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login e Registro</div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Usuário:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Senha:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="action" value="login" class="btn btn-primary">Entrar</button>
                            <button type="submit" name="action" value="register" class="btn btn-secondary">Registrar</button>
                        </form>
                        <p class="mt-3"><?php echo $message; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
