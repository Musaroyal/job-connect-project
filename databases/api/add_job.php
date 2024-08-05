<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Include your database configuration

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$description = $data['description'];
$requirements = $data['requirements'];

$response = [];

if ($title && $description && $requirements) {
    $query = "INSERT INTO jobs_table (title, description, requirements) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $title, $description, $requirements);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Job added successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to add job';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'All fields are required';
}

echo json_encode($response);
?>
