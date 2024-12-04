<?php
//Initialize the session
session_start();

//Define constants for PHP
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username

//Define variables for error and success messages
$error = "";
$success = "";

//Submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    //Check user's input
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        //Create connection
        $conn = new mysqli($servername, $email, $password, $dbname);

        //Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //Prepare SQL statement to fetch user
        $stmt = $conn->prepare("SELECT customer_ID, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        //Verify email and password
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                //Credentials are correct, login successful
                $_SESSION['loggedin'] = true;
                $_SESSION['customer_ID'] = $row['id'];
                $_SESSION['email'] = $row['email'];

                //Redirect to pantslookup.php
                $success = "Login successful! Redirecting...";
                header("location: pantslookup.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        .form-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #04AA6D;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php
        if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (!empty($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>
        <form action="login.php" method="post">
            <p><input type="text" name="email" placeholder="Email" required></p>
            <p><input type="password" name="password" placeholder="Password" required></p>
            <p><input type="submit" value="Login"></p>
        </form>
    </div>
</body>
</html>
