<?php
// Initialize the session
session_start();

// Define constants
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pantology</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        table, th, td { border: 1px solid black; }
        input[type=text] { width: 15%; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; }
        input[type=button], input[type=submit], input[type=reset] {
            background-color: #04AA6D;
            border: none;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Pants Lookup:</h2>
    <form action="transaction.php" method="get">
        <p>Enter the Customer ID: <input type="text" size="20" name="customer_ID"></p>
        <p>
            <input type="submit" value="Submit">
            <input type="hidden" name="form_submitted" value="1">
        </p>
    </form>

    <?php
    if (!isset($_GET["form_submitted"])) {
        echo "Hello. Please enter a Customer ID and submit the form.";
    } else {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (!empty($_GET["customer_ID"])) {
            $serialnum = $_GET["customer_ID"]; // Get Customer ID from form

            // Prepare the statement
            $sqlstatement = $conn->prepare("SELECT transaction_ID, customer_ID, serial_num
                                            FROM transactions
                                            WHERE customer_ID = ?");

            if ($sqlstatement) {
                $sqlstatement->bind_param("s", $serialnum); // Bind the variable
                $sqlstatement->execute(); // Execute the query
                $result = $sqlstatement->get_result(); // Get the results
                $sqlstatement->close();

                if ($result->num_rows > 0) {
                    // Setup the table and headers
                    echo "<center><table><tr>
                        <th>Transaction ID</th>
                        <th>Serial Number</th>
                        <th>Customer ID</th>
                        </tr>";

                    // Output data of each row into a table row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row["transaction_ID"] . "</td>
                            <td>" . $row["serial_num"] . "</td>
                            <td>" . $row["customer_ID"] . "</td>
                            </tr>";
                    }

                    echo "</table></center>"; // Close the table
                    echo "There are " . $result->num_rows . " results.";
                } else {
                    echo "Customer ID $serialnum not found. 0 results.";
                }
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "<b>Please enter a Customer ID number</b>";
        }

        $conn->close();
    }
    ?>
    <p>Thanks for using the directory search!</p>
</body>
</html>
