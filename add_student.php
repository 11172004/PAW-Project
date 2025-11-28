<?php
// add_student.php
// Save this file in your project folder. Access via browser.

$studentsFile = __DIR__ . '/students.json';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $student_id = trim($_POST['student_id'] ?? '');
    $name       = trim($_POST['name'] ?? '');
    $group      = trim($_POST['group'] ?? '');

    // Validate
    if ($student_id === '') $errors[] = "Student ID is required.";
    elseif (!preg_match('/^\d+$/', $student_id)) $errors[] = "Student ID must be digits only.";
    if ($name === '') $errors[] = "Name is required.";
    if ($group === '') $errors[] = "Group is required.";

    if (empty($errors)) {
        // Load existing students
        $students = [];
        if (file_exists($studentsFile)) {
            $json = file_get_contents($studentsFile);
            $students = json_decode($json, true);
            if (!is_array($students)) $students = [];
        }

        // Append new student
        $students[] = [
            'student_id' => $student_id,
            'name'       => $name,
            'group'      => $group,
            'created_at' => date('c')
        ];

        // Save back
        file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        $success = "Student (ID: {$student_id}) added successfully.";
        // Clear inputs for redisplay
        $student_id = $name = $group = '';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add student (JSON)</title>
  <style>
    body{font-family:Segoe UI,Arial;padding:20px}
    input,button{padding:8px;margin:6px 0;width:100%}
    .errors{color:#a00}
    .success{color:green}
    form{max-width:420px}
  </style>
</head>
<body>
  <h2>Add Student (JSON)</h2>

  <?php if ($errors): ?>
    <div class="errors"><strong>Errors:</strong><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success"><?=htmlspecialchars($success)?></div>
  <?php endif; ?>

  <form method="post" action="">
    <label>Student ID</label>
    <input name="student_id" value="<?=htmlspecialchars($student_id ?? '')?>" required>

    <label>Name</label>
    <input name="name" value="<?=htmlspecialchars($name ?? '')?>" required>

    <label>Group</label>
    <input name="group" value="<?=htmlspecialchars($group ?? '')?>" required>

    <button type="submit">Add student</button>
  </form>

  <p><a href="take_attendance.php">Go to Take Attendance (Exercise 2)</a></p>
</body>
</html>
