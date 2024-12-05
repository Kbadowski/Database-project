<?php
session_start();

$servername = 'localhost';
$username = 'kbadowsk';
$password = 'Gx0Vf7bb';
$dbname = 'kbadowsk';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New transaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
    <h2>New Transaction Form:</h2>
    <form action="addtransactions.php" method="get">
        Customer ID: <input type="text" name="customer_ID" required>
        pants serial number: <input type="text" name="serial_num" required>
        
        <input type="submit" value="Submit">
            <input type="hidden" name="form_submitted" value="1">
    </form>

<?php
if (!isset($_GET["form_submitted"]))
{
                echo "Hello. Please enter new transaction information and submit the form.";
}
else {
    if (!empty($_GET["customer_ID"]) && !empty($_GET["serial_num"])){
        $usercustid = $_GET["customer_ID"];
        $usersnum = $_GET["serial_num"];
       

        $conn->begin_transaction(); // Start transaction
        try {
            // Insert into name2 table
            $stmt = $conn->prepare("INSERT INTO transactions2 (customer_ID, serial_num) VALUES (?, ?)");
            if ($sqlstatement === false) {
                die("SQL preparation failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $usercustid, $usersnum);
            $stmt->execute();
            $name_id = $conn->insert_id; // Get the generated ID
            $stmt->close();


            $conn->commit(); // Commit transaction
            echo "<p>User successfully added!</p>";
        } catch (Exception $e) {
            $conn->rollback(); // Rollback transaction on error
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p><b>Error: Please fill in all fields to proceed.</b></p>";
    }

    echo "<p>your transaction has been added!</p>";
}

$conn->close();
?>
<p><a href="home.php">Go back home</a></p>
</body>
</html>
