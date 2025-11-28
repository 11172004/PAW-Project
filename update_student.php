<?php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$id = intval($_GET['id'] ?? 0);
if (!$id) die('Missing id.');

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) die('Student not found.');

$errors = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $group_id = trim($_POST['group_id'] ?? '');
    if ($fullname === '' || $matricule === '') $errors = 'Fullname and matricule required.';
    if (!$errors) {
        $update = $pdo->prepare("UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?");
        $update->execute([$fullname, $matricule, $group_id, $id]);
        $success = 'Updated.';
        // refresh student
        $stmt->execute([$id]);
        $student = $stmt->fetch();
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit</title></head><body>
  <h2>Edit student #<?=htmlspecialchars($student['id'])?></h2>
  <?php if ($errors): ?><div style="color:red;"><?=htmlspecialchars($errors)?></div><?php endif; ?>
  <?php if ($success): ?><div style="color:green;"><?=htmlspecialchars($success)?></div><?php endif; ?>
  <form method="post">
    <label>Full name</label><br><input name="fullname" value="<?=htmlspecialchars($student['fullname'])?>"><br>
    <label>Matricule</label><br><input name="matricule" value="<?=htmlspecialchars($student['matricule'])?>"><br>
    <label>Group</label><br><input name="group_id" value="<?=htmlspecialchars($student['group_id'])?>"><br>
    <button type="submit">Save</button>
  </form>
  <p><a href="list_students.php">Back</a></p>
</body></html>
