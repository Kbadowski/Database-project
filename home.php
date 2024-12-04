<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  //  header('Location: login.php');
    //exit;
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #333;
        }
        .button-container {
            margin-top: 20px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Welcome to Pantology!</h1>
    <p>Choose an action below:</p>

    <div class="button-container">
        <a href="pantslookup.php" class="button">Pants Lookup</a>
        <a href="transaction.php" class="button">Transaction Lookup</a>
    </div>
</body>
</html>
