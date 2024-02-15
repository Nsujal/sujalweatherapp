<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weather_app";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$api_key = "0112d233c06a39d9225e4d2b6063b385"; 
$city = isset($_GET['city']) ? $_GET['city'] : '';

// Check if city parameter is provided
if (empty($city)) {
    echo json_encode(array('error' => 'City parameter is missing or empty.'));
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/weather?units=metric&q={$city}&appid={$api_key}"; 
$dataString = file_get_contents($url); 

$data = json_decode($dataString, true);

$date = date('Y-m-d');
$day = date("l", strtotime($date));
$temperature = $data['main']['temp'];
$name =  $data['name'];
$humidity = $data['main']['humidity'];
$pressure = $data['main']['pressure'];
$wind_speed = $data['wind']['speed'];
$description = $data['weather'][0]['description'];
$icon = $data['weather'][0]['icon'];

// Create table if not exists
$mainsql = "CREATE TABLE IF NOT EXISTS weather_data (
    city VARCHAR(255) NOT NULL,
    date VARCHAR(255) NOT NULL,
    day VARCHAR(255) NOT NULL,
    temperature DECIMAL(5,2) NOT NULL,
    humidity DECIMAL(5,2) NOT NULL,
    wind DECIMAL(4,2) NOT NULL,
    pressure DECIMAL(7,2) NOT NULL,
    description VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL
)";
$conn->query($mainsql);

// Insert data into the table
$ins_sql = "INSERT INTO weather_data (city,date, day, temperature, humidity, wind, pressure,description,icon) 
    VALUES 
    ('$name','$date', '$day', '$temperature', '$humidity', '$wind_speed', '$pressure', '$description', '$icon')";
$conn->query($ins_sql);

// Retrieve data from the table
$select_sql = "SELECT * FROM weather_data WHERE city='$city' AND date='$date';";
$result = $conn->query($select_sql);

// Check if data is found
if ($result->num_rows > 0) {
    // Fetch data and store it in an array
    $weatherData = array();
    while ($row = $result->fetch_assoc()) {
        $weatherData[] = $row;
    }

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($weatherData);
} else {
    // Return error message if no data is found
    echo json_encode(array('error' => 'No weather data found for the specified city and date.'));
}

// Close the connection
mysqli_close($conn);
?>