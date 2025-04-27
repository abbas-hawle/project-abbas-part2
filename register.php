<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $email = $data['E-mail'] ?? '';
        $password = $data['passWord'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format']);
            exit();}

        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Password must be at least 6 characters long']);
            exit(); }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        try {
            $stmt->execute([$email, $hashedPassword]);
            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully']); } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                http_response_code(409);
                echo json_encode(['error' => 'Email already exists']);
             } else {
                http_response_code(500);
                echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);   }  }  } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);   }?>