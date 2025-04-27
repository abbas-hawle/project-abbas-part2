<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $quizId = $_GET['quiz_id'] ?? null;
        $current_user_id = 1;

        if (!is_numeric($quizId) || $quizId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid quiz ID']);
            exit();  }



        $stmt = $pdo->prepare("DELETE FROM quizzes WHERE quiz_id = ? AND created_by = ?");
        $stmt->execute([$quizId, $current_user_id]);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Quiz deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Quiz not found or you are not authorized to delete it']);  }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']); }?>