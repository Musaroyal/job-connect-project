<?php
// PHP equivalent of JobApp React component

// Function to simulate logout (remove user from session in PHP)
function logout() {
    unset($_SESSION['user']);
    header('Location: /login'); // Redirect to login page
    exit();
}

// Simulating the fetchUserData function
function fetchUserData() {
    try {
        // Simulate fetching user data
        $userResponse = ['success' => true, 'user' => ['name' => 'John Doe', 'email' => 'john.doe@example.com']];
        
        // Simulate fetching job data
        $jobsResponse = ['success' => true, 'jobs' => [
            ['id' => 1, 'title' => 'Job 1', 'description' => 'Job 1 description', 'requirements' => 'Requirements for Job 1', 'created_at' => '2024-07-25 10:00:00'],
            ['id' => 2, 'title' => 'Job 2', 'description' => 'Job 2 description', 'requirements' => 'Requirements for Job 2', 'created_at' => '2024-07-26 09:30:00']
        ]];
        
        // Simulate fetching applications data
        $applicationsResponse = ['success' => true, 'applications' => [
            ['job_title' => 'Job 1', 'name' => 'Applicant 1', 'email' => 'applicant1@example.com', 'status' => 'Pending'],
            ['job_title' => 'Job 2', 'name' => 'Applicant 2', 'email' => 'applicant2@example.com', 'status' => 'Accepted']
        ]];
        
        // Handle responses
        if ($userResponse['success']) {
            $user = $userResponse['user'];
        } else {
            throw new Exception('Failed to fetch user data');
        }
        
        if ($jobsResponse['success']) {
            $jobs = $jobsResponse['jobs'];
        } else {
            throw new Exception('Failed to fetch job data');
        }
        
        if ($applicationsResponse['success']) {
            $applications = $applicationsResponse['applications'];
        } else {
            throw new Exception('Failed to fetch applications');
        }
        
        // Return fetched data
        return compact('user', 'jobs', 'applications');
    } catch (Exception $e) {
        // Handle exceptions
        return ['error' => 'An error occurred while fetching data.'];
    }
}

// Initialize session (for user login/logout)
session_start();

// Fetch user data and other necessary data
$data = fetchUserData();

// Check if there's an error
if (isset($data['error'])) {
    // Display error message
    echo '<p>' . $data['error'] . '</p>';
    exit();
}

// Extract fetched data
$user = $data['user'];
$jobs = $data['jobs'];
$applications = $data['applications'];

// Display HTML content
?>
<div style="padding: 70px; text-align: center; background-color: #fff;">
    <?php if ($user) : ?>
        <div>
            <h2>Welcome <?php echo $user['name']; ?>!</h2>
            <p>Email: <?php echo $user['email']; ?></p>
            <button onclick="handleLogout()">Logout</button>
        </div>
    <?php endif; ?>

    <p>Our mission is to empower job seekers by providing them with access to a wide range of career opportunities while offering employers efficient tools to manage their hiring processes.</p>
    
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Requirements</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><?php echo $job['title']; ?></td>
                    <td><?php echo $job['description']; ?></td>
                    <td><?php echo $job['requirements']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($job['created_at'])); ?></td>
                    <td><button onclick="handleApply(<?php echo json_encode($job); ?>)">Apply</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Application Status</h2>
    <table>
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application) : ?>
                <tr>
                    <td><?php echo $application['job_title']; ?></td>
                    <td><?php echo $application['name']; ?></td>
                    <td><?php echo $application['email']; ?></td>
                    <td><?php echo $application['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer class="footer">
        <p>&copy; 2024 JobConnect. All rights reserved.</p>
    </footer>
</div>

<script>
    // JavaScript equivalent functions
    function handleLogout() {
        fetch('/logout.php', {
            method: 'POST'
        }).then(() => {
            window.location.href = '/login';
        }).catch((error) => {
            console.error('Logout error:', error);
        });
    }

    function handleApply(job) {
        const title = encodeURIComponent(job.title);
        const id = job.id;
        const applicationUrl = `http://localhost:3000/application-form?title=${title}&id=${id}`;
        window.open(applicationUrl, '_blank');
    }
</script>
