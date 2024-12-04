<?php
// Initialize the session
session_start();
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username

// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
//run a query to get all department names  
$sqlstatement = $conn->prepare("SELECT distinct dept_name FROM department order by dept_name asc"); //prepare the statement
$sqlstatement->execute(); //execute the query
$departments = $sqlstatement->get_result(); //return the results we'll use them in the web form
$sqlstatement->close();

function displayusers() {
  global $conn; //reference the global connection object (scope)
  $sql = "SELECT * FROM users2";
        $result = $conn->query($sql);

     if ($result->num_rows > 0) {
        // Setup the table and headers
        echo "<Center><table><tr><th>customer ID</th><th>first Name</th><th>last name</th><th>email</th><th>password</th><th>waist</th><th>hip</th><th>inseam</th></tr>";
       // output data of each row into a table row
       while($row = $result->fetch_assoc()) {
           echo "<tr><td>".$row["customer_ID"]."</td>
                <td>".$row["FirstName"]."</td>
                <td> ".$row["LastName"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["pwd"]."</td>
                <td>".$row["waist"]."</td>
                <td>".$row["hip"]."</td>
                <td>".$row["inseam"]."</td>
                </tr>";
           }
      echo "</table></center>"; // close the table
      echo "There are ". $result->num_rows . " results.";
     // Don't render the table if no results found
    } else {
      echo "0 results";
                                                                                                                      }
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>new user</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
New User Entry Form:</h2></p>
<form action="adduser.php" method=get>
	Enter user first name: <input type=text size=20 name="FirstName">
    Enter user last name: <input type=text size=20 name="LastName">
	<p>Enter user email: <input type=text size=5 name="email">
    <p>Enter user password: <input type=text size=5 name="pwd">
    <p>Enter user waist size(in): <input type=text size=5 name="waist">
    <p>Enter user hip size(in): <input type=text size=5 name="hip">
    <p>Enter user inseam size(in): <input type=text size=5 name="inseam">

  <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter new users information and submit the form.";
    echo "<p>Here is a list of the current users:";
    displayFaculty();
}
else {
  if (!empty($_GET["FirstName"]) && !empty($_GET["LastName"])  && !empty($_GET["email"])  && !empty($_GET["pwd"])  && !empty($_GET["waist"])  && !empty($_GET["hip"])  && !empty($_GET["inseam"]))
{
   $userfName = $_GET["FirstName"]; //gets name from the form
   $userlName = $_GET["LastName"]; //gets id from the form
   $useremail = $_GET["email"]; //get department from the form
   $userpass = $_GET["pwd"];
   $userwaist = $_GET["waist"];
   $userhip = $_GET["hip"];
   $userinseam = $_GET["inseam"];

   $sqlstatement = $conn->prepare("INSERT INTO name2 values(?,?)"); //prepare the statement
   if ($sqlstatement === false) {
    die("SQL preparation failed: " . $conn->error);
    }
    $sqlstatement->bind_param("ss",$userfName,$userlName); //insert the variables into the ? in the above statement
    $sqlstatement->execute(); //execute the query
    echo $sqlstatement->error; //print an error if the query fails
    $sqlstatement->close();

    $sqlstatement = $conn->prepare("INSERT INTO sizing2 values(?,?,?)"); //prepare the statement
   if ($sqlstatement === false) {
    die("SQL preparation failed: " . $conn->error);
    }
    $sqlstatement->bind_param(types: "sss", $userwaist, $userhip, $userinseam);
    $sqlstatement->execute(); //execute the query
    echo $sqlstatement->error; //print an error if the query fails
    $sqlstatement->close();

    $sqlstatement = $conn->prepare("INSERT INTO login2 values(?,?)"); //prepare the statement
   if ($sqlstatement === false) {
    die("SQL preparation failed: " . $conn->error);
    }
    $sqlstatement->bind_param("ss",$useremail,$userpass); //insert the variables into the ? in the above statement
    $sqlstatement->execute(); //execute the query
    echo $sqlstatement->error; //print an error if the query fails
    $sqlstatement->close();

 }
 else {
	 echo "<b> Error: Please fill in all feileds to proceed.</b>";
 }
 
   echo "<p>Here is a list of the current users:";
   displayusers();
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
</body>

</html>
