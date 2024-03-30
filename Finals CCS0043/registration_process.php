<?php
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

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if username already exists
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "Username already exists";
    } else {
        // Insert new user into the database
        $insert_sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($insert_sql) === TRUE) {
            // Echo the message
            echo "Registered Successfully! Redirecting to Login";
            ?>
            <script>
                // Redirect to login page after 1 second
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 1000);
            </script>
            <?php
            exit; // Ensure script stops execution after redirection
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
