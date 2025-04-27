<?php
    require 'db.php';

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

    

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT quiz_id, title, description FROM quizzes");
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quizzes);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']); }?>