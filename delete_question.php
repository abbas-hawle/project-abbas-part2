<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

 if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $questionId = $_GET['question_id'] ?? null;

   if (!is_numeric($questionId) || $questionId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid question ID']);
            exit();  }

        $stmt = $pdo->prepare("DELETE FROM questions WHERE question_id = ?");
        $stmt->execute([$questionId]);


        
    if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode