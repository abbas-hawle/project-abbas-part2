<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $quizId = $_GET['quiz_id'] ?? null;

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $questionText = $data['question_text'] ?? '';
        $options = json_encode($data['options'] ?? []);
        $correctAnswer = $data['correct_answer'] ?? '';

        if (!is_numeric($quizId) || $quizId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid quiz ID']);
            exit(); }



        if (empty($questionText) || empty($data['options']) || empty($correctAnswer)) {
            http_response_code(400);
            echo json_encode(['error' => 'Question text, options, and correct answer are required']);
            exit();
        }

        

        if (!in_array($correctAnswer, $data['options'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Correct answer must be one of the options']);
            exit();  }

        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_answer) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$quizId, $questionText, $options, $correctAnswer]);
            $questionId = $pdo->lastInsertId();
            http_response_code(201);
            echo json_encode(['message' => 'Question created successfully', 'question_id' => $questionId]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create question: ' . $e->getMessage()]);  }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']); }
?>