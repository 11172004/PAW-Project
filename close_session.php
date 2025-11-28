<?php
// close_session.php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$session_id = intval($_POST['session_id'] ?? 0);
if (!$session_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing session_id']);
    exit;
}

$stmt = $pdo->prepare("UPDATE attendance_sessions SET status='closed' WHERE id=?");
$stmt->execute([$session_id]);

echo json_encode(['success' => true, 'session_id' => $session_id]);
