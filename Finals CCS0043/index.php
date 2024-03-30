<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the main CSS file -->
    <link rel="stylesheet" href="registration_styles.css"> <!-- Link to the additional CSS file -->
</head>
<body>
<div class="card-container">
     <div class="registration-card">
         <h2>Login</h2>
         <form action="login_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="login">Login</button>
         </form>
        <p>Don't have an account yet? <a href="register.php">Register here</a></p>
     </div>
</div>
</body>
</html>
