<?php
// add_student_db.php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $group_id = trim($_POST['group_id'] ?? '');

    if ($fullname === '') $errors[] = 'Full name required.';
    if ($matricule === '') $errors[] = 'Matricule required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $matricule, $group_id]);
        $success = "Student added (ID: " . $pdo->lastInsertId() . ").";
        // clear inputs
        $fullname = $matricule = $group_id = '';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add student (DB)</title></head><body>
  <h2>Add student (DB)</h2>
  <?php if ($errors): ?><div style="color:red;"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
  <?php if ($success): ?><div style="color:green;"><?=htmlspecialchars($success)?></div><?php endif; ?>
  <form method="post">
    <label>Full name</label><br><input name="fullname" value="<?=htmlspecialchars($fullname ?? '')?>"><br>
    <label>Matricule</label><br><input name="matricule" value="<?=htmlspecialchars($matricule ?? '')?>"><br>
    <label>Group</label><br><input name="group_id" value="<?=htmlspecialchars($group_id ?? '')?>"><br>
    <button type="submit">Add</button>
  </form>
  <p><a href="list_students.php">List students</a></p>
</body></html>
