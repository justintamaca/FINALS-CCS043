<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the main CSS file -->
    <link rel="stylesheet" href="registration_styles.css"> <!-- Link to the additional CSS file -->
</head>
<body>
    <div class="card-container">
        <div class="registration-card">
            <h2>Register</h2>
            <form class="registration-form" action="registration_process.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit" name="register">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
