<?php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $pdo->prepare("DELETE FROM students WHERE id=?")->execute([$id]);
}
header('Location: list_students.php');
exit;
