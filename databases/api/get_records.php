<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
$result = $conn->query("SELECT id, title, description, requirements FROM jobs_table");
$records = [];
}
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

echo json_encode(["records" => $records]);

?>
