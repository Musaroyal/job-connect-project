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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $resume = $_FILES['resume'];
    $coverLetter = isset($_FILES['coverLetter']) ? $_FILES['coverLetter'] : null;
    $jobTitle = $_POST['jobTitle']; // Retrieve job title from POST data

    if ($name && $email && $resume) {
        // Handle file uploads
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $resumePath = $uploadDir . basename($resume['name']);
        $coverLetterPath = $coverLetter ? $uploadDir . basename($coverLetter['name']) : null;

        if (move_uploaded_file($resume['tmp_name'], $resumePath) && (!$coverLetter || move_uploaded_file($coverLetter['tmp_name'], $coverLetterPath))) {
            // Insert data into database
            $query = "INSERT INTO applications_table (name, email, resume, coverLetter, title) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $name, $email, $resumePath, $coverLetterPath, $jobTitle);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Application submitted successfully';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to submit application';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to upload files';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'All fields are required';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
