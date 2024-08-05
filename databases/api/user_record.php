<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


// Include the database connection configuration
include 'config.php';

// Start session
session_start();

// Check if email is set in session
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];

    // Prepare SQL statement to fetch user information
    $sql = "SELECT id, username, email FROM users_db WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user was found
    if ($row = mysqli_fetch_assoc($result)) {
        $response = [
            'success' => true,
            'user' => [
                'id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email']
                // Add more fields as needed
            ]
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // User not found
        $response = [
            'success' => false,
            'message' => 'User not found'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    mysqli_stmt_close($stmt);
} else {
    // Session or email not set
    $response = [
        'success' => false,
        'message' => 'User email not provided'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}

mysqli_close($conn);
?>
