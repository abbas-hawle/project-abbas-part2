<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format']);
            exit();   }

        $stmt = $pdo->prepare("SELECT user_id, password, is_admin FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            http_response_code(200);
            echo json_encode(['message' => 'Login successful', 'user_id' => $user['user_id'], 'email' => $email, 'is_admin' => (bool) $user['is_admin']]);  } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);  }
    } else {
       ; }?>