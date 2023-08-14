<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project = $_POST['project'];
    $status = $_POST['status'];

    $data = json_decode(file_get_contents("deploy_status.json"), true);

    if ($status === 'deploying') {
        $data[$project]['status'] = 'deploying';
        $data[$project]['last_user'] = $_SESSION['user_id'];
    } elseif ($status === 'done') {
        $data[$project]['status'] = 'done';
        $data[$project]['user_id'] = $_SESSION['user_id'];
    }

    file_put_contents("deploy_status.json", json_encode($data, JSON_PRETTY_PRINT));

    header('Location: index.php');
    exit;
}
?>
