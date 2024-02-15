<?php
$hostname = "your_mysql_host";  // Replace with your MySQL host
$username = "your_mysql_username";  // Replace with your MySQL username
$password = "your_mysql_password";  // Replace with your MySQL password
$database = "your_mysql_database";  // Replace with your MySQL database name

// Open a connection to the MySQL server
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to retrieve weather data from the database
$query = "SELECT * FROM weather_data";

// Perform the query
$result = $mysqli->query($query);

// Check if the query was successful
if ($result) {
    // Fetch data and store it in an array
    $weatherData = array();
    while ($row = $result->fetch_assoc()) {
        $weatherData[] = $row;
    }

    // Close the result set
    $result->close();

    // Close the MySQL connection
    $mysqli->close();

    // Return weather data as JSON
    header('Content-Type: application/json');
    echo json_encode($weatherData);
} else {
    // If the query fails, return an error message
    echo "Error: " . $query . "<br>" . $mysqli->error;

    // Close the MySQL connection
    $mysqli->close();
}
?>
