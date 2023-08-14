<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se o usuário já existe
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        $error_message = "Usuário já existe.";
    } else {
        // Cria um hash da senha
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco de dados
        $insert_stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insert_stmt->bindParam(':username', $username);
        $insert_stmt->bindParam(':password', $hashed_password);

        if ($insert_stmt->execute()) {
            $success_message = "Usuário cadastrado com sucesso.";
        } else {
            $error_message = "Erro ao cadastrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h1>Cadastro de Usuário</h1>
    <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
    <?php if (isset($success_message)) echo "<p>$success_message</p>"; ?>
    <form method="post">
        <label for="username">Usuário:</label>
        <input type="text" name="username" required><br>
        <label for="password">Senha:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="login.php">Já tem uma conta? Faça login aqui</a></p>
</body>
</html>
