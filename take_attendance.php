<?php
// take_attendance.php

$studentsFile = __DIR__ . '/students.json';
$today = date('Y-m-d');
$attendanceFile = __DIR__ . "/attendance_{$today}.json";
$message = '';

if (!file_exists($studentsFile)) {
    die("No students found. Add students first: <a href='add_student.php'>Add student</a>");
}

$students = json_decode(file_get_contents($studentsFile), true);
if (!is_array($students)) $students = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If today's attendance already taken
    if (file_exists($attendanceFile)) {
        $message = "Attendance for today has already been taken.";
    } else {
        $attendance = [];
        foreach ($students as $stu) {
            $id = $stu['student_id'];
            // input name e.g. status_101
            $status = (isset($_POST["status_{$id}"]) && $_POST["status_{$id}"] === 'present') ? 'present' : 'absent';
            $attendance[] = ['student_id' => $id, 'status' => $status];
        }

        file_put_contents($attendanceFile, json_encode($attendance, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $message = "Attendance saved to " . basename($attendanceFile);
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Take Attendance</title>
  <style>body{font-family:Segoe UI;padding:18px} table{border-collapse:collapse;width:100%;max-width:800px} th,td{border:1px solid #ddd;padding:8px;text-align:center}</style>
</head>
<body>
  <h2>Take Attendance (<?=htmlspecialchars($today)?>)</h2>
  <?php if ($message): ?><p><strong><?=htmlspecialchars($message)?></strong></p><?php endif; ?>

  <form method="post">
    <table>
      <thead>
        <tr><th>ID</th><th>Name</th><th>Group</th><th>Present</th></tr>
      </thead>
      <tbody>
      <?php foreach ($students as $s): ?>
        <tr>
          <td><?=htmlspecialchars($s['student_id'])?></td>
          <td><?=htmlspecialchars($s['name'])?></td>
          <td><?=htmlspecialchars($s['group'])?></td>
          <td>
            <label><input type="radio" name="status_<?=htmlspecialchars($s['student_id'])?>" value="present" checked> Present</label>
            <label style="margin-left:8px"><input type="radio" name="status_<?=htmlspecialchars($s['student_id'])?>" value="absent"> Absent</label>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <p><button type="submit">Save attendance for <?=htmlspecialchars($today)?></button></p>
  </form>

  <p><a href="add_student.php">Back to Add Student</a></p>
</body>
</html>
