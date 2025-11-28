<?php
// test_connection.php
require 'db_connect.php';
$pdo = db_connect();
if ($pdo) {
    echo "<h2>Connection successful ✔</h2>";
} else {
    echo "<h2>Connection failed ❌</h2>";
    echo "<p>Check db_errors.log for details (if any).</p>";
}
