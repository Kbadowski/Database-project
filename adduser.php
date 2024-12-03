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

function displayFaculty() {
  global $conn; //reference the global connection object (scope)
  $sql = "SELECT * FROM student";
        $result = $conn->query($sql);

     if ($result->num_rows > 0) {
        // Setup the table and headers
        echo "<Center><table><tr><th>ID</th><th>Name</th><th>Department</th><th>Total_Credits</th></tr>";
       // output data of each row into a table row
       while($row = $result->fetch_assoc()) {
           echo "<tr><td>".$row["ID"]."</td><td>".$row["name"]."</td><td> ".$row["dept_name"]."</td><td>$".$row["tot_cred"]."</td></tr>";
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
    <title>New Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
New Student Entry Form:</h2></p>
<form action="HW3data.php" method=get>
	Enter Faculty name: <input type=text size=20 name="name">
	<p>Enter Student ID number: <input type=text size=5 name="id">
	<p>Select Student Department: 
	<select name="department"
        <?php //iterate through the results of the department query to build the web form
	while($department = $departments->fetch_assoc()) {
	?>
		<option value="<?php echo $department["dept_name"]; ?>"><?php echo $department["dept_name"]; ?>
		</option>
	<?php } //end while loop ?>
	</select> 

  <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter new Student information and submit the form.";
    echo "<p>Here is a list of the current Student:";
    displayFaculty();
}
else {
  if (!empty($_GET["name"]) && !empty($_GET["id"]))
{
   $studentName = $_GET["name"]; //gets name from the form
   $studentID = $_GET["id"]; //gets id from the form
   $studentDept = $_GET["department"]; //get department from the form
   $studentcredits = 0; //get salary from the form
   $sqlstatement = $conn->prepare("INSERT INTO student values(?, ?, ?, ?)"); //prepare the statement
   if ($sqlstatement === false) {
    die("SQL preparation failed: " . $conn->error);
}

   $sqlstatement->bind_param("sssd",$studentID,$studentName,$studentDept,$studentcredits); //insert the variables into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   echo $sqlstatement->error; //print an error if the query fails
   $sqlstatement->close();
 }
 else {
	 echo "<b> Error: Please enter a name, an ID number and a salary to proceed.</b>";
 }
 
   echo "<p>Here is a list of the current Students:";
   displayFaculty();
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
</body>

</html>
