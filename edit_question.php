<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $questionId = $_GET['question_id'] ?? null;

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

      $questionText = $data['question_text'] ?? '';
        $options = json_encode($data['options'] ?? []);
        $correctAnswer = $data['correct_answer'] ?? '';

        if (!is_numeric($questionId) || $questionId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid question ID']);
            exit();    }
    if (empty($questionText) || empty($data['options']) || empty($correctAnswer)) {
            http_response_code(400);
            echo json_encode(['error' => 'Question text, options, and correct answer are required']);
            exit();
        }

        if (!in_array($correctAnswer, $data['options'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Correct answer must be one of the options']);
            exit();  }
   $stmt = $pdo->prepare("UPDATE questions SET question_text = ?, options = ?, correct_answer = ?, updated_at = NOW() WHERE question_id = ?");
        $stmt->execute([$questionText, $options, $correctAnswer, $questionId]);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Question updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Question not found or you are not authorized to edit it'])    }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);  }
?>