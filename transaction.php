//transaction

<?php
// Initialize the session
session_start();

// Define database connection constants
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $serial_num = $_POST['serial_num'];
    $quantity = $_POST['quantity'];

    // Validate inputs
    if (empty($customer_id) || empty($serial_num) || empty($quantity)) {
        $error_message = "All fields are required.";
    } else {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind statement
        $stmt = $conn->prepare("INSERT INTO transactions (customer_id, serial_num, quantity, transaction_date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("isi", $customer_id, $serial_num, $quantity);

        // Execute and check if successful
        if ($stmt->execute()) {
            $success_message = "Transaction successfully recorded.";
        } else {
            $error_message = "Error: " . $stmt->error;
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
    <title>Record Transaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Record a Transaction</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form action="transaction.php" method="post">
                <div class="form-group">
                    <label for="customer_id">Customer ID:</label>
                    <input type="text" name="customer_id" id="customer_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="serial_num">Pants Serial Number:</label>
                    <input type="text" name="serial_num" id="serial_num" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
