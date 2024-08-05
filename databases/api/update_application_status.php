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

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $applicationId = $data->application_id;
    $newStatus = $data->status;

    // Update status in the database
    $query = "UPDATE applications_table SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newStatus, $applicationId);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Application status updated successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to update application status';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
