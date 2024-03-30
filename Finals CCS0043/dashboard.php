<?php
// Start the session
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

$successMessage = ''; // Initialize success message variable

// Function to remove a task
function removeTask($taskId) {
    global $conn;
    $sql = "DELETE FROM tasks WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to mark a task as completed
function markTaskAsCompleted($taskId) {
    global $conn;
    $sql = "UPDATE tasks SET completed = 1 WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        // Remove the task if it's marked as completed
        removeTask($taskId);
        return true;
    } else {
        return false;
    }
}

if(isset($_POST['create_task'])) {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO active_tasks (task_name, description) VALUES ('$task_name', '$description')";
    if ($conn->query($sql) === TRUE) {
        // Set success message
        $successMessage = "New task created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle removal of task
if (isset($_POST['remove_task'])) {
    $taskIdToRemove = $_POST['task_id'];
    if (removeTask($taskIdToRemove)) {
        $successMessage = "Task removed successfully";
    } else {
        echo "Error: Failed to remove task";
    }
}

// Handle marking a task as completed
if (isset($_POST['finish_task'])) {
    $taskIdToFinish = $_POST['task_id'];
    if (markTaskAsCompleted($taskIdToFinish)) {
        $successMessage = "Task marked as completed and removed successfully";
    } else {
        echo "Error: Failed to mark task as completed";
    }
}

// Count pending tasks
$sqlPendingCount = "SELECT COUNT(*) as pending_count FROM active_tasks WHERE completed = 0";
$resultPendingCount = $conn->query($sqlPendingCount);
$pendingCount = $resultPendingCount->fetch_assoc()['pending_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file for styling -->
    <script>
        // Function to hide the success message after a certain duration
        function hideSuccessMessage() {
            var successMessage = document.querySelector('.success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 1000); 
            }
        }

        // Call the function when the page is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            hideSuccessMessage();
        });
    </script>
</head>
<body>
    <header>
        <h1>Task Manager</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <?php if ($pendingCount > 0): ?> <!-- Check if pending count is greater than 0 -->
                    <li><a href="tasks.php">Tasks (<?php echo $pendingCount; ?>)</a></li> <!-- Display the count of pending tasks -->
                <?php else: ?>
                    <li><a href="tasks.php">Tasks</a></li> <!-- Display tasks without count if pending count is 0 -->
                <?php endif; ?>
                <li><a href="taskhistory.php">Task History</a></li>
                <li><a href="logout.php">Logout</a></li> <!-- Logout link -->
            </ul>
        </nav>
    </header>
    
    <section class="main-content">
        <!-- Task Management Interface -->
        <div class="task-form">
            <h2>Create New Task</h2>
            <form action="dashboard.php" method="POST">
                <input type="text" id="task_name" name="task_name" placeholder="Task Name" required>
                <textarea id="description" name="description" placeholder="Description" required></textarea>
                <button type="submit" name="create_task">Create Task</button>
            </form>
        </div>

        <!-- Display Success Message -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

       <!-- Display pending tasks count -->
       <?php if ($pendingCount > 0): ?>
           <div class="task-count">
                <h3>Pending Tasks: <?php echo $pendingCount; ?></h3>
           </div>
       <?php endif; ?>

    </section>

</body>
</html>

