<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Database Connection
$servername = "localhost";
$username = "root"; // Replace with your actual MySQL username
$password = ""; // Replace with your actual MySQL password
$dbname = "task_management"; // Replace with your actual MySQL database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tasks from both completed_tasks and removed_tasks tables
$sql = "SELECT task_name, description FROM completed_tasks 
         UNION 
        SELECT task_name, description FROM removed_tasks";

$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $tasks = array();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task History</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file for styling -->
</head>
<body>
    <header>
        <h1>Task History</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="tasks.php">Tasks</a></li>
                <li><a href="taskhistory.php">Task History</a></li>
                <li><a href="logout.php">Logout</a></li> <!-- Logout link -->
            </ul>
        </nav>
    </header>
    
    <section class="main-content">
        <div class="task-history">
            <h2>All Tasks</h2>
            <?php if (!empty($tasks)): ?>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <li>
                            <strong><?php echo $task['task_name']; ?></strong>
                            <p><?php echo $task['description']; ?></p>
                            <!-- Display more task details if needed -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tasks found.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
