<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $created_by = 1;

        if (empty($title)) {
            http_response_code(400);
            echo json_encode(['error' => 'Quiz title is required']);
            exit();}

        $stmt = $pdo->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$title, $description, $created_by]);
            $quizId = $pdo->lastInsertId();
            http_response_code(201);
            echo json_encode(['message' => 'Quiz created successfully', 'quiz_id' => $quizId]);  } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create quiz: ' . $e->getMessage()]);
        } } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']); }?>