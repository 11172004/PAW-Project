<?php
require 'db_connect.php';
$pdo = db_connect();
if (!$pdo) die('DB connection error.');

$rows = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Students</title></head><body>
  <h2>Students</h2>
  <p><a href="add_student_db.php">Add new</a></p>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr><th>ID</th><th>Full name</th><th>Matricule</th><th>Group</th><th>Actions</th></tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['id'])?></td>
        <td><?=htmlspecialchars($r['fullname'])?></td>
        <td><?=htmlspecialchars($r['matricule'])?></td>
        <td><?=htmlspecialchars($r['group_id'])?></td>
        <td>
          <a href="update_student.php?id=<?=urlencode($r['id'])?>">Edit</a> |
          <a href="delete_student.php?id=<?=urlencode($r['id'])?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body></html>
