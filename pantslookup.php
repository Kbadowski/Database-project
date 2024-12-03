<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page

//Define some constants in this PHP code block
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pantology </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		    table, th, td { border: 1px solid black; }
        input[type=text] { width: 15%; padding: 12px 20px; margin: 8px 0;  box-sizing: border-box;}
        input[type=button], input[type=submit], input[type=reset] { background-color: #04AA6D;  border: none;  color: white;  padding: 16px 32px;  text-decoration: none;  margin: 4px 2px;  cursor: pointer;}
    </style>
</head>
<body>
<p><h2>Pants look Up:</h2></p>
<form action="pantslookup.php" method=get>
	<p>Enter the Serial Number: <input type=text size=20 name="serial_num">
	<p>Enter max price: <input type=text size=5 name="price">
        <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter a Serial number or your max price and submit the form.";
}
else {
// Create connection

 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
 if (!empty($_GET["serial_num"]))
 {
   $serialnum = $_GET["serial_num"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT pants.serial_num, pants.price, pants.size_N, size_L.lenngth, color.color, style.style, category.category, pants.stock
	FROM pants
   	INNER Join color ON pants.color_ID = color.color_ID
    	INNER JOIN style ON pants.style_ID = style.style_ID
   	 INNER JOIN size_L ON pants.length_ID = size_L.length_ID
   	 INNER JOIN category ON pants.category_ID = category.category_ID
    	WHERE serial_num = ?"); //prepare the statement
   $searchTerm = "%".$serialnum."%";
   $sqlstatement->bind_param("s",$searchTerm); //insert the String variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 elseif (!empty($_GET["price"]))
 {
   $pantsprice = $_GET["price"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT serial_num, price, stock FROM pants where price LIKE ?"); //prepare the statement
   $searchTerm = "%".$pantsprice."%";
   $sqlstatement->bind_param("s",$searchTerm); //insert the integer variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 else {
	 echo "<b>Please enter a serial number or max price  number</b>";
 }
   if ($result->num_rows > 0) {
     	// Setup the table and headers
	echo "<center><table><tr>
            <th>Serial Number</th>
            <th>Price</th>
            <th>stock</th>
            <th>size number</th>
            <th>size length</th>
            <th>color</th>
            <th>style</th>
            <th>category</th>
            </tr>";
	// output data of each row into a table row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr>
                <td>".$row["serial_num"]."</td>
                <td>".$row["price"]."</td>
                <td> ".$row["stock"]."</td>
                <td> ".$row["size_N"]."</td>
                <td> ".$row["length_ID"]."</td>
                <td> ".$row["color_ID"]."</td>
                <td> ".$row["style_ID"]."</td>
                <td> ".$row["category_ID"]."</td>
                </tr>";
   	}
	
	echo "</table> </center>"; // close the table
	echo "There are ". $result->num_rows . " results.";
	// Don't render the table if no results found
   	} else {
               echo "$name not found. 0 results";
	} 
   $conn->close();
 } //end else condition where form is submitted
 ?> <!-- this is the end of our php code -->
<p> Thanks for using the directory search! 
</body>
</html>
