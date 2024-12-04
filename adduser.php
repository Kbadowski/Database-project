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

function displayusers() {
    global $conn;
    $sql = "SELECT * FROM users2";
        $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<center><table><tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Waist</th><th>Hip</th><th>Inseam</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["user_id"]."</td>
                <td>".$row["first_name"]."</td>
                <td>".$row["last_name"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["waist"]."</td>
                <td>".$row["hip"]."</td>
                <td>".$row["inseam"]."</td>
                </tr>";
        }
        echo "</table></center>";
        echo "There are " . $result->num_rows . " results.";
    } else {
        echo "0 results";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
    <h2>New User Entry Form:</h2>
    <form action="adduser.php" method="get">
        First Name: <input type="text" name="FirstName" required>
        Last Name: <input type="text" name="LastName" required>
        Email: <input type="email" name="email" required>
        Password: <input type="password" name="pwd" required>
        Waist Size (in): <input type="text" name="waist" required>
        Hip Size (in): <input type="text" name="hip" required>
        Inseam Size (in): <input type="text" name="inseam" required>

        <input type="submit" value="Submit">
            <input type="hidden" name="form_submitted" value="1">
    </form>

<?php
if (!isset($_GET["form_submitted"]))
{
                echo "Hello. Please enter new users information and submit the form.";
    echo "<p>Here is a list of the current users:";
    displayFaculty();
}
else {
    if (!empty($_GET["FirstName"]) && !empty($_GET["LastName"]) && !empty($_GET["email"]) && !empty($_GET["pwd"]) && !empty($_GET["waist"]) && !empty($_GET["hip"]) && !empty($_GET["inseam"])) {
        $userfName = $_GET["FirstName"];
        $userlName = $_GET["LastName"];
        $useremail = $_GET["email"];
        $userpass = $_GET["pwd"];
        $userwaist = $_GET["waist"];
        $userhip = $_GET["hip"];
        $userinseam = $_GET["inseam"];

        $conn->begin_transaction(); // Start transaction
        try {
            // Insert into name2 table
            $stmt = $conn->prepare("INSERT INTO name2 (FirstName, LastName) VALUES (?, ?)");
            if ($sqlstatement === false) {
                die("SQL preparation failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $userfName, $userlName);
            $stmt->execute();
            $name_id = $conn->insert_id; // Get the generated ID
            $stmt->close();

            // Insert into sizing2 table
            $stmt = $conn->prepare("INSERT INTO sizing2 (waist, hip, inseam) VALUES (?, ?, ?)");
            if ($sqlstatement === false) {
                die("SQL preparation failed: " . $conn->error);
            }
            $stmt->bind_param("sss", $userwaist, $userhip, $userinseam);
            $stmt->execute();
            $sizing_id = $conn->insert_id;
            $stmt->close();

            // Insert into login2 table
            $stmt = $conn->prepare("INSERT INTO login2 (email, pwd) VALUES (?, ?)");
            if ($sqlstatement === false) {
                die("SQL preparation failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $useremail, $userpass);
            $stmt->execute();
            $login_id = $conn->insert_id;
            $stmt->close();

            // Insert into users2 table
            $stmt = $conn->prepare("INSERT INTO users2 (name_id, sizing_id, login_id) VALUES (?, ?, ?)");
            if ($sqlstatement === false) {
                die("SQL preparation failed: " . $conn->error);
            }
            $stmt->bind_param("iii", $name_id, $sizing_id, $login_id);
            $stmt->execute();
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

    echo "<p>Here is a list of the current users:</p>";
    displayusers();
}

$conn->close();
?>
</body>
</html>
