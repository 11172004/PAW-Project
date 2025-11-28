<?php
// create_session.php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$course_id = intval($_POST['course_id'] ?? 0);
$group_id = trim($_POST['group_id'] ?? '');
$opened_by = intval($_POST['opened_by'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Send POST: course_id, group_id, opened_by";
    exit;
}

if (!$course_id || !$group_id || !$opened_by) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status) VALUES (?, ?, ?, ?, 'open')");
$stmt->execute([$course_id, $group_id, date('Y-m-d'), $opened_by]);
$sessionId = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode(['session_id' => $sessionId]);
