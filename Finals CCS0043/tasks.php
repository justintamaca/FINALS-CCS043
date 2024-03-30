<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "task_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = '';

function moveTaskToRemoved($taskId) {
    global $conn;
    // Move task from active_tasks to removed_tasks
    $sqlMove = "INSERT INTO removed_tasks (task_name, description) SELECT task_name, description FROM active_tasks WHERE id = ?";
    // Delete the task from active_tasks
    $sqlDelete = "DELETE FROM active_tasks WHERE id = ?";
    
    // Prepare and bind parameters for the move query
    $stmtMove = $conn->prepare($sqlMove);
    $stmtMove->bind_param("i", $taskId);
    
    // Prepare and bind parameters for the delete query
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $taskId);
    
    // Execute move query
    if ($stmtMove->execute()) {
        // Execute delete query
        if ($stmtDelete->execute()) {
            return true;
        } else {
            echo "Error deleting task from active_tasks table: " . $stmtDelete->error;
            return false;
        }
    } else {
        echo "Error moving task to removed_tasks table: " . $stmtMove->error;
        return false;
    }
}






function markTaskAsCompleted($taskId) {
    global $conn;
    // Move task from active_tasks to completed_tasks
    $sqlMove = "INSERT INTO completed_tasks (task_name, description) SELECT task_name, description FROM active_tasks WHERE id = ?";
    // Delete the task from active_tasks
    $sqlDelete = "DELETE FROM active_tasks WHERE id = ?";
    
    // Prepare and bind parameters for the move query
    $stmtMove = $conn->prepare($sqlMove);
    $stmtMove->bind_param("i", $taskId);
    
    // Prepare and bind parameters for the delete query
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $taskId);
    
    // Execute move query
    if ($stmtMove->execute()) {
        // Execute delete query
        if ($stmtDelete->execute()) {
            return true;
        } else {
            echo "Error deleting task from active_tasks table: " . $stmtDelete->error;
            return false;
        }
    } else {
        echo "Error moving task to completed_tasks table: " . $stmtMove->error;
        return false;
    }
}



if (isset($_POST['remove_task'])) {
    $taskIdToRemove = $_POST['task_id'];
    if (moveTaskToRemoved($taskIdToRemove)) { // Corrected function call
        $successMessage = "Task removed successfully";
    } else {
        echo "Error: Failed to remove task";
    }
}


if (isset($_POST['mark_as_completed'])) {
    $taskIdToComplete = $_POST['task_id'];
    if (markTaskAsCompleted($taskIdToComplete)) {
        $successMessage = "Task marked as completed and removed successfully";
    } else {
        echo "Error: Failed to mark task as completed";
    }
}

$sqlPendingCount = "SELECT COUNT(*) as pending_count FROM active_tasks WHERE completed = 0";
$resultPendingCount = $conn->query($sqlPendingCount);
$pendingCount = $resultPendingCount->fetch_assoc()['pending_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
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
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

    <div class='task-list'>
        <?php
        $sql = "SELECT * FROM active_tasks WHERE completed = 0";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='task'>";
                echo "<h3>" . $row["task_name"]. "</h3>";
                echo "<p>" . $row["description"]. "</p>";
                echo "<form action='tasks.php' method='POST'>";
                echo "<input type='hidden' name='task_id' value='".$row["id"]."'>";
                echo "<button type='submit' name='remove_task'>Remove Task</button>";
                echo "<span class='button-space'></span>"; // Add space between buttons
                echo "<button type='submit' name='mark_as_completed'>Mark as Completed</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No pending tasks found</p>";
        }
        ?>
    </div>


    </section>
</body>
</html>
