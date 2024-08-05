<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $title = $data['title'];
    $description = $data['description'];
    $requirements = $data['requirements'];

    if (!empty($id) && !empty($title) && !empty($description) && !empty($requirements)) {
        $stmt = $conn->prepare("UPDATE jobs_table SET title = ?, description = ?, requirements = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $requirements, $id);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Record updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update the record."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
