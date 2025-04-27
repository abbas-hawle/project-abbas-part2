<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $quizId = $_GET['quiz_id'] ?? null;

        if (!is_numeric($quizId) || $quizId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid quiz ID']);
            exit();   }

        $stmt = $pdo->prepare("SELECT question_id, question_text, options FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($questions);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']); }
?>