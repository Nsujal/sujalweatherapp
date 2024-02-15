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

// OpenWeatherMap API key
$apikey = "0112d233c06a39d9225e4d2b6063b385";

// Default city
$defaultcity = "Kolar";

// OpenWeatherMap API URL
$apiurl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . $defaultcity . "&appid=" . $apikey;

// Fetch weather data from OpenWeatherMap API
$response = file_get_contents($apiurl);

if ($response !== false) {
    $data = json_decode($response, true);

    // Check if the city is found
    if ($data['cod'] == 200) {
        // Extract relevant weather data
        $city = $data['name'];
        $temperature = $data['main']['temp'];
        $humidity = $data['main']['humidity'];
        $pressure = $data['main']['pressure'];
        $wind_speed = $data['wind']['speed'];

        // Insert data into MySQL table
        $query = "INSERT INTO weather_data (city, temperature, humidity, pressure, wind_speed) 
                  VALUES ('$city', '$temperature', '$humidity', '$pressure', '$wind_speed')";

        if ($mysqli->query($query) === TRUE) {
            echo "Weather data inserted successfully!";
        } else {
            echo "Error: " . $query . "<br>" . $mysqli->error;
        }
    } else {
        echo "Error: City not found.";
    }
} else {
    echo "Error fetching data from OpenWeatherMap API.";
}

// Close the MySQL connection
$mysqli->close();
?>
