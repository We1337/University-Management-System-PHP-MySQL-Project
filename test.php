<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "uni";

try {
  // Create connection
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
} 
catch(PDOException $e) 
{
  die("Connection failed: " . $e->getMessage());
}
echo "Connected successfully";
?>