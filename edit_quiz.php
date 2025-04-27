<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $quizId = $_GET['quiz_id'] ?? null;

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $current_user_id = 1;

         if (!is_numeric($quizId) || $quizId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid quiz ID']);
            exit();  }

          if (empty($title)) {
            http_response_code(400);
            echo json_encode(['error' => 'Quiz title is required']);
            exit(); }

        $stmt = $pdo->prepare("UPDATE quizzes SET title = ?, description = ?, updated_at = NOW() WHERE quiz_id = ? AND created_by = ?");
        $stmt->execute([$title, $description, $quizId, $current_user_id]);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Quiz updated successfully']);
} else {
            http_response_code(404);
            echo json_encode(['error' => 'Quiz not found or you are not authorized to edit it']);
        }} else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
    }?>