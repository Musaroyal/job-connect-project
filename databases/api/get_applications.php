<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Include your database configuration

// Fetch applications
$sql = "SELECT id, title, name, email, resume, coverLetter, status FROM applications_table";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $applications = [];
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
    echo json_encode(['success' => true, 'applications' => $applications]);
} else {
    echo json_encode(['success' => true, 'applications' => []]);
}

$conn->close();
?>
